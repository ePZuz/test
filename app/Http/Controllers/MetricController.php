<?php

namespace App\Http\Controllers;

use App\Domains\Metric\Repository\MetricRepository;
use App\Domains\Metric\Requests\MetricRequest;
use PhpClickHouseLaravel\RawColumn;

class MetricController extends Controller
{
    public function __invoke(MetricRequest $request)
    {
        $repository = new MetricRepository();
        $repository->select(['sensor', new RawColumn('avg(temperature) as avg_temp')]);
        $repository->setFilterByRequest($request);
        return $repository->getQuery()->groupBy(['sensor'])->getRows();
    }
}