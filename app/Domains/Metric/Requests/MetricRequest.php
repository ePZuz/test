<?php

namespace App\Domains\Metric\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetricRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'startAt' => 'nullable|date',
            'endAt' => 'nullable|date',
            'sensorId' => 'int|between:0,3'
        ];
    }

}
