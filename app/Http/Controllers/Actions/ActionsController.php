<?php

namespace App\Http\Controllers\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Functions;

use App\Models\Actions\Board;
use App\Models\Actions\BoardListTask;
use App\Models\Actions\BoardMember;
use App\Models\Actions\Config as ActionsConfig;
use App\Models\Actions\Task as ActionsTask;
use App\Models\Actions\Notification as ActionsNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ActionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $functions = new Functions;

        $loggedId = intval(Auth::id());

        $boards = Board::select()->where('user_id', $loggedId)->get()->toArray();


        $memberBoards = BoardMember::where('user_id', $loggedId)->with('boards')->first();
        if (!empty($memberBoards)) {
            $memberBoards->toArray();
        } else {
            $memberBoards = ['boards' => []];
        }



        // Configurações e Cores
        $actionsConfig = ActionsConfig::where('c_name', 'Default')->first()->toArray();
        $bgColors = array_slice($functions->colorsClass(), 1, 20);

        // Status do projeto
        //dd($boards->toArray());
        $actionsConfig['c_task_status_name'] = explode(';', $actionsConfig['c_task_status_name']);
        $actionsConfig['c_task_status_color'] = explode(';', $actionsConfig['c_task_status_color']);

        //dd($actionsConfig);

        //verificar atrasadas
        $dateActual = $functions->dateActual();
        foreach ($boards as $key => $board) {
            $hasLate = false;
            $hasTaskLate = false;

            //dd($board);

            // Verfica se esta atrasada
            // Atrasado por data de inicio do projeto
            if ($board['date_start'] < $dateActual && $board['status'] === 0) {
                $hasLate = true;
            }

            // Atrasado por ter uma tarefa com a data de inicio atrasada

            //dd($dateActual);

            //dd(ActionsTask::where('board_id', $board['id'])->where('date_start', '<', $dateActual)->where('status', 0)->count());

            if (ActionsTask::where('board_id', $board['id'])->where('date_start', '<', $dateActual)->where('status', 0)->count() > 0) {
                $hasLate = true;
                $hasTaskLate = true;
            }

            // Atrasado por ter uma tarefa com a data final atrasada
            if (ActionsTask::where('board_id', $board['id'])->where('date_end', '<', $dateActual)->whereIn('status', [3, -1])->count() > 0) {
                $hasLate = true;
                $hasTaskLate = true;
            }


            // Ajustar as informação de atrasado
            if ($hasLate) {
                $boards[$key]['status'] = 2;
                $boards[$key]['statusMessenger'] = 'Atrasado';
                $boards[$key]['statusColor'] = $actionsConfig['c_task_status_color'][2];
            } else {
                $boards[$key]['statusMessenger'] = 'Não iniciado';
                $boards[$key]['statusColor'] = $actionsConfig['c_task_status_color'][0];
            }

            if ($hasTaskLate) {
                $boards[$key]['statusTask'] = 2;
            } else {
                $boards[$key]['statusTask'] = 0;
            }

            //notifications
            // notificações
            //$boards[$key]['notifications'] = ActionsNotification::where('board_id', $board['id'])->where('user_id', $loggedId)->get()->toArray();

            //Só conta as notificações que não foram lidas
            $boards[$key]['totalNotifications'] = ActionsNotification::where('board_id', $board['id'])
                ->where('user_id', $loggedId)
                ->where('status', 0)
                ->count();


            // Porecentagem de concluídas
            $allTasks = ActionsTask::where('board_id', $board['id'])->whereNotIn('status', [-1])->count();
            $doneTasks = ActionsTask::where('board_id', $board['id'])->where('status', 3)->count();
            $boards[$key]['percent'] = ($allTasks > 0) ? round(($doneTasks * 100) / $allTasks, 2) : 0;
        }

        foreach ($memberBoards['boards'] as $mbkey => $board) {
            $hasLate = false;
            $hasTaskLate = false;

            //dd($board);

            // Verfica se esta atrasada
            // Atrasado por data de inicio do projeto
            if ($board['date_start'] < $dateActual && $board['status'] === 0) {
                $hasLate = true;
            }

            // Atrasado por ter uma tarefa com a data de inicio atrasada

            //dd($dateActual);

            //dd(ActionsTask::where('board_id', $board['id'])->where('date_start', '<', $dateActual)->where('status', 0)->count());

            if (ActionsTask::where('board_id', $board['id'])->where('date_start', '<', $dateActual)->where('status', 0)->count() > 0) {
                $hasLate = true;
                $hasTaskLate = true;
            }

            // Atrasado por ter uma tarefa com a data final atrasada
            if (ActionsTask::where('board_id', $board['id'])->where('date_end', '<', $dateActual)->whereIn('status', [3, -1])->count() > 0) {
                $hasLate = true;
                $hasTaskLate = true;
            }


            // Ajustar as informação de atrasado
            if ($hasLate) {
                $memberBoards['boards'][$mbkey]['status'] = 2;
                $memberBoards['boards'][$mbkey]['statusMessenger'] = 'Atrasado';
                $memberBoards['boards'][$mbkey]['statusColor'] = $actionsConfig['c_task_status_color'][2];
            } else {
                $memberBoards['boards'][$mbkey]['statusMessenger'] = 'Não iniciado';
                $memberBoards['boards'][$mbkey]['statusColor'] = $actionsConfig['c_task_status_color'][0];
            }

            if ($hasTaskLate) {
                $memberBoards['boards'][$mbkey]['statusTask'] = 2;
            } else {
                $memberBoards['boards'][$mbkey]['statusTask'] = 0;
            }

            //notifications
            // notificações
            //$boards[$key]['notifications'] = ActionsNotification::where('board_id', $board['id'])->where('user_id', $loggedId)->get()->toArray();

            //Só conta as notificações que não foram lidas
            $memberBoards['boards'][$mbkey]['totalNotifications'] = ActionsNotification::where('board_id', $board['id'])
                ->where('user_id', $loggedId)
                ->where('status', 0)
                ->count();


            // Porecentagem de concluídas
            $allTasks = ActionsTask::where('board_id', $board['id'])->whereNotIn('status', [-1])->count();
            $doneTasks = ActionsTask::where('board_id', $board['id'])->where('status', 3)->count();
            $memberBoards['boards'][$mbkey]['percent'] = ($allTasks > 0) ? round(($doneTasks * 100) / $allTasks, 2) : 0;
        }

        //dd($memberBoards['boards']);

        return view('actions.index', [
            "boards" => !empty($boards) ? $boards : [],
            "memberBoards" => !empty($memberBoards['boards']) ? $memberBoards['boards'] : [],
            "boardColors" => $functions->colorsClass(),
            "bgColors" => $bgColors,
            "actionsConfig" => $actionsConfig,
            //"notifications" => $notifications,
            //"statusProject" => $statusProject
        ]);
    }

    public function showBoard(Request $request)
    {

        $loggedId = intval(Auth::id());
        $boardId = base64_decode($request->id_board) ?? 0;

        $board = Board::where('id', $boardId)->with('boardListTask')->first();
        $memberBoards = BoardMember::select('actions__board_member.id', 'actions__board_member.user_id', 'users.name', 'users.email')
            ->where('board_id', $boardId)
            ->where('user_id', '!=', $loggedId)
            ->join('users', 'users.id', '=', 'user_id')
            ->get()->toArray();

        $isMembrer = [];
        foreach ($memberBoards as $memberBoard) {
            $isMembrer[] = $memberBoard['user_id'];
        }

        $allUsers = User::select('id', 'name', 'email')
            ->where('id', '!=', $loggedId)
            ->whereNotIn('id', $isMembrer)
            ->get()->toArray();

        return view('actions.board', [
            'board' => $board,
            'allUsers' => $allUsers,
            'memberBoards' => $memberBoards
        ]);
    }

    public function store(Request $request)
    {
        $loggedId = intval(Auth::id());

        //dd($request->all());

        $board = new Board();
        $board->name = $request->projectTitle;
        $board->description = $request->projectDescription;
        $board->user_id = $loggedId;
        $board->date_start = $request->dateStartProject;
        $board->date_end = $request->dateEndProject;
        if (!empty($request->authTaskCreate)) $board->auth_task_create = 1;
        if (!empty($request->boardColor)) $board->color = $request->boardColor;
        $board->save();

        return redirect()->route('actions.index');
    }

    public function  listStore(Request $request)
    {
        $idBoard = base64_decode($request->id_board);

        $boardList = new BoardListTask();
        $boardList->board_id = $idBoard;
        $boardList->title = $request->titleNewList;
        $boardList->type = $request->type || 1;
        $boardList->save();

        //$board = Board::find(base64_decode($request->id_board));

        return redirect()->route('actions.board', ['id_board' => $request->id_board]);
    }

    public function  listUpdate(Request $request)
    {

        $boardList = BoardListTask::find($request->listTitleId);

        $boardList->title = $request->listTitleText;
        $boardList->save();

        return redirect()->route('actions.board', ['id_board' => base64_encode($boardList->board_id)]);
    }
}
