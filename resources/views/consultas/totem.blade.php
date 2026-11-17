@extends('layouts.totem')

@section('title', 'Check-in de saúde')

@section('content')
    <div class="totem-page">
        <header class="totem-header">
            <a class="totem-brand" href="{{ route('consultas.totem') }}" aria-label="Prontuário, início">
                <span class="totem-brand-mark" aria-hidden="true">+</span>
                <span>
                    <strong>Prontuário</strong>
                    <small>Autoatendimento</small>
                </span>
            </a>
            <div class="totem-secure"><span aria-hidden="true">●</span> Atendimento seguro</div>
        </header>

        <main class="totem-main">
            <section class="totem-intro">
                <div>
                    <p class="totem-eyebrow">CHECK-IN DE SAÚDE</p>
                    <h1>Olá! Como podemos ajudar?</h1>
                    <p>Preencha seus dados para iniciar seu atendimento. Leva só alguns minutos.</p>
                </div>
                <div class="totem-time" aria-label="Tempo estimado: 2 minutos">
                    <span aria-hidden="true">◷</span>
                    <span><strong>2 min</strong><small>tempo estimado</small></span>
                </div>
            </section>

            @if (session('success'))
                <div class="totem-alert totem-alert--success" role="status">
                    <span class="totem-alert-icon" aria-hidden="true">✓</span>
                    <div>
                        <strong>Check-in realizado!</strong>
                        <p>{{ session('success') }} Nossa equipe já foi avisada. Aguarde ser chamado.</p>
                    </div>
                    <a class="totem-button totem-button--secondary" href="{{ route('consultas.totem') }}">Novo check-in</a>
                </div>
            @endif

            @if ($errors->any())
                <div class="totem-alert totem-alert--error" role="alert">
                    <strong>Confira os campos destacados e tente novamente.</strong>
                </div>
            @endif

            @unless (session('success'))
                <form class="totem-form" action="{{ route('consultas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="origem" value="totem">

                <section class="totem-card">
                    <div class="totem-card-heading">
                        <span class="totem-section-number">01</span>
                        <div>
                            <h2>Identificação</h2>
                            <p>Vamos localizar seu prontuário ou iniciar um novo.</p>
                        </div>
                    </div>

                    <div class="totem-field">
                        <label for="cpf">CPF</label>
                        <input
                            type="text"
                            class="form-control totem-input @error('cpf') is-invalid @enderror"
                            id="cpf"
                            name="cpf"
                            value="{{ old('cpf') }}"
                            maxlength="14"
                            autocomplete="off"
                            aria-describedby="cpf-help @error('cpf') cpf-error @enderror"
                            required
                        >
                        @error('cpf')
                            <div class="invalid-feedback" id="cpf-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <fieldset class="totem-field">
                        <legend>Sexo</legend>
                        <div class="totem-choice-row">
                            <label class="totem-choice">
                                <input type="radio" name="sexo_id" value="1" @checked(old('sexo_id') == 1) required>
                                <span class="totem-choice-icon" aria-hidden="true">♂</span>
                                <span>Masculino</span>
                            </label>
                            <label class="totem-choice">
                                <input type="radio" name="sexo_id" value="2" @checked(old('sexo_id') == 2) required>
                                <span class="totem-choice-icon" aria-hidden="true">♀</span>
                                <span>Feminino</span>
                            </label>
                        </div>
                        @error('sexo_id')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="totem-field">
                        <legend>Possui alguma doença crônica?</legend>
                        <div class="totem-choice-row">
                            <label class="totem-choice totem-choice--compact">
                                <input type="radio" name="possui_doencas_cronicas" value="1" @checked(old('possui_doencas_cronicas') === '1') required>
                                <span>Sim</span>
                            </label>
                            <label class="totem-choice totem-choice--compact">
                                <input type="radio" name="possui_doencas_cronicas" value="0" @checked(old('possui_doencas_cronicas') === '0') required>
                                <span>Não</span>
                            </label>
                        </div>
                        @error('possui_doencas_cronicas')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </section>

                <section class="totem-card">
                    <div class="totem-card-heading">
                        <span class="totem-section-number">02</span>
                        <div>
                            <h2>Como você está se sentindo?</h2>
                            <p>Selecione todos os sintomas que está sentindo agora.</p>
                        </div>
                    </div>

                    <fieldset class="totem-field">
                        <legend class="visually-hidden">Sintomas</legend>
                        <div class="totem-symptoms">
                            @foreach ($sintomas as $id => $sintoma)
                                <label class="totem-symptom">
                                    <input
                                        type="checkbox"
                                        name="sintomas[]"
                                        value="{{ $id }}"
                                        @checked(in_array($id, old('sintomas', [])))
                                    >
                                    <span>{{ $sintoma }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('sintomas')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @error('sintomas.*')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <div class="totem-details-grid">
                        <fieldset class="totem-field">
                            <legend>Qual a intensidade dos sintomas?</legend>
                            <div class="totem-levels">
                                @foreach ($intensidades as $id => $intensidade)
                                    <label class="totem-level">
                                        <input type="radio" name="intensidade_id" value="{{ $id }}" @checked(old('intensidade_id') == $id) required>
                                        <span class="totem-level-check" aria-hidden="true"></span>
                                        <span>{{ $intensidade }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('intensidade_id')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="totem-field">
                            <legend>Há quanto tempo está sentindo isso?</legend>
                            <div class="totem-levels">
                                @foreach ($periodos as $id => $periodo)
                                    <label class="totem-level">
                                        <input type="radio" name="periodo_id" value="{{ $id }}" @checked(old('periodo_id') == $id) required>
                                        <span class="totem-level-check" aria-hidden="true"></span>
                                        <span>{{ $periodo }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('periodo_id')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </section>

                <div class="totem-submit-area">
                    <button class="totem-button totem-button--primary" type="submit">
                        Finalizar check-in <span aria-hidden="true">→</span>
                    </button>
                </div>
                </form>
            @endunless

            <footer class="totem-footer">
            </footer>
        </main>
    </div>
@endsection
