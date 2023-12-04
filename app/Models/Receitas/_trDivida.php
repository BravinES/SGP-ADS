<?php

namespace App\Models\Receitas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _trDivida extends Model
{
  use HasFactory;

  protected $connection = 'sqlsrv';
  protected $table = 'tr_divida';

  public $timestamps = false;
}
