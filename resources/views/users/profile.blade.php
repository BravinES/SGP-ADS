@extends('adminlte::page')
@section('title', 'Usuários')


@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Usuários</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('users.create')}}" class="btn btn-sm btn-success float-sm-right">Novo Usuario</a>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="card card-secondary" >
        <div class="card-header">
            <h3 class="card-title">Lista Geral</h3>

        </div>

        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Açoes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                <a href="{{route('users.edit', ['user' => $user->id])}}" class="btn btn-sm btn-info" >Editar</a>
                                @if($loggedId !== intval($user->id))
                                <form
                                    action="{{route('users.destroy', ['user' => $user->id])}}"
                                    method="post" class="d-inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @method('DELETE')
                                    @csrf()
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($users->total() > $users->perPage())
            <div class="card-footer clearfix">
                <div class="pagination m-0 float-right">
                    {{$users->links("pagination::bootstrap-4")}}
                </div>
            </div>
        @endif
    </div>

@endsection
