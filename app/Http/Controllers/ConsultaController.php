<?php

namespace App\Http\Controllers;

use App\Models\IntensidadeModel;
use App\Models\PacienteModel;
use App\Models\PeriodoModel;
use App\Models\SintomasModel;
use App\Services\ConsultaPrioridade;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultas = DB::table('consultas_final')
            ->join('pacientes_final', 'consultas_final.paciente_id', '=', 'pacientes_final.id')
            ->select(
                'consultas_final.id',
                'pacientes_final.nome_completo as paciente_nome',
                'consultas_final.data_consulta',
                'consultas_final.horario_consulta',
                'consultas_final.prioridade',
                'consultas_final.status'
            )
            ->orderByDesc('consultas_final.prioridade')
            ->orderByDesc('consultas_final.data_consulta')
            ->orderByDesc('consultas_final.horario_consulta')
            ->paginate(10);

        return view('consultas.index', compact('consultas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('consultas.add', $this->opcoesFormulario());
    }

    public function totem()
    {
        return view('consultas.totem', $this->opcoesFormulario());
    }

    private function opcoesFormulario(): array
    {
        return [
            'sintomas' => SintomasModel::opcoes(),
            'intensidades' => IntensidadeModel::opcoes(),
            'periodos' => PeriodoModel::opcoes(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cpf' => ['required', 'string', 'max:14'],
            'sexo_id' => ['required', 'integer', Rule::in([1, 2])],
            'possui_doencas_cronicas' => ['required', 'integer', Rule::in([0, 1])],
            'sintomas' => ['required', 'array', 'min:1'],
            'sintomas.*' => ['required', 'integer', Rule::in(array_keys(SintomasModel::opcoes()))],
            'intensidade_id' => ['required', 'integer', Rule::in(array_keys(IntensidadeModel::opcoes()))],
            'periodo_id' => ['required', 'integer', Rule::in(array_keys(PeriodoModel::opcoes()))],
        ]);
        $prioridade = ConsultaPrioridade::calcular(
            $validated['sintomas'],
            $validated['intensidade_id'],
            $validated['possui_doencas_cronicas'],
            $validated['periodo_id']
        );

        $cpf = trim($validated['cpf']);
        $cpfSemFormatacao = preg_replace('/\D/', '', $cpf);
        $cpfsParaBusca = [$cpf];

        if (strlen($cpfSemFormatacao) === 11) {
            $cpfsParaBusca[] = $cpfSemFormatacao;
            $cpfsParaBusca[] = substr($cpfSemFormatacao, 0, 3).'.'
                .substr($cpfSemFormatacao, 3, 3).'.'
                .substr($cpfSemFormatacao, 6, 3).'-'
                .substr($cpfSemFormatacao, 9, 2);
        }

        DB::transaction(function () use ($validated, $cpf, $cpfsParaBusca, $prioridade): void {
            try {
                $paciente = PacienteModel::whereIn('cpf', array_unique($cpfsParaBusca))
                    ->firstOrFail();
            } catch (ModelNotFoundException) {
                $paciente = PacienteModel::create([
                    'cpf' => $cpf,
                    'sexo_id' => $validated['sexo_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $dataConsulta = now();

            DB::table('consultas_final')->insert([
                'paciente_id' => $paciente->id,
                'data_consulta' => $dataConsulta->toDateString(),
                'horario_consulta' => $dataConsulta->format('H:i:s'),
                'status' => 'Aguardando',
                'sintomas_id' => json_encode($validated['sintomas'], JSON_THROW_ON_ERROR),
                'possui_doencas_cronicas' => $validated['possui_doencas_cronicas'],
                'intensidade_id' => $validated['intensidade_id'],
                'periodo_id' => $validated['periodo_id'],
                'prioridade' => $prioridade,
                'created_at' => $dataConsulta,
                'updated_at' => $dataConsulta,
            ]);

            DB::table('pacientes_final')
                ->where('id', $paciente->id)
                ->increment('numero_consultas', 1, [
                    'data_ultima_consulta' => $dataConsulta->toDateString(),
                    'updated_at' => $dataConsulta,
                ]);
        });

        return redirect()
            ->route($request->input('origem') === 'totem' ? 'consultas.totem' : 'consultas.index')
            ->with('success', 'Consulta cadastrada com sucesso.');
    }

    public function realizar(string $consulta)
    {
        $registro = DB::table('consultas_final')
            ->where('id', $consulta)
            ->firstOrFail();

        if ($registro->status === 'Aguardando') {
            DB::table('consultas_final')
                ->where('id', $consulta)
                ->where('status', 'Aguardando')
                ->update([
                    'status' => 'Realizado',
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'status' => DB::table('consultas_final')->where('id', $consulta)->value('status'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $consulta = DB::table('consultas_final')
            ->join('pacientes_final', 'consultas_final.paciente_id', '=', 'pacientes_final.id')
            ->where('consultas_final.id', $id)
            ->select(
                'consultas_final.id',
                'consultas_final.paciente_id',
                'pacientes_final.nome_completo as paciente_nome',
                'pacientes_final.cpf as paciente_cpf',
                'consultas_final.data_consulta',
                'consultas_final.horario_consulta',
                'consultas_final.sintomas_id',
                'consultas_final.possui_doencas_cronicas',
                'consultas_final.intensidade_id',
                'consultas_final.periodo_id',
                'consultas_final.prioridade'
            )
            ->firstOrFail();

        $consulta->sintomas = json_decode($consulta->sintomas_id, true, 512, JSON_THROW_ON_ERROR);

        return view('consultas.show', [
            'consulta' => $consulta,
            'sintomas' => SintomasModel::opcoes(),
            'intensidades' => IntensidadeModel::opcoes(),
            'periodos' => PeriodoModel::opcoes(),
        ]);
    }

    public function edit(string $consulta)
    {
        $consulta = DB::table('consultas_final')
            ->join('pacientes_final', 'consultas_final.paciente_id', '=', 'pacientes_final.id')
            ->where('consultas_final.id', $consulta)
            ->select(
                'consultas_final.id',
                'consultas_final.paciente_id',
                'pacientes_final.nome_completo as paciente_nome',
                'pacientes_final.cpf as paciente_cpf',
                'consultas_final.data_consulta',
                'consultas_final.horario_consulta',
                'consultas_final.sintomas_id',
                'consultas_final.possui_doencas_cronicas',
                'consultas_final.intensidade_id',
                'consultas_final.periodo_id',
                'consultas_final.prioridade'
            )
            ->firstOrFail();

        $consulta->sintomas = json_decode($consulta->sintomas_id, true, 512, JSON_THROW_ON_ERROR);

        return view('consultas.edit', [
            'consulta' => $consulta,
            'sintomas' => SintomasModel::opcoes(),
            'intensidades' => IntensidadeModel::opcoes(),
            'periodos' => PeriodoModel::opcoes(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $consulta)
    {
        $validated = $request->validate([
            'data_consulta' => ['required', 'date_format:Y-m-d'],
            'horario_consulta' => ['required', 'date_format:H:i'],
            'possui_doencas_cronicas' => ['required', 'integer', Rule::in([0, 1])],
            'sintomas' => ['required', 'array', 'min:1'],
            'sintomas.*' => ['required', 'integer', Rule::in(array_keys(SintomasModel::opcoes()))],
            'intensidade_id' => ['required', 'integer', Rule::in(array_keys(IntensidadeModel::opcoes()))],
            'periodo_id' => ['required', 'integer', Rule::in(array_keys(PeriodoModel::opcoes()))],
        ]);
        $prioridade = ConsultaPrioridade::calcular(
            $validated['sintomas'],
            $validated['intensidade_id'],
            $validated['possui_doencas_cronicas'],
            $validated['periodo_id']
        );

        DB::transaction(function () use ($consulta, $validated, $prioridade): void {
            $registro = DB::table('consultas_final')
                ->where('id', $consulta)
                ->firstOrFail();

            DB::table('consultas_final')
                ->where('id', $consulta)
                ->update([
                    'data_consulta' => $validated['data_consulta'],
                    'horario_consulta' => $validated['horario_consulta'].':00',
                    'sintomas_id' => json_encode($validated['sintomas'], JSON_THROW_ON_ERROR),
                    'possui_doencas_cronicas' => $validated['possui_doencas_cronicas'],
                    'intensidade_id' => $validated['intensidade_id'],
                    'periodo_id' => $validated['periodo_id'],
                    'prioridade' => $prioridade,
                    'updated_at' => now(),
                ]);

            $paciente = DB::table('consultas_final')
                ->where('paciente_id', $registro->paciente_id)
                ->selectRaw('COUNT(*) as numero_consultas, MAX(data_consulta) as data_ultima_consulta')
                ->first();

            DB::table('pacientes_final')
                ->where('id', $registro->paciente_id)
                ->update([
                    'numero_consultas' => $paciente->numero_consultas,
                    'data_ultima_consulta' => $paciente->data_ultima_consulta,
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('consultas.index')
            ->with('success', 'Consulta atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function () use ($id): void {
            $consulta = DB::table('consultas_final')
                ->where('id', $id)
                ->firstOrFail();

            DB::table('consultas_final')
                ->where('id', $id)
                ->delete();

            $paciente = DB::table('consultas_final')
                ->where('paciente_id', $consulta->paciente_id)
                ->selectRaw('COUNT(*) as numero_consultas, MAX(data_consulta) as data_ultima_consulta')
                ->first();

            DB::table('pacientes_final')
                ->where('id', $consulta->paciente_id)
                ->update([
                    'numero_consultas' => $paciente->numero_consultas,
                    'data_ultima_consulta' => $paciente->data_ultima_consulta,
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('consultas.index')
            ->with('success', 'Consulta excluída com sucesso.');
    }
}
