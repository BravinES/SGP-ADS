<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'actions__tasks';

    //Comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id', 'id');
    }

}



