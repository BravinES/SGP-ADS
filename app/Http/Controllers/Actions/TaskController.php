<?php

namespace App\Http\Controllers\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Actions\Task;
use App\Models\Actions\Comment;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $task = new Task();
        //dd($request);
        $task->title = $request->taskTitle;
        $task->description = $request->taskDescription;
        $task->board_id = intval(base64_decode($request->id_board));
        $task->list_origin_id = intval($request->taskList);
        $task->list_actual_id = intval($request->taskList);
        $task->date_start = $request->startDate;
        $task->date_end = $request->endDate;
        $task->order_task_list = 1;

        //image Upload
        if($request->hasFile('taskCover') && $request->taskCover->isValid()){
            $task->cover = $request->taskCover->store('/images/task');
        } else {
            $task->cover = null;
        }

        $task->save();

        return redirect()->route('actions.board', ['id_board' => $request->id_board]);

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //dd($id);

        $task = Task::find($id);
        //dd($task);
        $task->title = $request->taskTitle;
        $task->description = $request->taskDescription;
        $task->list_actual_id = intval($request->taskList);
        $task->date_start = $request->startDate;
        $task->date_end = $request->endDate;
        $task->status = $request->taskStatus;

        $task->save();

        return redirect()->route('actions.board', ['id_board' => base64_encode($task->board_id)]);
    }

    public function destroy($id)
    {
        //
    }

    public function showComments($id)
    {
        $loggedId = intval(Auth::id());

        $comment = Comment::where('task_id', $id)
        ->where('user_id', $loggedId)->get();
        return json_encode($comment);

    }

    public function storeComment(Request $request)
    {
        $loggedId = intval(Auth::id());

        dd($request);
    }
}
