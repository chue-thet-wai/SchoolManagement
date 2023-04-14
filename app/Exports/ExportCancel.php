<?php

namespace App\Exports;

use App\Models\CancelRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportCancel implements FromCollection, WithHeadings
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
            'Registration No',
            'Student ID',
            'Cancel Date',
            'Refund Amount'
        ];
    }
}
