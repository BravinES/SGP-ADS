<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Isaac José Silvério',
            'name_show' => 'Isaac Silvério',
            'email' => 'isaac.acz@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ]);

        User::create([
            'name' => 'Marcos Vinicius Bravin de Paula',
            'name_show' => 'marcos.paula',
            'email' => 'marcos.paula@el.com.br',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ]);
        
    }
}
