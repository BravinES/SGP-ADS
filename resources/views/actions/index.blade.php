@extends('adminlte::page')

@section('title', 'Painel de Ações')

@section('content')
    <div class="row">
        <div id="board-container">
            <div class="nav-main">
                <div class="title">Painel de Ações</div>
            </div>

            <div class="board-content list-boards">
                <div class="boards">
                    <div class="boards-title">
                        <i class="far fa-clipboard"></i>
                        <h3>SEUS PROJETOS</h3>
                    </div>
                    <div class="boards-content">
                        <ul class="boards-list">
                            <li class="board-item card-empty">
                                <button type="button" class="btn" data-toggle="modal"
                                    data-target="#modalNewProject">
                                    <i class="fas fa-plus icon-hover"></i>
                                    <div class="card-empty-text">
                                        <i class="fas fa-plus"></i>
                                        <span> Criar novo projeto</span>
                                    </div>
                                </button>
                            </li>

                            @foreach ($boards as $board)

                                <li class="board-item-content">

                                    @if (intval($board['totalNotifications']) > 0 && 1 === 2)
                                        <div class="ball-notification" style="background-color: #F00">
                                            <span>{{ $board['totalNotifications'] }}</span>
                                        </div>
                                    @endif

                                    <div class="board-item" style="border-color:{{ $board['statusColor'] }}">

                                        <div class="bg-full-percent {{ $actionsConfig['c_board_view_percent_type'] === 2 ? $board['color'] : '' }}"
                                            style="width: {{ $board['percent'] }}%"></div>

                                        <a href="{{ route('actions.board', ['id_board' => base64_encode($board['id'])]) }}"
                                            class="project-card">
                                            <div class="project-card-heard">

                                                @if ($actionsConfig['c_board_view_project_color'] === 1)
                                                    <div class="project-color {{ $board['color'] }}"></div>
                                                @endif

                                                <div class="project-card-title">
                                                    <span class="title">{{ $board['name'] }}</span>
                                                </div>
                                                <div class="project-info"></div>
                                            </div>
                                            <div class="project-card-body">
                                                <div class="project-deadline">
                                                    <span>{{ date('d/m/Y', strtotime($board['date_start'])) }}</span>
                                                    <span>à</span>
                                                    <span>{{ date('d/m/Y', strtotime($board['date_end'])) }}</span>
                                                </div>
                                                <div class="project-status">
                                                    <span>{{ $board['statusMessenger'] }}</span>
                                                </div>
                                                <div class="project-percent">
                                                    <span>{{ $board['percent'] }}% Concluído</span>
                                                </div>
                                            </div>
                                        </a>

                                        @if ($board['statusTask'] === 2 && 1 === 2)
                                            <div class="bg-notification">
                                                <div class="notification-title">
                                                    <span>Há tarefas atrasadas nesse projeto</span>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>

                @if (!empty($memberBoards))
                    <div class="boards">
                        <div class="boards-title">
                            <i class="far fa-clipboard"></i>
                            <h3>PROJETOS QUE SOU MEMBRO</h3>
                        </div>
                        <div class="boards-content">
                            <ul class="boards-list">

                                @foreach ($memberBoards as $board)
                                    <li class="board-item-content">

                                        @if (intval($board['totalNotifications']) > 0 && 1 === 2)
                                            <div class="ball-notification" style="background-color: #F00">
                                                <span>{{ $board['totalNotifications'] }}</span>
                                            </div>
                                        @endif

                                        <div class="board-item" style="border-color:{{ $board['statusColor'] }}">

                                            <div class="bg-full-percent {{ $actionsConfig['c_board_view_percent_type'] === 2 ? $board['color'] : '' }}"
                                                style="width: {{ $board['percent'] }}%"></div>

                                            <a href="{{ route('actions.board', ['id_board' => base64_encode($board['id'])]) }}"
                                                class="project-card">
                                                <div class="project-card-heard">

                                                    @if ($actionsConfig['c_board_view_project_color'] === 1)
                                                        <div class="project-color {{ $board['color'] }}"></div>
                                                    @endif

                                                    <div class="project-card-title">
                                                        <span class="title">{{ $board['name'] }}</span>
                                                    </div>
                                                    <div class="project-info"></div>
                                                </div>
                                                <div class="project-card-body">
                                                    <div class="project-deadline">
                                                        <span>{{ date('d/m/Y', strtotime($board['date_start'])) }}</span>
                                                        <span>à</span>
                                                        <span>{{ date('d/m/Y', strtotime($board['date_end'])) }}</span>
                                                    </div>
                                                    <div class="project-status">
                                                        <span>{{ $board['statusMessenger'] }}</span>
                                                    </div>
                                                    <div class="project-percent">
                                                        <span>{{ $board['percent'] }}% Concluído</span>
                                                    </div>
                                                </div>
                                            </a>

                                            @if ($board['statusTask'] === 2 && 1 === 2)
                                                <div class="bg-notification">
                                                    <div class="notification-title">
                                                        <span>Há tarefas atrasadas nesse projeto</span>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="modalNewProject" tabindex="-1" role="dialog" aria-labelledby="modalNewProjetLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNewProjetLabel">Novo Projeto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ route('actions.store') }}" method="post">
                            @csrf
                            <div class="form-row">
                                <div
                                    class="{{ $actionsConfig['c_board_view_project_color'] === 1 ? 'col-8' : 'col-12' }}">
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label for="projectTitle">Nome do Projeto</label>
                                            <div class="input-group input-project">
                                                <input type="text" class="form-control" name="projectTitle"
                                                    id="projectTitle" placeholder="Novo Projeto">
                                            </div>
                                        </div>
                                        <div class="form-group col-12">
                                            <label for="projectTitle">Descrição do Projeto</label>
                                            <div class="input-group input-project">
                                                <textarea type="text" class="form-control" name="projectDescription"
                                                    id="projectDescription"> </textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label for="projectTitle">Inicio do Projeto</label>
                                            <input type="date" class="form-control" name="dateStartProject"
                                                placeholder="NovoProjeto">
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="projectTitle">Fim do Projeto</label>
                                            <input type="date" class="form-control" name="dateEndProject"
                                                placeholder="NovoProjeto">
                                        </div>

                                        <div class="form-group col-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="authTaskCreate"
                                                    id="authTaskCreate" />
                                                <label class="form-check-label" for="authTaskCreate">Membros podem criar
                                                    tarefas</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($actionsConfig['c_board_view_project_color'] === 1)
                                    <div class="col-4">
                                        <label for="projectTitle">Cor</label>
                                        <div class="color-box">
                                            <ul class="color-list">
                                                @foreach ($bgColors as $indexColor)
                                                    <li class="color-item" onclick="handleCheck(this)">
                                                        <input type="radio" id="color1" name="boardColor"
                                                            value="{{ $indexColor }}">
                                                        <label class="{{ $indexColor }}">
                                                            <i class="fas fa-check d-none"></i>
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="row">
                                <div class="col-7">
                                    <button type="submit" class="btn btn-primary float-right">Criar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::to('/assets/css/actions_panel/main.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>

    <script>
        function handleCheck(el) {
            const elCheck = el.querySelector("input[type=radio]");
            const AllCheck = document.querySelectorAll("li[onclick='handleCheck(this)']");
            const stateCheck = elCheck.checked

            const elCheckView = el.querySelector("i");

            console.log(AllCheck)

            AllCheck.forEach(checkItem => {
                const elCheckItem = checkItem.querySelector("input[type=radio]");
                elCheckItem.checked = false;

                const elCheckViewItem = checkItem.querySelector("i");
                elCheckViewItem.classList.add("d-none");
            })

            elCheck.checked = !stateCheck;

            if (elCheck.checked) {
                elCheckView.classList.remove("d-none");
            } else {
                elCheckView.classList.add("d-none");
            }
        }
    </script>
@endsection
