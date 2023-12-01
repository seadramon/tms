<?php

namespace App\Exports;

use App\Models\Sp3;
use App\Models\Views\VSpprbRi;
use App\Models\Sbu;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Sp3Export implements FromView, WithStyles, ShouldAutoSize
{
    protected $jmlDetail = 0;

    function __construct($noSp3) {
        $this->noSp3 = $noSp3;
    }
 
    public function view(): View
    {
        $data = Sp3::with('unitkerja')->find($this->noSp3);
        $detail = $data->sp3D;
        $sp3pics = $data->pic;

        $sbu = null;
        if ($detail->count() > 0) {
            $this->jmlDetail = $detail->count();

            $sbu = Sbu::where('kd_sbu',  substr($detail[0]->kd_produk, 1, 1))->first();
        }

        $VSpprbRi = VSpprbRi::where('no_npp', $data->no_npp)->first();

        $pics = "";
        if (count($sp3pics) > 0) {
            $tmp = [];
            foreach ($sp3pics as $sp3pic) {
                $tmp[] = $sp3pic->employee->first_name.' '.$sp3pic->employee->last_name;
            }
            $pics = implode(", ", $tmp);
        }

        return view('pages.sp3.export-excel', [
            'data' => $data,
            'detail' => $detail,
            'sbu' => $sbu,
            'pics' => $pics,
            'vspprbRi' => $VSpprbRi
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A7:L9')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A10:L10')->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        if ($this->jmlDetail > 0) {
            $row = 27 + (int)$this->jmlDetail + 4;
        } else {
            $row = 27 + 1;
        }

        $sheet->getStyle('B25:J'.$row)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }
}
