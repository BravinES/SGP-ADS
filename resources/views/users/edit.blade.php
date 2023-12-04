@extends('adminlte::page')
@section('title', 'Editar Usuário')

@section('content_header')
    <h1><i class="fas fa-arrow-circle-left"></i> Editar Usuário</h1>
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Erro ao cadastar!</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post" class="form-horizontal">
        @method('PUT')
        @csrf
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Dados Pessoais</h3>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Nome Completo</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="{{ $user->name }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" value="{{ $user->email }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Nova senha</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Confirme a nova senha</label>
                    <div class="col-sm-10">
                        <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                            class="form-control @error('password') is-invalid @enderror" placeholder="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" name="sendNewUser" class="btn btn-success" value="Salvar">
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Permissões</h3>
            </div>

            <div class="card-body">

                @foreach ($user->userPermission as $permission)
                    {{ $permission->permission_id }}
                    {{ $permission->access_groups_id }}
                @endforeach

                {{ dd($user->permissionsGroup) }}


                <h3>Permissões</h3>
                <div class="form-group">

                    <ul class="list-unstyled">
                        @foreach ($permissionForUsers as $permission)
                            <li>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox"
                                        id="{{ 'permission-' . $permission['id'] }}" name="permissions[]"
                                        value="{{ $permission['id'] }}">
                                    <label for="{{ 'permission' . $permission['id'] }}" class="custom-control-label"
                                        onclick="handleCheck({{ $permission['id'] }})">
                                        {{ $permission['name'] }}
                                    </label>
                                </div>
                            </li>
                            @if (count($permission['sub']) > 0)
                                <ul class="list-unstyled ml-4 mb-3">
                                    @foreach ($permission['sub'] as $subPermission)
                                        <li>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox"
                                                    id="{{ 'permission-' . $permission['id'] . $subPermission['id'] }}"
                                                    name="permissions[]" value="{{ $subPermission['id'] }}"
                                                    data-check-id="{{ $permission['id'] }}">
                                                <label class="custom-control-label sub-checkbox"
                                                    for="{{ 'permission-' . $permission['id'] . $subPermission['id'] }}">
                                                    {{ $subPermission['name'] }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Custom Elements</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="customCheckbox1"
                                        value="option1">
                                    <label for="customCheckbox1" class="custom-control-label">Custom Checkbox</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="customCheckbox2" checked="">
                                    <label for="customCheckbox2" class="custom-control-label">Custom Checkbox
                                        checked</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="customCheckbox3" disabled="">
                                    <label for="customCheckbox3" class="custom-control-label">Custom Checkbox
                                        disabled</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox"
                                        id="customCheckbox4" checked="">
                                    <label for="customCheckbox4" class="custom-control-label">Custom Checkbox with custom
                                        color</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        class="custom-control-input custom-control-input-danger custom-control-input-outline"
                                        type="checkbox" id="customCheckbox5" checked="">
                                    <label for="customCheckbox5" class="custom-control-label">Custom Checkbox with custom
                                        color outline</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- radio -->
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="customRadio">
                                    <label for="customRadio1" class="custom-control-label">Custom Radio</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="customRadio"
                                        checked="">
                                    <label for="customRadio2" class="custom-control-label">Custom Radio checked</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio3" disabled="">
                                    <label for="customRadio3" class="custom-control-label">Custom Radio disabled</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input custom-control-input-danger" type="radio"
                                        id="customRadio4" name="customRadio2" checked="">
                                    <label for="customRadio4" class="custom-control-label">Custom Radio with custom
                                        color</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input
                                        class="custom-control-input custom-control-input-danger custom-control-input-outline"
                                        type="radio" id="customRadio5" name="customRadio2">
                                    <label for="customRadio5" class="custom-control-label">Custom Radio with custom color
                                        outline</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- select -->
                            <div class="form-group">
                                <label>Custom Select</label>
                                <select class="custom-select">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Custom Select Disabled</label>
                                <select class="custom-select" disabled="">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Select multiple-->
                            <div class="form-group">
                                <label>Custom Select Multiple</label>
                                <select multiple="" class="custom-select">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Custom Select Multiple Disabled</label>
                                <select multiple="" class="custom-select" disabled="">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                    <option>option 5</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">Toggle this custom switch
                                element</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3">
                            <label class="custom-control-label" for="customSwitch3">Toggle this custom switch element with
                                custom colors danger/success</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" disabled="" id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2">Disabled custom switch element</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customRange1">Custom range</label>
                        <input type="range" class="custom-range" id="customRange1">
                    </div>
                    <div class="form-group">
                        <label for="customRange2">Custom range (custom-range-danger)</label>
                        <input type="range" class="custom-range custom-range-danger" id="customRange2">
                    </div>
                    <div class="form-group">
                        <label for="customRange3">Custom range (custom-range-teal)</label>
                        <input type="range" class="custom-range custom-range-teal" id="customRange3">
                    </div>
                    <div class="form-group">
                        <!-- <label for="customFile">Custom File</label> -->

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group">
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>

    </form>
@endsection


@section('css')
    <style>
        label.custom-control-label.sub-checkbox {
            font-weight: 500;
        }

    </style>
@endsection


@section('js')
    <script>
        function handleCheck(id) {
            const elMaster = document.getElementById(`permission-${id}`)
            const elCheck = document.querySelectorAll(`[data-check-id='${id}']`)

            elMaster.checked = !elMaster.checked

            elCheck.forEach(item => {
                item.checked = elMaster.checked;

            })
        }

    </script>
@endsection
