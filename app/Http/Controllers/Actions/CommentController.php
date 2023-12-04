<?php

namespace App\Http\Controllers\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Actions\Board;
use App\Models\Actions\BoardMember;
use App\Models\Actions\Task;
use App\Models\Actions\Comment;
use App\Models\Actions\CommentPrivate;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
        $loggedId = intval(Auth::id());
        $comment = new Comment();

        $comment->board_id = $request->boardId;
        $comment->task_id = $request->taskId;
        $comment->user_id = $loggedId;
        $comment->comment = $request->comment;
        $comment->private = $request->private;

        if (!empty($request->parent_id)) {
            $comment->parent_id = $request->parent_id;
        }

        if (!empty($request->comment_attached_id)) {
            $comment->comment_attached_id = $request->comment_attached_id;
        }

        $comment->save();

        if ($request->private == 1) {
            foreach ($request->user as $key => $userPrivete) {
                $commentPrivate = new CommentPrivate();
                $commentPrivate->comment_id = $comment->id;
                $commentPrivate->task_id = $comment->task_id;
                $commentPrivate->user_id = $userPrivete;
                $commentPrivate->save();
            }
        }

        return response()->json(['success' => true, 'taskId' => $request->taskId, 'boardId' => $request->boardId]);
    }

    public function show($id)
    {
        $loggedId = intval(Auth::id());

        $privateComment = CommentPrivate::select('comment_id')
            ->where('user_id', $loggedId)
            ->where('task_id', $id)
            ->get()->toArray();

        $privateComment = array_column($privateComment, 'comment_id');


        $comment = Comment::select('users.name', 'users.photo', 'actions__comment.*')
            ->where('actions__comment.task_id', $id)
            ->where(function ($query)  use ($loggedId, $privateComment) {
                $query->where('actions__comment.user_id', $loggedId)
                    ->orWhereIn('actions__comment.id', $privateComment)
                    ->orWhere('actions__comment.private', 0);
            })
            ->whereNull('actions__comment.parent_id')
            ->join('users', 'users.id', '=', 'actions__comment.user_id')
            ->get();

        $comment = array_map(function ($item) {
            $item['subComments'] = Comment::where('actions__comment.parent_id', $item['id'])
                ->join('users', 'users.id', '=', 'actions__comment.user_id')
                ->get();
            return $item;
        }, $comment->toArray());



        //mambre comment

        $boardId = Task::select('board_id')->where('id', $id)->first();
        $userCreate = Board::select('user_id', 'users.name', 'users.email')->where('actions__board.id', $boardId->board_id)->join('users', 'users.id', '=', 'user_id')->first();

        $memberBoards = BoardMember::select('actions__board_member.user_id', 'users.name', 'users.email')
            ->where('board_id', $boardId->board_id)
            ->where('user_id', '!=', $loggedId)
            ->join('users', 'users.id', '=', 'user_id')
            ->get()->toArray();



        if($userCreate && $userCreate->user_id !== $loggedId){
            $memberBoards[] = $userCreate->toArray();
        }

        return json_encode(['comment' => $comment, 'memberBoards' => $memberBoards]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
