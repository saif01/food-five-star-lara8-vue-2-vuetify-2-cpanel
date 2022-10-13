<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HourExport implements WithMultipleSheets
{
    
    public $data;
    

    public function __construct($data){
        $this->data = $data;

        
    }

    public function sheets(): array
    {
        $sheets = [];
        $iterate = count($this->data->chart_label);

        for ($label = 0; $label < $iterate; $label++) {
            $sheets[] = new PerHourExport($this->data->chart_label[$label], $this->data);
        }

        return $sheets;
    }



    
}
