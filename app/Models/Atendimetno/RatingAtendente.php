<?php

namespace App\Models\Atendimetno;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingAtendente extends Model
{
    use HasFactory;
    protected $table = 'rating__atendente';

    public function setor()
    {
        return $this->belongsTo(RatingSetor::class, 'setor_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'atendente_id');
    }
}
