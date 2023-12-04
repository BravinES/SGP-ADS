<?php

namespace App\Http\Controllers\Actions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Actions\BoardMember;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BoardMemberController extends Controller
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
        $isMembrer = BoardMember::where('board_id', $request->boradId)
            ->where('user_id',  $request->userId)
            ->first();

        $mensagem = '';

        if (!$isMembrer) {
            $boardMember = new BoardMember();
            $boardMember->board_id = $request->boradId;
            $boardMember->user_id = $request->userId;
            $boardMember->save();
        } else {
            $mensagem = 'Já é membro';
        }

        return response()->json(['mensagem' => $mensagem]);
    }

    public function show($id)
    {

        $loggedId = intval(Auth::id());

        $memberBoards = BoardMember::select('actions__board_member.id', 'actions__board_member.user_id', 'users.name', 'users.email')
            ->where('board_id', $id)
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

            return response()->json(
            [
                'allUsers' => $allUsers,
                'memberBoards' => $memberBoards
            ]
        );
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        BoardMember::where('id', $id)->delete();
    }
}
