<?php

namespace App\Http\Controllers;

use App\Models\PacienteModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = PacienteModel::query()
            ->select(
                'id',
                'nome_completo',
                'cpf',
                'telefone',
                'numero_consultas',
                'data_ultima_consulta'
            )
            ->orderBy('nome_completo')
            ->paginate(10);

        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pacientes.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paciente = PacienteModel::query()->findOrFail($id);

        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $paciente)
    {
        $paciente = PacienteModel::query()->findOrFail($paciente);

        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $paciente)
    {
        $paciente = PacienteModel::query()->findOrFail($paciente);

        $validated = $request->validate([
            'nome_completo' => ['required', 'string', 'max:255'],
            'cpf' => [
                'required',
                'string',
                'max:14',
                Rule::unique('pacientes_final', 'cpf')->ignore($paciente->id),
            ],
            'sexo_id' => ['required', 'integer', Rule::in([1, 2, 3])],
            'nacionalidade' => ['required', 'string', 'max:100'],
            'telefone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'cep' => ['required', 'string', 'max:9'],
            'logradouro' => ['required', 'string', 'max:255'],
            'numero_endereco' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'size:2'],
            'complemento_endereco' => ['nullable', 'string', 'max:255'],
            'doencas_cronicas' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $paciente->update($validated);

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PacienteModel::query()
            ->findOrFail($id)
            ->delete();

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente excluído com sucesso.');
    }
}
