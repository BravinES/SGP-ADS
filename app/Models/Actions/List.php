<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    use HasFactory;

    protected $table = 'actions__lists';

    public function boardListTasks()
    {
        return $this->hasMany(BoardListTask::class, 'board_list_actual_id' ,'id');
    }
}
