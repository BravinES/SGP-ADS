<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;



use Database\Seeders\Actions\ConfigSeeder as ActionsConfigSeeder;


class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            UserSeeder::class
        ]);

        $this->call(ActionsConfigSeeder::class);
    }
}
