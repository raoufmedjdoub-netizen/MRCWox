<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedTrackerPortsDefaults extends Migration
{
    /**
     * Insère les protocoles GPS standards si la table est vide.
     * GT06 est configuré sur le port 12050 (port personnalisé).
     */
    public function up()
    {
        if (DB::table('tracker_ports')->count() > 0) {
            return; // Déjà peuplé, on ne touche pas
        }

        $ports = [
            // Protocoles les plus courants
            ['active' => 1, 'port' => '5023',  'name' => 'gt06',       'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '12050', 'name' => 'teltonika',  'parent' => null, 'extra' => '{}'], // Teltonika FMB — port personnalisé
            ['active' => 1, 'port' => '5055',  'name' => 'osmand',     'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5013',  'name' => 'h02',        'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5108',  'name' => 'concox',     'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5004',  'name' => 'gl200',      'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5002',  'name' => 'tk103',      'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5011',  'name' => 'globalsat',  'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5020',  'name' => 'meitrack',   'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5016',  'name' => 'wialon',     'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5093',  'name' => 'watch',      'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5031',  'name' => 'topflytech', 'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5036',  'name' => 'laipac',     'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5058',  'name' => 'ulbotech',   'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5064',  'name' => 'minifinder', 'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5070',  'name' => 'calamp',     'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5080',  'name' => 'ruptela',    'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5082',  'name' => 'cellocator', 'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5096',  'name' => 'eelink',     'parent' => null, 'extra' => '{}'],
            ['active' => 1, 'port' => '5100',  'name' => 'box',        'parent' => null, 'extra' => '{}'],
        ];

        DB::table('tracker_ports')->insert($ports);
    }

    public function down()
    {
        DB::table('tracker_ports')->whereIn('name', [
            'gt06','teltonika','osmand','h02','concox','gl200','tk103',
            'globalsat','meitrack','wialon','watch','topflytech','laipac',
            'ulbotech','minifinder','calamp','ruptela','cellocator','eelink','box',
        ])->delete();
    }
}
