@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="text-center py-5">
        <h1 class="h3 mb-3">Bem-vindo</h1>
        <p class="text-muted mb-4">Acesse o totem para iniciar um atendimento.</p>
        <a class="btn btn-primary" href="{{ route('consultas.totem') }}">Ir para o totem</a>
    </div>
@endsection
