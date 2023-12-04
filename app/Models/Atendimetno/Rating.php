<?php

namespace App\Models\Atendimetno;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'rating__rating';

    public function atendente()
    {
        return $this->belongsTo(RatingAtendente::class, 'atendente_id');
    }
}
