<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\LoginResource;
use App\Models\Driver;
use App\Models\GpsLog;
use App\Models\Pat;
use App\Models\Personal;
use App\Models\SpprbH;
use App\Models\SptbD2;
use App\Models\SptbH;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SptbController extends Controller
{
    public function sptbPrint($sptb)
    {
        $noSptb = str_replace('|', '/', $sptb);
        $ppb = null;

        $data = SptbH::find($noSptb);

        // get ppb
        $trxid = !empty($data->trxid)?$data->trxid:null;
        if ($trxid) {
            $arr = explode("-", $trxid);
            $pat_ppb = Pat::where('kd_pat', $arr[1])->first();
            $ppb = $pat_ppb->ket;
        }

        $sptbd2 = SptbD2::where('no_sptb', $noSptb)->get();
        $detail2 = [];
        if (count($sptbd2) > 0) {
            foreach ($sptbd2 as $row) {
                $detail2[$row->kd_produk] = [
                    'stockid' => $row->stockid
                ];
            }
        }

        $pdf = Pdf::loadView('prints.sptb', [
            'data' => $data,
            'ppb' => $ppb,
            'detail2' => $detail2,
        ]);

        $filename = "SPTB-SuratJalan";

        return $pdf->setPaper('a4', 'portrait')
            ->stream($filename . '.pdf');
    }
}

