<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class StepArrayExport implements FromArray
{
    public function __construct(private array $rows) {}
    
    public function array(): array 
    { 
        return $this->rows; 
    }
}