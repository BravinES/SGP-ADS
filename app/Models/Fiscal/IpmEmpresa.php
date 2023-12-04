<?php

namespace App\Models\Fiscal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpmEmpresa extends Model
{
    use HasFactory;

    protected $table = 'ipm_empresa';

    public function vafsDay() {
        return $this->hasMany(VafsProgressDay::class, 'inscricao', 'inscricao');
    }
}
