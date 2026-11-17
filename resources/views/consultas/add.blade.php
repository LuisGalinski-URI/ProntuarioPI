@extends('layouts.app')
@section('title', 'Nova consulta')

@section('content')

    <form action="{{ route('consultas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" maxlength="14" required>
        </div>

        <div class="form-group">
            <label for="possui_doencas_cronicas">Possui doenças crônicas?</label>
            <select class="form-select @error('possui_doencas_cronicas') is-invalid @enderror" id="possui_doencas_cronicas" name="possui_doencas_cronicas" required>
                <option value="">Selecione</option>
                <option value="1" @selected(old('possui_doencas_cronicas') === '1')>Sim</option>
                <option value="0" @selected(old('possui_doencas_cronicas') === '0')>Não</option>
            </select>
            @error('possui_doencas_cronicas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <fieldset class="form-group">
            <legend class="col-form-label">Sexo</legend>
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" id="sexo_masculino" name="sexo_id" value="1" @checked(old('sexo_id') == 1) required>
                <label class="form-check-label" for="sexo_masculino">Masculino</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" id="sexo_feminino" name="sexo_id" value="2" @checked(old('sexo_id') == 2) required>
                <label class="form-check-label" for="sexo_feminino">Feminino</label>
            </div>
        </fieldset>

        <fieldset class="form-group">
            <legend class="col-form-label">Sintomas</legend>
            <div class="sintomas-grid">
                @foreach ($sintomas as $id => $sintoma)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('sintomas') is-invalid @enderror" id="sintoma_{{ $id }}" name="sintomas[]" value="{{ $id }}" @checked(in_array($id, old('sintomas', [])))>
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

        <div class="form-group">
            <label for="intensidade_id">Intensidade</label>
            <select class="form-select @error('intensidade_id') is-invalid @enderror" id="intensidade_id" name="intensidade_id" required>
                <option value="">Selecione a intensidade</option>
                @foreach ($intensidades as $id => $intensidade)
                    <option value="{{ $id }}" @selected(old('intensidade_id') == $id)>{{ $intensidade }}</option>
                @endforeach
            </select>
            @error('intensidade_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="periodo_id">Período</label>
            <select class="form-select @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id" required>
                <option value="">Selecione o período</option>
                @foreach ($periodos as $id => $periodo)
                    <option value="{{ $id }}" @selected(old('periodo_id') == $id)>{{ $periodo }}</option>
                @endforeach
            </select>
            @error('periodo_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

@endsection
