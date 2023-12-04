<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder3 extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Rafael Colodetti Santos ',
            'name_show' => 'Rafael Colodetti Santos',
            'email' => 'rafaelcolodetti@hotmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 01',
            'name_show' => 'Usuário Padrão 01',
            'email' => 'usuariopadrao01@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 02',
            'name_show' => 'Usuário Padrão 02',
            'email' => 'usuariopadrao02@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 03',
            'name_show' => 'Usuário Padrão 03',
            'email' => 'usuariopadrao03@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 04',
            'name_show' => 'Usuário Padrão 04',
            'email' => 'usuariopadrao04@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 05',
            'name_show' => 'Usuário Padrão 05',
            'email' => 'usuariopadrao05@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 06',
            'name_show' => 'Usuário Padrão 06',
            'email' => 'usuariopadrao06@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

        User::create([
            'name' => 'Usuário Padrão 07',
            'name_show' => 'Usuário Padrão 07',
            'email' => 'usuariopadrao07@gmail.com',
            'password' => password_hash("123456", PASSWORD_DEFAULT),
        ])->roles()->attach(7);

    }
}
