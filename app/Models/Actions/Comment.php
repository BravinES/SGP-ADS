<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'actions__comment';

    public function  subComments()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
 }
