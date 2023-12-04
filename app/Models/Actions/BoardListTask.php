<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardListTask extends Model
{
    use HasFactory;

    protected $table = 'actions__lists';

    public function listTasks()
    {
        //list_actual_id
        return $this->hasMany(Task::class, 'list_actual_id', 'id');
    }
}
