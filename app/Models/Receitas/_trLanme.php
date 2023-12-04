<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _trLanme extends Model
{
  use HasFactory;

  protected $connection = 'sqlsrv';
  protected $table = 'tr_lanme';
  
  public $timestamps = false;
}