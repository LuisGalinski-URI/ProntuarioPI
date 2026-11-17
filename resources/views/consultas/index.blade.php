@extends('layouts.app')

@section('title', 'Consultas')

@section('content')

<table class="table table-striped w-100">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Nome</th>
      <th scope="col">Data</th>
      <th scope="col">Horário</th>
      <th scope="col">Prioridade</th>
      <th scope="col">Status</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($consultas as $consulta)
        <tr>
            <td>{{ $consulta->id }}</td>
            <td>{{ $consulta->paciente_nome }}</td>
            <td>{{ $consulta->data_consulta }}</td>
            <td>{{ $consulta->horario_consulta }}</td>
            <td>{{ $consulta->prioridade }} pts</td>
            <td>
                @if ($consulta->status === 'Aguardando')
                    <form action="{{ route('consultas.realizar', $consulta->id) }}"
                          method="POST"
                          data-consulta-status-form>
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-outline-warning" type="submit">Aguardando</button>
                        <span class="text-danger small" data-status-error role="alert"></span>
                    </form>
                @else
                    <span class="badge text-bg-success">{{ $consulta->status }}</span>
                @endif
            </td>
            <td>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('consultas.show', $consulta->id) }}">Ver</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('consultas.edit', $consulta->id) }}">Editar</a>
                <form class="d-inline" action="{{ route('consultas.destroy', $consulta->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
  </tbody>
</table>

{{ $consultas->links() }}

@endsection