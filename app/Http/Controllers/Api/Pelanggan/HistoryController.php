<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pelanggan\PelangganResource;
use App\Models\Pelanggan;
use App\Models\PelangganUser;
use App\Models\SptbD2;
use App\Models\SptbH;
use App\Services\KalenderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class HistoryController extends Controller
{

    public function nppList($no_hp)
    {
        $sql = "select b.no_npp, b.nama_proyek, b.kd_sbu, c.ket as sbu
					from usradm.usr_pelanggan_d a
					inner join npp b on a.no_npp = b.no_npp
					inner join tb_sbu c on b.kd_sbu = c.kd_sbu
					where a.no_hp = '".$no_hp."'";

        $results = DB::select("select b.no_npp, b.nama_proyek, b.kd_sbu, c.ket as sbu
            from tms_pelanggan_npps a
            inner join tms_pelanggan_users f on a.pelanggan_user_id = f.id                                                                             
            inner join npp b on a.no_npp = b.no_npp
            inner join tb_sbu c on b.kd_sbu = c.kd_sbu
            where f.no_hp = '" . $no_hp . "'");
        
        $data = [];
        foreach($results as $row){
            $data[] = [     
                "no_npp" => $row->no_npp,
                "nama_proyek" => $row->nama_proyek,
                "sbu" => $row->sbu,
                "procedure_path" => "http://tms.wika-beton.co.id/document/procedure/" . $row->sbu . ".pdf",
            ];
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }
    
    public function sptbListByNpp($no_npp)
    {
        $sql = "select distinct a.no_sptb, to_char(tgl_sampai,'dd/mm/yyyy')tgl_sampai, jam_sampai, penerima 
					from WOS.SPTB_H a 
					inner join sptb_d2 b on a.no_sptb = b.no_sptb 
					where no_npp = '".$no_npp."' and app_pelanggan = '1' 
					order by to_date(tgl_sampai,'dd/mm/yyyy') desc";

        $results = DB::select("select distinct a.no_sptb, to_char(tgl_sampai,'dd/mm/yyyy')tgl_sampai, jam_sampai, penerima_nama 
            from WOS.SPTB_H a 
            inner join sptb_d2 b on a.no_sptb = b.no_sptb 
            where no_npp = '" . $no_npp . "' and app_pelanggan = '1' 
            order by to_date(tgl_sampai,'dd/mm/yyyy') desc");
        
        $data = [];
        foreach($results as $row){
            $data[] = [     
                "no_sptb" => $row->no_sptb,
                "tgl_sampai" => $row->tgl_sampai,
                "jam_sampai" => $row->jam_sampai,
                "penerima" => $row->penerima_nama
            ];
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }

}

