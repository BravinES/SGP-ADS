<?php

namespace App\Models\Actions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    use HasFactory;

    protected $table = 'actions__board_member';

    public function boards()
    {
        return $this->hasMany(Board::class, 'id' ,'board_id');
    }
}
