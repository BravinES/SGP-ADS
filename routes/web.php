<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

//-| Painel de Ações |---------------------------------------------------------|
use App\Http\Controllers\Actions\ActionsController;
use App\Http\Controllers\Actions\BoardMemberController;
use App\Http\Controllers\Actions\CommentController;
use App\Http\Controllers\Actions\TaskController;

Route::get('/', [ActionsController::class, 'index'])->name('dashboard');
Route::get('info', function () {
    return phpinfo();
});

Route::get('/dashboard', [ActionsController::class, 'index']);

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'setRegister']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('password/reset', [AuthController::class, 'passwordReset']);
Route::post('password/reset', [AuthController::class, 'setPasswordReset']);

Route::resource('users', UserController::class);

Route::get('users/profile', [UserController::class, 'profile'])->name('user.profile.index');
Route::post('users/profile', [UserController::class, 'alterPassword'])->name('user.profile.index');



//-| Painel de Ações |---------------------------------------------------------|
Route::get('projetos', [ActionsController::class, 'index'])->name('actions.index');
Route::get('projeto/{id_board}', [ActionsController::class, 'showBoard'])->name('actions.board');
Route::post('projeto', [ActionsController::class, 'store'])->name('actions.store');
Route::post('projeto/list/{id_board}', [ActionsController::class, 'listStore'])->name('actions.list.store');
Route::put('projeto/list/{id_board}', [ActionsController::class, 'listUpdate'])->name('actions.list.update');

Route::post('projeto/task/{id_board}', [TaskController::class, 'store'])->name('task.store');
Route::put('projeto/task/{id_task}', [TaskController::class, 'update'])->name('task.update');

//-| Painel de Ações |---------------------------------------------------------|
Route::get('board/task/comments/{task_id}', [CommentController::class, 'show'])->name('comments.show');
Route::post('board/task/comment', [CommentController::class, 'store'])->name('comment.store');

//-| Painel de Ações |---------------------------------------------------------|
Route::get('board/member/{board_id}', [BoardMemberController::class, 'show'])->name('board.member.show');
Route::post('board/member/', [BoardMemberController::class, 'store'])->name('board.member.store');
Route::delete('board/member/{id}', [BoardMemberController::class, 'destroy'])->name('board.member.delete');
