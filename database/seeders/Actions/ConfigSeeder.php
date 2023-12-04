<?php

namespace Database\Seeders\Actions;

use App\Models\Actions\Config as ActionsConfig;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        ActionsConfig::create([
            'c_name' => 'Default',
            'c_model' => 1,
            'user_id' => 1,
            'c_board_name' => 'Ações'
        ]);
    }
}
