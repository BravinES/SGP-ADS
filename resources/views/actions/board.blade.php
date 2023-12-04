@extends('adminlte::page')

@section('title', 'Gestão de Projetos')

@section('content')
    <div class="row">
        <div id="board-container">
            <div class="nav-main">
                <div class="title">
                    <div class="title-left">
                        <div> Painel de Ações</div>

                        <div class="painel-name">
                            <h1 class="board-header-btn">{{ $board->name }}</h1>
                        </div>

                    </div>
                    <div class="title-right">

                        <div class="input-group-prepend painel-name">
                            <button type="button" class="btn btn-default board-header-btn" data-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalTeamMembers">
                                    Membros do time
                                </a>

                                <!-- <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Informações do projeto</a> -->
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="board-content">
                <div class="lists">

                    @foreach ($board->boardListTask as $pkey => $boardList)
                        <div class="list">
                            <div id="boardListTitle">
                                <div class="list-title" onclick="actionsPanel.viewUpdateList(this)">
                                    <span class="list-title-text">{{ $boardList->title }}</span>
                                    <form id="formUpdate"
                                        action="{{ route('actions.list.update', ['id_board' => base64_encode($board->id)]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="text" class="list-title-text d-none" name="listTitleText"
                                            value="{{ $boardList->title }}">
                                        <input type="hidden" class="list-title-text d-none" name="listTitleId"
                                            value="{{ $boardList->id }}">
                                    </form>
                                </div>
                            </div>
                            <ul class="tasks-list">
                                @foreach ($boardList->listTasks as $task)
                                    <li class="task-item" data-toggle="modal" data-target="#modalTask"
                                        onclick="handleUpdateTask({{ intval($task->id) }})">
                                        <div class="task-header">
                                            <div class="cover-img">
                                                @if (!empty($task->cover))
                                                    <img src="{{ $task->cover }}" alt="{{ $task->title }}">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="task-body">
                                            <div class="task-labels">
                                                <div class="task-label bg-red">
                                                    URGENTE
                                                </div>
                                                <div class="task-label bg-blue">
                                                    Parte 01
                                                </div>
                                                <div class="task-label bg-cyan">
                                                    Parte 02
                                                </div>
                                                <div class="task-label bg-dark">
                                                    Parte 02
                                                </div>
                                            </div>
                                            <div class="task-content">
                                                <div class="task-title">
                                                    <span class="task-number">Nº {{ $task->id }} - </span>
                                                    <span class="task-text">{{ $task->title }}</span>
                                                </div>
                                                <div class="task-description">
                                                    <span class="task-text">
                                                        {{ strlen($task->description) < 148 ? $task->description : substr($task->description, 0, strrpos(substr($task->description, 0, 148), ' ')) . '...' }}

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-footer">
                                            <!--
                                                <div class="task-badges">
                                                    <div class="badge">
                                                        <i class="far fa-eye"></i>
                                                        <span class="badge-text"></span>
                                                    </div>
                                                    <div class="badge">
                                                        <i class="fas fa-paperclip"></i>
                                                        <span class="badge-text">1</span>
                                                    </div>
                                                    <div class="badge">
                                                        <i class="far fa-comments"></i>
                                                        <span class="badge-text">2</span>
                                                    </div>
                                                    <div class="badge">
                                                        <i class="fas fa-code-branch"></i>
                                                        <span class="badge-text">8</span>
                                                    </div>
                                                </div>
                                                <div class="task-members">
                                                    <div class="member">
                                                        <img class="img-circle"
                                                            src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg"
                                                            alt="User Image">
                                                    </div>
                                                    <div class="member">
                                                        <img class="img-circle"
                                                            src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg"
                                                            alt="User Image">
                                                    </div>
                                                </div>
                                            -->
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <footer>
                                <button id="addNewTask" type="button" class="btn" data-toggle="modal"
                                    data-target="#modalTask"
                                    onclick="handleAddTask({{ $boardList->id }}, '{{ route('task.store', ['id_board' => base64_encode($board->id)]) }}')">
                                    <i class="fas fa-plus"></i>
                                    Adicionar um tarefa
                                </button>
                            </footer>
                        </div>
                    @endforeach

                    <div class="list new-list" id="newList">
                        <div id="boardListTitle">
                            <div class="list-title" id="listTitle" onclick="actionsPanel.showListInput()">
                                <i class="fas fa-plus"></i>
                                <span class="list-title-text">Adicionar Etapa</span>
                            </div>

                            <div class="form-new-list d-none">
                                <form id="newListForm"
                                    action="{{ route('actions.list.store', ['id_board' => base64_encode($board->id)]) }}"
                                    method="post">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="titleNewList" name="titleNewList"
                                                placeholder="Nova estapa">
                                        </div>

                                        <!-- <div class="form-group">
                                            <input type="radio" class="form-control" name="type" id="tasksNotStarted"
                                                placeholder="Título" value="1" />
                                            <label for="taskNoStart">Tarefas não iniciadas</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" class="form-control" name="type" id="completedTasks"
                                                placeholder="Título" value="2" />
                                            <label for="taskNoStart">Tarefas Concluídas</label>
                                        </div> -->

                                    </div>
                                    <div class="card-footer">
                                        <div class="close">
                                            <button type="submit" class="btn btn-primary">Adicionar etapa</button>
                                            <a id="closeNewList"><i class="fas fa-times"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTask" tabindex="-1" role="dialog" aria-labelledby="modalTaskLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form action="{{ route('task.store', ['id_board' => base64_encode($board->id)]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                            <div class="form-row">
                                <div class="col-8 aside-left">
                                    <div class="input-group input-task">
                                        <label for="taskTitle">Título</label>
                                        <input type="text" class="form-control" name="taskTitle" id="taskTitle"
                                            placeholder="Título">
                                    </div>

                                    <div class="input-group input-task">
                                        <label for="taskList">Criar na lista</label>
                                        <select class="form-control" name="taskList" id="taskList">
                                            @foreach ($board->boardListTask as $pkey => $boardList)
                                                <option value={{ $boardList->id }}>{{ $boardList->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input-group input-task">
                                        <label for="taskTag">Etiquetas</label>
                                    </div>

                                    <div class="input-group input-task">
                                        <label for="taskTitle">Descrição</label>
                                        <textarea type="text" class="form-control" name="taskDescription"
                                            id="taskDescription" rows="10"></textarea>
                                    </div>

                                    <!-- <div class="progress mb-3 task-progress">
                                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="40"
                                                    aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                    <span class="sr-only">40% Complete (success)</span>
                                                </div>
                                            </div> -->

                                    <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                <!-- <li class="nav-item">
                                                            <a class="nav-link active" id="custom-tabs-four-home-tab"
                                                                data-toggle="pill" href="#custom-tabs-four-home" role="tab"
                                                                aria-controls="custom-tabs-four-home"
                                                                aria-selected="true">Tarefas</a>
                                                        </li> -->
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-profile-tab"
                                                        data-toggle="pill" href="#custom-tabs-four-profile" role="tab"
                                                        aria-controls="custom-tabs-four-profile"
                                                        aria-selected="false">Comentários</a>
                                                </li>
                                                <!--
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="custom-tabs-four-messages-tab"
                                                                data-toggle="pill" href="#custom-tabs-four-messages" role="tab"
                                                                aria-controls="custom-tabs-four-messages"
                                                                aria-selected="false">Observações</a> -->
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                                <!-- <div class="tab-pane fade show active" id="custom-tabs-four-home"
                                                            role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                            Adcionar nova tarefa
                                                        </div> -->
                                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                                    aria-labelledby="custom-tabs-four-profile-tab">
                                                    <div id="commentsList"></div>
                                                </div>
                                                <!--
                                                        <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                                                            aria-labelledby="custom-tabs-four-messages-tab">
                                                            Adcionar uma observação
                                                        </div> -->
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>

                                </div>

                                <div class="col-4 aside-right">

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default" data-toggle="dropdown"
                                            aria-expanded="false">Datas</button>
                                        <div class="dropdown-menu" role="menu" style="">
                                            <div class="dropdown-item" href="#">
                                                <label for="">Data inicio</label>
                                                <input type="date" class="form-control" name="startDate" id="startDate"
                                                    placeholder="Data inicio">
                                            </div>
                                            <div class="dropdown-item" href="#">
                                                <label for="">Data fim</label>
                                                <input type="date" class="form-control" name="endDate" id="endDate"
                                                    placeholder="Data fim">
                                            </div>

                                        </div>
                                    </div>
                                    <!--
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default" data-toggle="dropdown"
                                                    aria-expanded="false">Atribuir tarefa
                                                </button>
                                                <div class="dropdown-menu" role="menu" style="">
                                                    <a class="dropdown-item" href="#">lista de usuarios</a>
                                                </div>
                                            </div>
                                        -->

                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Etiquetas</h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="nav nav-pills flex-column">
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="far fa-circle text-danger"></i>
                                                        URGENTE
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="far fa-circle text-warning"></i> Importante
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="far fa-circle text-primary"></i>
                                                        Aguardando
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!--
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="taskCover" id="taskCover">
                                                <label class="custom-file-label" for="exampleInputFile">Imagem...</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Upload</span>
                                            </div>
                                        </div>
                                        -->

                                    <div class="form-group">
                                        <label>Status da tarefa</label>
                                        <select class="form-control" id="taskStatus" name="taskStatus">
                                            <option value="0">Não iniciado</option>
                                            <option value="1">Em andamento</option>
                                            <option value="2">Atrasada</option>
                                            <option value="3">Concluída</option>
                                            <option value="-1">Cancelada</option>
                                        </select>
                                    </div>

                                    <!--
                                        <div class="form-group">
                                            <label>Mover para</label>
                                            <select class="form-control">
                                                <option>option 1</option>
                                                <option>option 2</option>
                                                <option>option 3</option>
                                                <option>option 4</option>
                                                <option>option 5</option>
                                            </select>
                                        </div>
                                        -->
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-7">
                                    <button type="submit" class="btn btn-primary float-right">Concluir</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <!-- Modal Membros do time -->
        <div class="modal fade" id="modalTeamMembers" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Membros do time</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- list user member -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Membros do Projeto</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Progresso</th>
                                            <th style="width: 40px"></th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="usersMembersList">
                                        @foreach ($memberBoards as $member)
                                            <tr>
                                                <td>{{ $member['name'] }}</td>
                                                <td>{{ $member['email'] }}</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar progress-bar-danger" style="width: 55%">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-danger">55%</span></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-block btn-sm"
                                                        onclick="handleDeleteBoardMember({{ $member['id'] }})">
                                                        <i class="fa fa-trash"></i> Exluir
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- ./list user member -->

                        <!-- list All users -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Membros do Projeto</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="usersNotMembersList">
                                        @foreach ($allUsers as $user)
                                            <tr>
                                                <td>{{ $user['name'] }}</td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-block btn-sm"
                                                        onclick="handleAddBoardMember({{ $board->id }} ,{{ $user['id'] }})">
                                                        <i class="fa fa-plus"></i>
                                                        Adicionar
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- ./list All users-->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /Modal Membros do time -->

    </div>




@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::to('/assets/css/actions_panel/main.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        moment.locale('pt-br');

        // AJAX
        const isAjax = (method, url, data) => {
            const promise = new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open(method, url);

                xhr.responseType = 'json';

                if (data) {
                    xhr.setRequestHeader('Content-Type', 'application/json');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                }

                xhr.onload = () => {
                    if (xhr.status >= 400) {
                        reject(xhr.response);
                    } else {
                        resolve(xhr.response);
                    }
                };

                xhr.onerror = () => {
                    reject('Something went wrong!');
                };

                xhr.send(JSON.stringify(data));
            });
            return promise;
        };

        const getData = () => {
            isAjax('GET', 'https://reqres.in/api/users').then(responseData => {
                console.log(responseData);
            });
        };

        const sendData = () => {
            isAjax('POST', 'https://reqres.in/api/register', {
                    email: 'eve.holt@reqres.in'
                    // password: 'pistol'
                })
                .then(responseData => {
                    console.log(responseData);
                })
                .catch(err => {
                    console.log(err);
                });
        };

        const loggedUser = {!! json_encode(auth()->user()) !!}
        const board = {!! json_encode($board) !!};
        const allListsTasks = board.board_list_task
        const allTasks = []

        allListsTasks.forEach(function(list) {
            allTasks.push(...list.list_tasks);
        })

        const actionsPanel = {
            state: {},

            el: {
                newListForm: document.querySelector("#newListForm"),
                titleNewList: document.querySelector("#titleNewList"),
            },

            showListInput() {
                const closeNewList = document.getElementById('closeNewList');
                const listTitle = document.querySelector('#newList .list-title');
                const formNewList = document.querySelector('#newList .form-new-list');
                const titleNewList = document.querySelector('#titleNewList');

                listTitle.classList.add('d-none');
                formNewList.classList.remove('d-none');

                titleNewList.focus();

                closeNewList.addEventListener('click', () => {
                    listTitle.classList.remove('d-none');
                    formNewList.classList.add('d-none');
                })
            },

            newList(event) {
                event.preventDefault();
            },

            viewUpdateList(el) {
                const listTitle = el;
                const listTitleText = listTitle.querySelector('.list-title-text');
                const inputListTitle = listTitle.querySelector('input[type=text].list-title-text');
                const formUpdate = listTitle.querySelector('#formUpdate');

                listTitleText.classList.add('d-none');
                inputListTitle.classList.remove('d-none');

                actionsPanel.state.inputListTitle = inputListTitle.value

                inputListTitle.focus();
                const strLength = inputListTitle.value.length * 2;
                inputListTitle.setSelectionRange(0, strLength);

                inputListTitle.addEventListener('blur', () => {
                    if (actionsPanel.state.inputListTitle === inputListTitle.value) {
                        listTitleText.classList.remove('d-none');
                        inputListTitle.classList.add('d-none');
                        return false;
                    }
                    listTitleText.innerText = inputListTitle.value;

                    formUpdate.submit();
                })
            }
        }

        function handleAddTask(id, route) {
            document.querySelector("#modalTask form").reset();
            document.querySelector("#modalTask form").action = route;
            document.querySelector(`#taskList option[value="${id}"]`).selected = true;
            document.querySelector('#modalTask form input[type=hidden][name=_method]').value = 'POST';
            document.querySelector('#modalTask form button[type=submit]').value = 'Concluir';
            document.querySelector('#modalTask #commentsList').innerHTML = '';
        }

        function handleUpdateTask(taskId) {
            const route = "{{ route('task.update', ['id_task' => ':taskId']) }}".replace(':taskId', taskId);
            const task = allTasks.find(task => task.id === taskId);

            const modalTask = {
                el: document.querySelector("#modalTask"),
                title: document.querySelector('#modalTask #taskTitle'),
                list: document.querySelector('#modalTask #taskList'),
                description: document.querySelector('#modalTask #taskDescription'),
                startDate: document.querySelector('#modalTask #startDate'),
                endDate: document.querySelector('#modalTask #endDate'),
                form: document.querySelector('#modalTask form'),
                FormMethod: document.querySelector('#modalTask form input[type=hidden][name=_method]'),
                button: document.querySelector('#modalTask form button[type=submit]'),
                taskStatus: document.querySelector('#modalTask #taskStatus'),
            }

            modalTask.title.value = task.title;
            modalTask.list.querySelector(`option[value="${task.list_actual_id}"]`).selected = true;
            modalTask.taskStatus.querySelector(`option[value="${task.status}"]`).selected = true;
            modalTask.description.value = task.description;
            modalTask.startDate.value = task.date_start;
            modalTask.endDate.value = task.date_end;

            modalTask.form.action = route;
            modalTask.FormMethod.value = 'PUT';
            modalTask.button.innerText = 'Atualizar';

            htmlComponents.comments(taskId, {{ $board->id }});
        }

        function handleIsPrivate(isprivete) {
            const elAreaMembers = document.querySelector('#modalTask div#areaUserCommentPrivate');
            if (parseInt(isprivete) === 1) {
                elAreaMembers.classList.remove('d-none');
            } else {
                elAreaMembers.classList.add('d-none');
            }
        }


        function handleReplyComment(commentId) {
            $allElReplyComment = document.querySelectorAll('.reply-comment');
            $allElReplyComment.forEach(elReplyComment => {
                elReplyComment.classList.add('d-none');
            });

            document.querySelector(`#replyCommentForm_${commentId}`).classList.remove('d-none');

            $allElReplyCommentOption = document.querySelectorAll('.reply-comment-option');
            $allElReplyCommentOption.forEach(elReplyCommentOption => {
                elReplyCommentOption.classList.remove('d-none');
            });

            document.querySelector(`#replyCommentOption_${commentId}`).classList.add('d-none');

        }

        function handleAddComments(taskId, boardId) {

            const route = "{{ route('comment.store', ['id_task' => ':taskId']) }}".replace(':taskId', taskId);

            const selectedUsers = [];

            for (var option of document.querySelector('#modalTask select#userCommentPrivate')
                    .options) {
                if (option.selected) {
                    selectedUsers.push(option.value);
                }
            }

            isAjax('POST', route, {
                    taskId,
                    boardId,
                    comment: document.querySelector('#modalTask textarea#commentDescription').value,
                    private: document.querySelector('#modalTask select#commentIsPrivate').value,
                    user: selectedUsers,
                    _token: '{{ csrf_token() }}',
                })
                .then(responseData => {
                    if (responseData.success)
                        htmlComponents.comments(responseData.taskId, responseData.boardId);
                })
                .catch(err => {
                    console.log(err);
                });
        }

        function handleAddSubComments(taskId, boardId, parentId) {
            const route = "{{ route('comment.store', ['id_task' => ':taskId']) }}".replace(':taskId', taskId);

            isAjax('POST', route, {
                    taskId,
                    boardId,
                    parent_id: parentId,
                    comment: document.querySelector(`#replyCommentText_${parentId}`).value,
                    _token: '{{ csrf_token() }}',
                })
                .then(responseData => {
                    if (responseData.success)
                        htmlComponents.comments(responseData.taskId, responseData.boardId);
                })
                .catch(err => {
                    console.log(err);
                });
        }

        actionsPanel.el.newListForm.addEventListener("submit", (event) => {
            !titleNewList.value && event.preventDefault();
        })

        //html compomntes
        const htmlComponents = {
            listMembers(boradId) {
                const members = document.querySelector('#usersMembersList');
                members.innerHTML = "";

                const users = document.querySelector('#usersNotMembersList');
                users.innerHTML = "";

                const htmlMembers = (member) => /*html*/ `<tr>
                    <td>${member.name}</td>
                    <td>${member.email}</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width: 55%">
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-danger">55%</span></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-block btn-sm"
                            onclick="handleDeleteBoardMember(${member.id})">
                            <i class="fa fa-trash"></i> Exluir
                        </button>
                    </td>
                </tr>`

                const htmlUsers = (user) => /*html*/ `<tr>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-block btn-sm"
                            onclick="handleAddBoardMember(${boradId}, ${user.id})">
                            <i class="fa fa-plus"></i>
                            Adicionar
                        </button>
                    </td>
                </tr>`

                const routeMembers = "{{ route('board.member.show', ['board_id' => ':boardId']) }}".replace(
                    ':boardId', boradId);

                isAjax('GET', routeMembers, {
                    _token: '{{ csrf_token() }}'
                }).then(responseData => {

                    responseData.memberBoards.forEach(member => {
                        members.innerHTML += htmlMembers(member);
                    });

                    responseData.allUsers.forEach(user => {
                        users.innerHTML += htmlUsers(user);
                    });


                });
            },

            comments(taskId, boradId) {
                const commentsList = document.querySelector('#commentsList');
                commentsList.innerHTML = "";

                const htmlCommentsAttachments = (attachments) => {

                    if (attachments.length === 0) {
                        return '';
                    }

                    return /*html*/ `<div class="attachment-block clearfix">
                        <div class="attachment-pushed">
                            <h4 class="attachment-heading">Anexos</h4>
                            <a href="https://www.lipsum.com/">Lorem ipsum text generator</a>
                        </div>
                    </div>`
                }

                const htmlSubComments = (subComments) => {
                    if (subComments.length === 0) {
                        return ''
                    }

                    let htmlSubComments = '';

                    subComments.forEach(subComment => {
                        //Verifica a foto do perfil
                        subComment.photo = subComment.photo ||
                            'https://iupac.org/wp-content/uploads/2018/05/default-avatar.png';

                        //Verifica tempo do comentário
                        moment.locale('pt-br');
                        const date = moment(subComment.created_at);
                        subComment.dataDiff = date.fromNow()


                        htmlSubComments += /*html*/ `
                            <div class="card-comment">
                                <!-- User image -->
                                <img class="img-circle img-sm" src="${subComment.photo}" alt="User Image">

                                <div class="comment-text">
                                    <span class="username">
                                        ${subComment.name}
                                        <span class="text-muted float-right">publicado em - ${ (new Date(subComment.created_at).toLocaleDateString('pt-BR', {timeZone: 'UTC'})) } - ${subComment.dataDiff}</span>
                                    </span>
                                    ${subComment.comment}
                                </div>
                            </div>`
                    })



                    return /*html*/ `<div class="card-footer card-comments" style="display: block;"> ${htmlSubComments} </div>`;
                }

                const htmlComments = (comment) => /*html*/ `<div class="card card-widget">
                    <div class="card-header">
                        <div class="user-block">
                            <img class="img-circle" src="${comment.photo}" alt="User Image">
                            <span class="username"><a href="#">${comment.name}</a></span>
                            <span class="description"> Comentário ${comment.private === 0 ? ' público' : 'privado' },  publicado em - ${ (new Date(comment.created_at).toLocaleDateString('pt-BR', {timeZone: 'UTC'})) } - ${comment.dataDiff}</span>
                        </div>
                        <!-- /.user-block -->
                        <div class="card-tools">

                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <!-- post text -->
                        ${comment.comment}

                        <!-- Attachment -->
                        ${htmlCommentsAttachments(comment.attachments)}
                        <!-- /.attachment-block -->

                    </div>
                    <!-- /.card-body -->
                    ${htmlSubComments(comment.subComments)}

                    <!-- /.card-footer -->
                    <div class="card-footer" style="display: block;">
                        <div id="replyCommentForm_${comment.id}" class="reply-comment d-none">
                            <img class="img-fluid img-circle img-sm" src="https://iupac.org/wp-content/uploads/2018/05/default-avatar.png" alt="Alt Text"/>
                            <div class="img-push">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control rounded-0" id="replyCommentText_${comment.id}"  placeholder="Press enter to post comment">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-info btn-flat" onclick="handleAddSubComments(${taskId}, {{ $board->id }}, ${comment.id})">Comentar</button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="replyCommentOption_${comment.id}" class="reply-comment-option">
                            <div class="img-push">
                                <div class="input-group mb-3">
                                    <button type="button" class="btn btn-info btn-flat" onclick="handleReplyComment(${comment.id})">Responder</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-footer -->
                </div>`


                const htmlNewComments = (taskId, listBoardMembers) => {

                    let optionsMembers = '';

                    listBoardMembers.forEach(member => {
                        optionsMembers += /*html*/
                            `<option value="${member.user_id}">${member.name}</option>`
                    })

                    return /*html*/ `<div>
                        <img class="img-fluid img-circle img-sm" src="https://iupac.org/wp-content/uploads/2018/05/default-avatar.png" alt="Alt Text">
                        <!-- .img-push is used to add margin to elements next to floating images -->
                        <div class="img-push">
                            <div class="input-group mb-3">
                                <textarea type="text" class="form-control rounded-0" id="commentDescription" name="commentDescription"></textarea>
                            </div>
                            <div class="input-group mb-3" >
                                <select class="custom-select col-4"  name="commentIsPrivate" id="commentIsPrivate" onchange="handleIsPrivate(this.value)">
                                    <option value="0" selected >Público</option>
                                    <option value="1">Privado</option>
                                </select>

                                <div class="col-8">
                                    <div class="d-none" id="areaUserCommentPrivate" style="width: 100%;">
                                        <select
                                            style="width: 100%;"
                                            name="userCommentPrivate[]"
                                            id="userCommentPrivate" multiple>
                                            ${optionsMembers}
                                        </select>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    style="max-height: 40px;"
                                    class="btn btn-info"
                                    onclick="handleAddComments(${taskId}, {{ $board->id }})">Comentar</button>
                            </div>
                        </div>
                    </div>`
                }

                const routeComments = "{{ route('comments.show', ['task_id' => ':taskId']) }}".replace(
                    ':taskId', taskId);
                isAjax('GET', routeComments).then(responseData => {

                    console.log(responseData);

                    responseData.comment.forEach(comment => {

                        //Verifica a foto do perfil
                        comment.photo = comment.photo !== null ? comment.photo :
                            'https://iupac.org/wp-content/uploads/2018/05/default-avatar.png';

                        //Ferifica os anexos do comentário
                        comment.attachments = []

                        //Verifica tempo do comentário
                        moment.locale('pt-br');
                        const date = moment(comment.created_at);
                        comment.dataDiff = date.fromNow();

                        commentsList.innerHTML += htmlComments(comment);
                    });

                    commentsList.innerHTML += htmlNewComments(taskId, responseData.memberBoards);
                    $('#userCommentPrivate').select2({
                        theme: "bootstrap-5"
                    });
                });
            }

        }
        // Membros do projeto




        function handleAddBoardMember(boradId, userId) {

            isAjax('POST', "{{ route('board.member.store') }}", {
                    boradId,
                    userId
                })
                .then(responseData => {
                    console.log(responseData);
                })
                .catch(err => {
                    console.log(err);
                });

            htmlComponents.listMembers({{ $board->id }});

        }

        function handleDeleteBoardMember(userId) {

            const router = "{{ route('board.member.delete', ['id' => ':userId']) }}".replace(':userId', userId);

            isAjax('POST', router, {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                })
                .then(responseData => {
                    console.log(responseData);
                })
                .catch(err => {
                    console.log(err);
                });

            htmlComponents.listMembers({{ $board->id }});
        }
    </script>
@endsection
