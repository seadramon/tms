<?php

namespace App\Exports;

use App\Models\Sp3;
use App\Models\Spk;
use App\Models\Npp;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpkExport implements FromView, WithStyles, WithDefaultStyles, ShouldAutoSize
{
    protected $noSpk = null;

    function __construct($noSpk) {
        $this->noSpk = $noSpk;
    }

    public function view(): View
    {
        $data = Spk::with(['vendor', 'spk_d', 'unitkerja', 'jenisPekerjaan'])->find($this->noSpk);

        $npp = Npp::find($data->no_npp);

        return view('pages.spk.export-excel', [
            'data' => $data,
            'npp' => $npp
        ]);
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getFont()->setName('Times New Roman');
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A12:O12')->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        /*$sheet->getStyle('A7:L9')->applyFromArray([
            'borders' => [
                'outline' => [
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
        ]);*/
    }
}
