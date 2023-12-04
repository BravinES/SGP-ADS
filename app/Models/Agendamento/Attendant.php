<?php

namespace App\Models\Agendamento;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendant extends Model
{
    use HasFactory;

    protected $table = 'ag_attendants';

    public function user(){
        return $this->hasOne(User::class, 'id', 'id_user');
    }

}
