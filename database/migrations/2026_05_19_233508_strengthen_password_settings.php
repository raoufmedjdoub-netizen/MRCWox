<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StrengthenPasswordSettings extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('configs')) {
            return;
        }

        $row = DB::table('configs')->where('title', 'password')->first();

        if (!$row) {
            return;
        }

        $value = @unserialize($row->value);

        if (!is_array($value)) {
            return;
        }

        if (!isset($value['min_length']) || $value['min_length'] < 10) {
            $value['min_length'] = 10;
        }

        $includes = $value['includes'] ?? [];
        if (!is_array($includes)) {
            $includes = [];
        }

        foreach (['lowercase', 'uppercase', 'numbers', 'specials'] as $required) {
            if (!in_array($required, $includes, true)) {
                $includes[] = $required;
            }
        }
        $value['includes'] = array_values($includes);

        DB::table('configs')
            ->where('title', 'password')
            ->update(['value' => serialize($value)]);
    }

    public function down()
    {
        // No-op: we don't downgrade security settings.
    }
}
