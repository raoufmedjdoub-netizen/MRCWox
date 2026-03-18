<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveTraccarDevicesTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('traccar_devices'))
            return;

        // Try to copy from gpswox_traccar (upgrade path from older GPSWOX installs)
        try {
            DB::statement('CREATE TABLE `traccar_devices` LIKE `gpswox_traccar`.`devices`;');
            DB::statement('INSERT INTO `traccar_devices` SELECT * FROM `gpswox_traccar`.`devices`;');
            DB::statement('DROP TABLE `gpswox_traccar`.`devices`');
        } catch (\Exception $e) {
            // gpswox_traccar database doesn't exist (fresh install) — create table from scratch
            if (Schema::hasTable('traccar_devices'))
                return;

            Schema::create('traccar_devices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('uniqueId')->nullable()->unique();
                $table->bigInteger('latestPosition_id')->unsigned()->nullable();
                $table->double('lastValidLatitude')->nullable();
                $table->double('lastValidLongitude')->nullable();
                $table->dateTime('device_time')->nullable();
                $table->dateTime('server_time')->nullable();
                $table->dateTime('ack_time')->nullable();
                $table->dateTime('time')->nullable();
                $table->double('speed')->nullable();
                $table->text('other')->nullable();
                $table->double('altitude')->nullable();
                $table->double('power')->nullable();
                $table->double('course')->nullable();
                $table->string('address')->nullable();
                $table->string('protocol', 50)->nullable();
                $table->text('latest_positions')->nullable();
                $table->dateTime('moved_at')->nullable();
                $table->dateTime('stoped_at')->nullable();
                $table->dateTime('engine_on_at')->nullable();
                $table->dateTime('engine_off_at')->nullable();
                $table->dateTime('engine_changed_at')->nullable();
                $table->integer('database_id')->unsigned()->nullable();
                $table->dateTime('updated_at')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('CREATE TABLE `gpswox_traccar`.`devices` LIKE `traccar_devices`;');
        DB::statement('INSERT INTO `gpswox_traccar`.`devices` SELECT * FROM `traccar_devices`;');
        DB::statement('DROP TABLE `traccar_devices`');
    }
}
