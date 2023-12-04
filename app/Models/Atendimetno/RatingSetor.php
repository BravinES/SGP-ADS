<?php

namespace App\Models\Atendimetno;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingSetor extends Model
{
    use HasFactory;
    protected $table = 'rating__setor';

    public function atendentes()
    {
        return $this->hasMany(RatingAtendente::class, 'setor_id');
    }
}
