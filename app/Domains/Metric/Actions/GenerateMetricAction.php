<?php

namespace App\Domains\Metric\Actions;

use Carbon\Carbon;

class GenerateMetricAction
{
    public function handle(): array
    {
        return [
            'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
            'device' => rand(1, 100),
            'data' => $this->generateSensorsData()
        ];
    }

    protected function generateSensorsData(): array
    {
        $quantitySensors = rand(1, 3);
        $sensors = [];
        $ids = [];
        for ($i = 1; $i <= $quantitySensors; $i++){
            while(true){
                $id = rand(1, 3);
                if (!in_array($id, $ids)){
                    $sensors[] = [
                        'sensor' => $id,
                        'temp' => rand(2, 20)
                    ];
                    $ids[] = $id;
                    break;
                }
            }
        }
        return $sensors;
    }
}