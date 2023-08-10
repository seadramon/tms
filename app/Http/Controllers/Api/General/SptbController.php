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
                    'stockid' => $row->stockid,
                    'tgl' => $row->tgl_produksi
                ];
            }
        }
        $sptbd2 = SptbD2::where('no_sptb', $noSptb)->get()->groupBy('kd_produk');

        $pdf = Pdf::loadView('prints.sptb', [
            'data' => $data,
            'ppb' => $ppb,
            'detail2' => $detail2,
            'sptbd2' => $sptbd2,
        ]);

        $filename = "SPTB-SuratJalan";

        return $pdf->setPaper('a4', 'portrait')
            ->stream($filename . '.pdf');
    }

    public function deliveryByDate(Request $request)
    {
        $tgl = date('d/m/Y', strtotime($request->curr_date));
        $last_week = date('d/m/Y', strtotime("-7 days " . $request->curr_date));
        if($request->type == "pelanggan"){
            $sql = "select distinct a.no_sptb, TO_CHAR(tgl_berangkat,'DD/MM/YYYY')tgl_berangkat, TO_CHAR(tgl_sampai,'DD/MM/YYYY')tgl_sampai, C.SINGKATAN2 jenis_produk, app_pelanggan
                from sptb_h a
                inner join sptb_d b on a.no_sptb = b.no_sptb
                inner join sptb_d2 d on a.no_sptb = d.no_sptb
                inner join tb_sbu c on substr(b.kd_produk,1,1) = c.kd_sbu
                inner join tms_pelanggan_npps e on a.no_npp = e.no_npp   
                inner join tms_pelanggan_users f on e.pelanggan_user_id = f.id                                                                             
                where trunc(a.tgl_berangkat) between to_date('" . $last_week . "','dd/mm/yyyy') and to_date('" . $tgl . "','dd/mm/yyyy') and app_pelanggan = 0 and f.no_hp = '" . $request->no_hp . "'
                group by a.no_sptb, tgl_berangkat, jam_berangkat, tgl_sampai, jam_sampai, C.SINGKATAN2, app_pelanggan";
        }else{
            $filter = "";
            if($request->kd_pat != "0A"){
                $filter = " and a.no_npp like '20".$request->kd_pat."%'";
				$filter .=  " or a.no_npp like '19".$request->kd_pat."%'";
            }
            $sql = "select distinct a.no_sptb, nvl(TO_CHAR(tgl_berangkat,'DD/MM/YYYY'),'-')tgl_berangkat, nvl(TO_CHAR(tgl_sampai,'DD/MM/YYYY'),'-')tgl_sampai, C.SINGKATAN2 jenis_produk, app_pelanggan, a.kd_pat, e.ket as pat 
                from sptb_h a
                inner join sptb_d b on a.no_sptb = b.no_sptb
                inner join sptb_d2 d on a.no_sptb = d.no_sptb
                inner join tb_sbu c on substr(b.kd_produk,1,1) = c.kd_sbu
                inner join hrms.tb_pat e on a.kd_pat = e.kd_pat                                        
                where a.tgl_berangkat between to_date('" . $last_week . "','dd/mm/yyyy') and to_date('" . $tgl . "','dd/mm/yyyy')" . $filter . "
                group by a.no_sptb, tgl_berangkat, jam_berangkat, tgl_sampai, jam_sampai, C.SINGKATAN2, app_pelanggan, a.kd_pat, e.ket
                order by a.kd_pat asc, app_pelanggan asc";
        }

        $results = DB::select($sql);
        
        $data = [];
        foreach($results as $row){
            $temp = [     
                "no_sptb" => $row->no_sptb,
                "tgl_berangkat" => $row->tgl_berangkat,
                "tgl_sampai" => $row->tgl_sampai,
                "jenis_produk" => $row->jenis_produk,
                "app_pelanggan" => $row->app_pelanggan,
                "procedure_path" => "http://tms.wika-beton.co.id/document/procedure/" . $row->jenis_produk . ".pdf",
            ];
            if($request->type != "pelanggan"){
                $temp['pat'] = $row->pat;
            }
            $data[] = $temp;
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }
}

