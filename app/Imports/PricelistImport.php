<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PricelistImport implements ToArray, WithHeadingRow
{
    use Importable;

    public function array(Array $array)
    {
        //
    }
}
