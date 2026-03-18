<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedDeviceIconsDefaults extends Migration
{
    public function up()
    {
        if (DB::table('device_icons')->count() > 0) {
            return;
        }

        DB::table('device_icons')->insert([
            // Icône statut (by_status=1 : online/offline/ack séparés)
            ['type' => 'icon', 'order' => 1,  'width' => 26, 'height' => 26, 'path' => 'assets/images/arrow-online.png',  'by_status' => 1],
            ['type' => 'icon', 'order' => 2,  'width' => 26, 'height' => 26, 'path' => 'assets/images/arrow-offline.png', 'by_status' => 1],
            ['type' => 'icon', 'order' => 3,  'width' => 26, 'height' => 26, 'path' => 'assets/images/arrow-ack.png',     'by_status' => 1],
            // Icônes simples (by_status=0 : une seule icône)
            ['type' => 'icon', 'order' => 4,  'width' => 26, 'height' => 26, 'path' => 'assets/images/arrow-blue.png',   'by_status' => 0],
            ['type' => 'icon', 'order' => 5,  'width' => 26, 'height' => 26, 'path' => 'assets/images/arrow-black.png',  'by_status' => 0],
            ['type' => 'icon', 'order' => 6,  'width' => 26, 'height' => 26, 'path' => 'assets/images/arrow-orange.png', 'by_status' => 0],
            ['type' => 'icon', 'order' => 7,  'width' => 32, 'height' => 32, 'path' => 'assets/images/no-icon.png',      'by_status' => 0],
        ]);
    }

    public function down()
    {
        DB::table('device_icons')->whereIn('path', [
            'assets/images/arrow-online.png',
            'assets/images/arrow-offline.png',
            'assets/images/arrow-ack.png',
            'assets/images/arrow-blue.png',
            'assets/images/arrow-black.png',
            'assets/images/arrow-orange.png',
            'assets/images/no-icon.png',
        ])->delete();
    }
}
