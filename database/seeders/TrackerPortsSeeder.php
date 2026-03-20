<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrackerPortsSeeder extends Seeder
{
    public function run()
    {
        $ports = [
            ['active' => 1, 'port' => '12050', 'name' => 'teltonika', 'extra' => '{}'],
        ];

        foreach ($ports as $port) {
            $exists = DB::table('tracker_ports')->where('name', $port['name'])->exists();

            if (!$exists) {
                DB::table('tracker_ports')->insert($port);
            }
        }
    }
}
