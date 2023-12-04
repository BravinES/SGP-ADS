<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    public function run()
    {
        foreach ([
            'dev_master',
            'super_admin',
            'admin',
            'divida_ativa',
            'economico',
            'imobiliario',
            'fiscal_renda'
        ] as $name) {
            Role::create(['name' => $name]);
        }
    }
}
