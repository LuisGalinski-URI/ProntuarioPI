@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')

<table class="table table-striped w-100">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Nome</th>
      <th scope="col">CPF</th>
      <th scope="col">Telefone</th>
      <th scope="col">Consultas</th>
      <th scope="col">Última consulta</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($pacientes as $paciente)
        <tr>
            <td>{{ $paciente->id }}</td>
            <td>{{ $paciente->nome_completo }}</td>
            <td>{{ $paciente->cpf }}</td>
            <td>{{ $paciente->telefone }}</td>
            <td>{{ $paciente->numero_consultas }}</td>
            <td>{{ $paciente->data_ultima_consulta ?? '—' }}</td>
            <td>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('pacientes.show', $paciente->id) }}">Ver</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('pacientes.edit', $paciente->id) }}">Editar</a>
                <form class="d-inline" action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
  </tbody>
</table>

{{ $pacientes->links() }}

@endsection