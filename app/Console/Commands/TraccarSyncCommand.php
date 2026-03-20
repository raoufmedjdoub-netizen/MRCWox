<?php

namespace App\Console\Commands;

use App\Console\PositionsStack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TraccarSyncCommand extends Command
{
    protected $signature = 'tracker:sync {--interval=2 : Poll interval in seconds}';
    protected $description = 'Sync positions from Traccar standard (tc_positions) to GPSWOX Redis pipeline';

    protected PositionsStack $stack;

    public function handle()
    {
        $this->stack = new PositionsStack();
        $interval = (int) $this->option('interval');

        $this->info('Traccar sync started (polling every ' . $interval . 's)');

        $lastId = (int) Cache::get('traccar_sync.last_position_id', 0);

        while (true) {
            try {
                $this->syncPositions($lastId);
            } catch (\Exception $e) {
                $this->error('Sync error: ' . $e->getMessage());
                sleep(5);
                continue;
            }

            sleep($interval);
        }
    }

    protected function syncPositions(&$lastId): int
    {
        $connection = DB::connection('traccar_mysql');

        if (!$this->tableExists($connection, 'tc_positions')) {
            return 0;
        }

        if (!$this->tableExists($connection, 'tc_devices')) {
            return 0;
        }

        $positions = $connection->table('tc_positions')
            ->join('tc_devices', 'tc_positions.deviceid', '=', 'tc_devices.id')
            ->where('tc_positions.id', '>', $lastId)
            ->orderBy('tc_positions.id')
            ->limit(500)
            ->select([
                'tc_positions.id',
                'tc_positions.protocol',
                'tc_positions.deviceid',
                'tc_positions.servertime',
                'tc_positions.devicetime',
                'tc_positions.fixtime',
                'tc_positions.valid',
                'tc_positions.latitude',
                'tc_positions.longitude',
                'tc_positions.altitude',
                'tc_positions.speed',
                'tc_positions.course',
                'tc_positions.address',
                'tc_positions.attributes',
                'tc_devices.uniqueid as imei',
            ])
            ->get();

        if ($positions->isEmpty()) {
            return 0;
        }

        foreach ($positions as $pos) {
            $data = $this->formatPosition($pos);
            $this->stack->add($data);
            $lastId = $pos->id;
        }

        Cache::forever('traccar_sync.last_position_id', $lastId);

        return $positions->count();
    }

    protected function formatPosition($pos): array
    {
        $attributes = [];
        if (!empty($pos->attributes)) {
            $decoded = json_decode($pos->attributes, true);
            if (is_array($decoded)) {
                $attributes = $decoded;
            }
        }

        $fixTime = $pos->fixtime
            ? strtotime($pos->fixtime) * 1000
            : null;

        $deviceTime = $pos->devicetime
            ? strtotime($pos->devicetime) * 1000
            : null;

        return [
            'imei'       => $pos->imei,
            'uniqueId'   => $pos->imei,
            'protocol'   => $pos->protocol ?? 'teltonika',
            'latitude'   => (float) $pos->latitude,
            'longitude'  => (float) $pos->longitude,
            'altitude'   => (float) ($pos->altitude ?? 0),
            'speed'      => (float) ($pos->speed ?? 0),
            'course'     => (float) ($pos->course ?? 0),
            'valid'      => (int) ($pos->valid ?? 1),
            'fixTime'    => $fixTime,
            'deviceTime' => $deviceTime,
            'attributes' => $attributes,
        ];
    }

    protected function tableExists($connection, string $table): bool
    {
        static $checked = [];

        if (isset($checked[$table])) {
            return $checked[$table];
        }

        try {
            $checked[$table] = $connection->getSchemaBuilder()->hasTable($table);
        } catch (\Exception $e) {
            $checked[$table] = false;
        }

        return $checked[$table];
    }
}
