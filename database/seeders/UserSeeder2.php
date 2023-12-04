<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder2 extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Silvaneide Sousa dos Santos Barboza',
            'name_show' => 'Silvaneide Barboza',
            'email' => 'sbarboza@aracruz.es.gov.br',
            'password' => password_hash("1234", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Valcirene Ribeiro Silva',
            'name_show' => 'Valcirene Ribeiro',
            'email' => 'vribeiro@aracruz.es.gov.br',
            'password' => password_hash("1234", PASSWORD_DEFAULT),
        ])->roles()->attach(7);
    }
}
