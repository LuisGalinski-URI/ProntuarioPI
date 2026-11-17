@extends('layouts.app')

@php($readonly = $readonly ?? false)

@section('title', $readonly ? 'Visualizar consulta' : 'Editar consulta')

@section('content')
    <div class="mb-4">
        <a class="text-decoration-none" href="{{ route('consultas.index') }}">Consultas</a>
        <h1 class="h3 mt-2 mb-1">{{ $readonly ? 'Visualizar consulta' : 'Editar consulta' }}</h1>
    </div>

    @unless ($readonly)
        <form action="{{ route('consultas.update', $consulta->id) }}" method="POST">
            @csrf
            @method('PUT')
    @endunless

    <fieldset class="border-0 p-0 m-0" @disabled($readonly)>
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Paciente</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="paciente_nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="paciente_nome" value="{{ $consulta->paciente_nome ?? 'Paciente sem nome cadastrado' }}" @if ($readonly) disabled @else readonly @endif>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="paciente_cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="paciente_cpf" value="{{ $consulta->paciente_cpf }}" @if ($readonly) disabled @else readonly @endif>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Dados da consulta</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="form-label d-block">Prioridade calculada</span>
                    <strong>{{ $consulta->prioridade }} pts</strong>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="data_consulta" class="form-label">Data</label>
                        <input
                            type="date"
                            class="form-control @error('data_consulta') is-invalid @enderror"
                            id="data_consulta"
                            name="data_consulta"
                            value="{{ old('data_consulta', $consulta->data_consulta) }}"
                            required
                        >
                        @error('data_consulta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="horario_consulta" class="form-label">Horário</label>
                        <input
                            type="time"
                            class="form-control @error('horario_consulta') is-invalid @enderror"
                            id="horario_consulta"
                            name="horario_consulta"
                            value="{{ old('horario_consulta', substr($consulta->horario_consulta, 0, 5)) }}"
                            required
                        >
                        @error('horario_consulta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="possui_doencas_cronicas" class="form-label">Possui doenças crônicas?</label>
                    <select class="form-select @error('possui_doencas_cronicas') is-invalid @enderror" id="possui_doencas_cronicas" name="possui_doencas_cronicas" required>
                        <option value="">{{ $readonly ? 'Não informado' : 'Selecione' }}</option>
                        <option value="1" @selected(old('possui_doencas_cronicas', $consulta->possui_doencas_cronicas) == 1)>Sim</option>
                        <option value="0" @selected(old('possui_doencas_cronicas', $consulta->possui_doencas_cronicas) === '0' || old('possui_doencas_cronicas', $consulta->possui_doencas_cronicas) === 0)>Não</option>
                    </select>
                    @error('possui_doencas_cronicas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <fieldset class="form-group mb-3">
                    <legend class="col-form-label">Sintomas</legend>
                    <div class="sintomas-grid">
                        @foreach ($sintomas as $id => $sintoma)
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input @error('sintomas') is-invalid @enderror"
                                    id="sintoma_{{ $id }}"
                                    name="sintomas[]"
                                    value="{{ $id }}"
                                    @checked(in_array($id, old('sintomas', $consulta->sintomas)))
                                >
                                <label class="form-check-label" for="sintoma_{{ $id }}">{{ $sintoma }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('sintomas')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @error('sintomas.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="mb-3">
                    <label for="intensidade_id" class="form-label">Intensidade</label>
                    <select class="form-select @error('intensidade_id') is-invalid @enderror" id="intensidade_id" name="intensidade_id" required>
                        <option value="">Selecione a intensidade</option>
                        @foreach ($intensidades as $id => $intensidade)
                            <option value="{{ $id }}" @selected(old('intensidade_id', $consulta->intensidade_id) == $id)>{{ $intensidade }}</option>
                        @endforeach
                    </select>
                    @error('intensidade_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="periodo_id" class="form-label">Período</label>
                    <select class="form-select @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id" required>
                        <option value="">Selecione o período</option>
                        @foreach ($periodos as $id => $periodo)
                            <option value="{{ $id }}" @selected(old('periodo_id', $consulta->periodo_id) == $id)>{{ $periodo }}</option>
                        @endforeach
                    </select>
                    @error('periodo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('consultas.index') }}" class="btn btn-secondary">{{ $readonly ? 'Voltar' : 'Cancelar' }}</a>
            @if ($readonly)
                <a href="{{ route('consultas.edit', $consulta->id) }}" class="btn btn-primary">Editar consulta</a>
            @else
                <button type="submit" class="btn btn-primary">Atualizar consulta</button>
            @endif
        </div>
    @unless ($readonly)
        </form>
    @endunless
@endsection
