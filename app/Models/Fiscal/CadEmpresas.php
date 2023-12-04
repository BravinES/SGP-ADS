<?php

namespace App\Models\Fiscal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadEmpresas extends Model
{
    use HasFactory;

    public function speedFiles() {
        return $this->hasMany(UploadFiles::class, 'file_cpnj', 'cnpj');
    }

    public function dotFiles() {
        return $this->hasMany(DotUpload::class, 'file_cpnj', 'cnpj');
    }
}
