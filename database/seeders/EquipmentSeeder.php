<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Equipment::create(
            [
                'name' => 'Google',
                'ip' => '8.8.8.8',
                'user_id' => 1
            ],
        );
        Equipment::create(
            [
                'name' => 'Localhost',
                'ip' => '127.0.0.1',
                'user_id' => 1
            ],
        );
        Equipment::create(
            [
                'name' => 'xbtech',
                'ip' => '112.1.43.45',
                'user_id' => 1
            ],
        );
        Equipment::create(
            [
                'name' => 'externo01',
                'ip' => '190.17.45.10',
                'user_id' => 1
            ]
        );
        Equipment::factory(5)->create();
    }
}
