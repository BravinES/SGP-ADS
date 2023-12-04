<?php

namespace App\Models\Agendamento;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppointment extends Model
{
    use HasFactory;

    protected $table = 'ag_user_appointments';

    public function user(){
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
