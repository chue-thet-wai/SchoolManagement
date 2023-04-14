<?php

namespace App\Exports;

use App\Models\CancelRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFerry implements FromCollection, WithHeadings
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Track No',
            'Driver ID',
            'Name',
            'Phone',
            'Car Type',
            'Car No',
            'School from Time',
            'School to Time',
            'School from Period',
            'School to Period',
            'Arrive Student Number',
            'Township',
            'Twoway Amount',
            'Oneway Pickup Amount',
            'Oneway Back Amount'
        ];
    }
}
