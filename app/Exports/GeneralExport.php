<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class GeneralExport implements FromCollection
{
    function __construct(private $data,private $columns,private $headers){}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([array_combine($this->columns, $this->headers)])->merge($this->data)->map(function ($item) {
            return array_combine($this->columns, array_values($item));
        });
    }
}
