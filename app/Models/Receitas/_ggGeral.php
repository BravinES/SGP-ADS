<?php

namespace App\Models\Receitas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _ggGeral extends Model
{
  use HasFactory;

  protected $connection = 'sqlsrv';
  protected $table = 'gg_geral';

  public $timestamps = false;
}
