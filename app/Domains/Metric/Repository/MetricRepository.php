<?php

namespace App\Domains\Metric\Repository;

use App\Domains\Metric\Models\Metric;
use App\Domains\Metric\Requests\MetricRequest;

class MetricRepository
{
    protected \PhpClickHouseLaravel\Builder $query;

    public function __construct()
    {
        $this->query = Metric::select([]);
    }

    public function setFilterByRequest(MetricRequest $request): static
    {
        if (!empty($request->input('startAt'))){
            $this->setStartAt($request->input('startAt'));
        }
        if (!empty($request->input('endAt'))){
            $this->setEndAt($request->input('endAt'));
        }
        if (!empty($request->input('sensorId'))){
            $this->query->where('sensor', $request->input('sensorId'));
        }
        return $this;
    }

    public function select(array $select): static
    {
        $this->query->select($select);
        return $this;
    }

    public function setStartAt(string $startAt): static
    {
        $this->query->where('datetime', '>=', $startAt);
        return $this;
    }

    public function setEndAt(string $endAt): static
    {
        $this->query->where('datetime', '<=', $endAt);
        return $this;
    }

    public function getQuery(): \PhpClickHouseLaravel\Builder
    {
        return $this->query;
    }
}