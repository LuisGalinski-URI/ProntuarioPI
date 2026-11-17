@extends('layouts.app')

@php($editing = $editing ?? false)
@php($readonly = $readonly ?? false)

@section('title', $readonly ? 'Visualizar paciente' : ($editing ? 'Editar paciente' : 'Novo paciente'))

@section('content')
    <div class="mb-4">
        <a class="text-decoration-none" href="{{ route('pacientes.index') }}">Pacientes</a>
        <h1 class="h3 mt-2 mb-1">{{ $readonly ? 'Visualizar paciente' : ($editing ? 'Editar paciente' : 'Novo paciente') }}</h1>
    </div>

    @unless ($readonly)
        <form action="{{ $editing ? route('pacientes.update', $paciente->id) : route('pacientes.store') }}" method="POST">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif
    @endunless

    <fieldset class="border-0 p-0 m-0" @disabled($readonly)>
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Dados pessoais</h2>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="nome_completo" class="form-label">Nome completo</label>
                        <input
                            type="text"
                            class="form-control @error('nome_completo') is-invalid @enderror"
                            id="nome_completo"
                            name="nome_completo"
                            value="{{ old('nome_completo', $paciente->nome_completo ?? '') }}"
                            maxlength="255"
                            required
                        >
                        @error('nome_completo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input
                            type="text"
                            class="form-control @error('cpf') is-invalid @enderror"
                            id="cpf"
                            name="cpf"
                            value="{{ old('cpf', $paciente->cpf ?? '') }}"
                            maxlength="14"
                            required
                        >
                        @error('cpf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="sexo_id" class="form-label">Sexo</label>
                        <select
                            class="form-select @error('sexo_id') is-invalid @enderror"
                            id="sexo_id"
                            name="sexo_id"
                            required
                        >
                            <option value="">Selecione</option>
                            <option value="1" @selected(old('sexo_id', $paciente->sexo_id ?? '') == 1)>Masculino</option>
                            <option value="2" @selected(old('sexo_id', $paciente->sexo_id ?? '') == 2)>Feminino</option>
                            <option value="3" @selected(old('sexo_id', $paciente->sexo_id ?? '') == 3)>Outro</option>
                        </select>
                        @error('sexo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="nacionalidade" class="form-label">Nacionalidade</label>
                        <input
                            type="text"
                            class="form-control @error('nacionalidade') is-invalid @enderror"
                            id="nacionalidade"
                            name="nacionalidade"
                            value="{{ old('nacionalidade', $paciente->nacionalidade ?? '') }}"
                            maxlength="100"
                            required
                        >
                        @error('nacionalidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="data_nascimento" class="form-label">Data de nascimento</label>
                        <input
                            type="date"
                            class="form-control @error('data_nascimento') is-invalid @enderror"
                            id="data_nascimento"
                            name="data_nascimento"
                            value="{{ old('data_nascimento', $paciente->data_nascimento ?? '') }}"
                            required
                        >
                        @error('data_nascimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Contato e endereço</h2>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input
                            type="tel"
                            class="form-control @error('telefone') is-invalid @enderror"
                            id="telefone"
                            name="telefone"
                            value="{{ old('telefone', $paciente->telefone ?? '') }}"
                            maxlength="20"
                            required
                        >
                        @error('telefone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email', $paciente->email ?? '') }}"
                            maxlength="255"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input
                            type="text"
                            class="form-control @error('cep') is-invalid @enderror"
                            id="cep"
                            name="cep"
                            value="{{ old('cep', $paciente->cep ?? '') }}"
                            maxlength="9"
                            required
                        >
                        @error('cep')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-7 mb-3">
                        <label for="logradouro" class="form-label">Logradouro</label>
                        <input
                            type="text"
                            class="form-control @error('logradouro') is-invalid @enderror"
                            id="logradouro"
                            name="logradouro"
                            value="{{ old('logradouro', $paciente->logradouro ?? '') }}"
                            maxlength="255"
                            required
                        >
                        @error('logradouro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="numero_endereco" class="form-label">Número</label>
                        <input
                            type="text"
                            class="form-control @error('numero_endereco') is-invalid @enderror"
                            id="numero_endereco"
                            name="numero_endereco"
                            value="{{ old('numero_endereco', $paciente->numero_endereco ?? '') }}"
                            maxlength="20"
                            required
                        >
                        @error('numero_endereco')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input
                            type="text"
                            class="form-control @error('bairro') is-invalid @enderror"
                            id="bairro"
                            name="bairro"
                            value="{{ old('bairro', $paciente->bairro ?? '') }}"
                            maxlength="255"
                            required
                        >
                        @error('bairro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input
                            type="text"
                            class="form-control @error('cidade') is-invalid @enderror"
                            id="cidade"
                            name="cidade"
                            value="{{ old('cidade', $paciente->cidade ?? '') }}"
                            maxlength="255"
                            required
                        >
                        @error('cidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="estado" class="form-label">Estado (UF)</label>
                        <input
                            type="text"
                            class="form-control @error('estado') is-invalid @enderror"
                            id="estado"
                            name="estado"
                            value="{{ old('estado', $paciente->estado ?? '') }}"
                            maxlength="2"
                            required
                        >
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="complemento_endereco" class="form-label">Complemento</label>
                        <input
                            type="text"
                            class="form-control @error('complemento_endereco') is-invalid @enderror"
                            id="complemento_endereco"
                            name="complemento_endereco"
                            value="{{ old('complemento_endereco', $paciente->complemento_endereco ?? '') }}"
                            maxlength="255"
                        >
                        @error('complemento_endereco')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Informações de saúde</h2>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label for="doencas_cronicas" class="form-label">Doenças crônicas</label>
                    <textarea
                        class="form-control @error('doencas_cronicas') is-invalid @enderror"
                        id="doencas_cronicas"
                        name="doencas_cronicas"
                        rows="3"
                    >{{ old('doencas_cronicas', $paciente->doencas_cronicas ?? '') }}</textarea>
                    @error('doencas_cronicas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea
                        class="form-control @error('observacoes') is-invalid @enderror"
                        id="observacoes"
                        name="observacoes"
                        rows="4"
                    >{{ old('observacoes', $paciente->observacoes ?? '') }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">{{ $readonly ? 'Voltar' : 'Cancelar' }}</a>
            @if ($readonly)
                <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-primary">Editar paciente</a>
            @else
                <button type="submit" class="btn btn-primary">{{ $editing ? 'Atualizar paciente' : 'Salvar paciente' }}</button>
            @endif
        </div>
    @unless ($readonly)
        </form>
    @endunless
@endsection
