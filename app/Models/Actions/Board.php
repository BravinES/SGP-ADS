<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $table = 'actions__board';

    public function boardListTask()
    {
        return $this->hasMany(BoardListTask::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'board_id','id');
    }
}



