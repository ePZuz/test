<?php

namespace App\Domains\Metric\Actions;

use App\Domains\Metric\Models\Metric;

class InsertMetricAction
{
    public function handle($json){
        $metrics = [];
        foreach ($json['data'] as $sensor) {
            $metrics[] = [
                'device' => $json['device'],
                'datetime' => $json['datetime'],
                'sensor' => $sensor['sensor'],
                'temperature' => $sensor['temp'],
            ];
        }
        Metric::insertAssoc($metrics);
    }
}