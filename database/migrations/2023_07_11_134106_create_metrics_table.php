<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \PhpClickHouseLaravel\Migration {
    public function up()
    {
        static::write('
            CREATE TABLE IF NOT EXISTS telemetry.metrics (
                `datetime` DateTime,
                `device` UInt32,
                `sensor` UInt16,
                `temperature` Float32
            ) ENGINE = MergeTree() 
            PARTITION BY toYYYYMM(datetime)
            ORDER BY
                (device, sensor);
        ');
    }

    public function down()
    {
        static::write('DROP TABLE telemetry');
    }
};