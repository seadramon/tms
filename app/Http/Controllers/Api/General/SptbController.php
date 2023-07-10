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

    public function proyekProgress($no_npp)
    {
        $sql = "select no_npp, sum(VOL_OK)VOL_OK,sum(VOL_ESPTB_DITERIMA)VOL_ESPTB_DITERIMA, sum(VOL_SPTB)VOL_SPTB,sum(STOCK_PPB)STOCK_PPB, sum(STOCK_PROD)STOCK_PROD from(
            select NO_NPP,kd_produk_konfirmasi,tipe,max(VOL_OK)VOL_OK,max(VOL_OP)VOL_OP,sum(VOL_SPTB_DITERIMA)VOL_ESPTB_DITERIMA,
            max(VOL_SPTB)VOL_SPTB,max(STOCK_PPB)STOCK_PPB ,max(STOCK_PROD)STOCK_PROD   from (
              SELECT S1.NO_NPP,S1.kd_produk_konfirmasi ,s4.tipe,S1.VOL_KONFIRMASI VOL_OK, 
              S2.VOL_OP,
              case app_pelanggan when 1 then S3.VOL_SPTB  else 0 end as VOL_SPTB_DITERIMA, 
               case app_pelanggan when 0 then S3.VOL_SPTB  else 0 end as VOL_SPTB,
               pkg_snop.getStockByNPP_PROD(S1.NO_NPP,S1.kd_produk_konfirmasi, to_char(sysdate,'dd/mm/yyyy'),1)STOCK_PPB ,
                pkg_snop.getStockByNPP_PROD(S1.NO_NPP,S1.kd_produk_konfirmasi, to_char(sysdate,'dd/mm/yyyy'),3) 
                + pkg_snop.getStockByNPP_PROD(S1.NO_NPP,S1.kd_produk_konfirmasi, to_char(sysdate,'dd/mm/yyyy'),4) STOCK_PROD
               FROM MON_OP S1 
              LEFT JOIN 
                   (SELECT NO_NPP,KD_PRODUK,sum(vol_bast)vol_op, SUM(JML_PENGESAHAN)JML_PENGESAHAN FROM MON_OP_D  GROUP BY NO_NPP,KD_PRODUK)S2
                    ON S1.NO_NPP=S2.NO_NPP AND S1.kd_produk_konfirmasi=S2.KD_PRODUK
              LEFT JOIN (
              select a.no_npp,c.kd_produk,nvl(b.app_pelanggan,0)app_pelanggan,nvl(sum(c.vol),0)VOL_SPTB
              from spprb_h a 
               inner join sptb_h b on a.no_spprb=b.no_spprb
               inner join sptb_d c on b.no_sptb=c.no_sptb
               group by a.no_npp,c.kd_produk,b.app_pelanggan
              )    S3 on S1.no_npp=S3.no_npp and S1.kd_produk_konfirmasi like substr(S3.kd_produk,1,6)||'_'||  substr(S3.kd_produk,-3)  
              inner join tb_produk S4 on  S1.kd_produk_konfirmasi=S4.kd_produk 
                   WHERE  S1.no_npp='".$no_npp."')src      
                   group by NO_NPP,kd_produk_konfirmasi,tipe)total
                  group by total.no_npp";
        $results = DB::select($sql);
        
        $data = [];
        foreach($results as $row){
            $data[] = [
                //"KD_PRODUK"=>$rst->fields["KD_PRODUK_KONFIRMASI"],      
               "pesanan"=> $row->vol_ok,
               "delivery"=> $row->vol_sptb > $row->vol_ok ? $row->vol_ok : $row->vol_sptb,
               "tot_prod"=> $row->stock_prod > $row->vol_ok ? $row->vol_ok : $row->stock_prod,
               "distribusi"=> $row->vol_esptb_diterima > $row->vol_ok ? $row->vol_ok : $row->vol_esptb_diterima,
            ];
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }

    public function proyekProgressTipe($no_npp)
    {
        $sql = "select NO_NPP,kd_produk_konfirmasi,tipe,max(VOL_OK)VOL_OK,max(VOL_OP)VOL_OP,sum(VOL_SPTB_DITERIMA)VOL_ESPTB_DITERIMA,
			 max(VOL_SPTB)VOL_SPTB,max(STOCK_PPB)STOCK_PPB ,max(STOCK_PROD)STOCK_PROD   from (
			   SELECT S1.NO_NPP,S1.kd_produk_konfirmasi ,s4.tipe,S1.VOL_KONFIRMASI VOL_OK, 
			   S2.VOL_OP,
			   case app_pelanggan when 1 then S3.VOL_SPTB  else 0 end as VOL_SPTB_DITERIMA, 
			    case app_pelanggan when 0 then S3.VOL_SPTB  else 0 end as VOL_SPTB,
			    pkg_snop.getStockByNPP_PROD(S1.NO_NPP,S1.kd_produk_konfirmasi, to_char(sysdate,'dd/mm/yyyy'),1)STOCK_PPB ,
			     pkg_snop.getStockByNPP_PROD(S1.NO_NPP,S1.kd_produk_konfirmasi, to_char(sysdate,'dd/mm/yyyy'),3) 
			     + pkg_snop.getStockByNPP_PROD(S1.NO_NPP,S1.kd_produk_konfirmasi, to_char(sysdate,'dd/mm/yyyy'),4) STOCK_PROD
			    FROM MON_OP S1 
			   LEFT JOIN 
			        (SELECT NO_NPP,KD_PRODUK,sum(vol_bast)vol_op, SUM(JML_PENGESAHAN)JML_PENGESAHAN FROM MON_OP_D  GROUP BY NO_NPP,KD_PRODUK)S2
			         ON S1.NO_NPP=S2.NO_NPP AND S1.kd_produk_konfirmasi=S2.KD_PRODUK
			   LEFT JOIN (
			   select a.no_npp,c.kd_produk,nvl(b.app_pelanggan,0)app_pelanggan,nvl(sum(c.vol),0)VOL_SPTB
			   from spprb_h a 
			    inner join sptb_h b on a.no_spprb=b.no_spprb
			    inner join sptb_d c on b.no_sptb=c.no_sptb
			    group by a.no_npp,c.kd_produk,b.app_pelanggan
			   )    S3 on S1.no_npp=S3.no_npp and S1.kd_produk_konfirmasi=S3.kd_produk 
			   inner join tb_produk S4 on  S1.kd_produk_konfirmasi=S4.kd_produk 
			        WHERE  S1.no_npp='".$no_npp."')src      
			        group by NO_NPP,kd_produk_konfirmasi,tipe"; 

        $results = DB::select($sql);
        
        $data = [];
        foreach($results as $row){
            $data[] = [     
               "kd_produk"=>$row->kd_produk_konfirmasi,
               "tipe"=>$row->tipe,
               "tot_pesanan"=>$row->vol_ok,
               "delivery"=>$row->vol_sptb > $row->vol_ok ? $row->vol_ok : $row->vol_sptb,
               "tot_prod"=>$row->stock_prod > $row->vol_ok ? $row->vol_ok : $row->stock_prod,
               "distribusi"=>$row->vol_esptb_diterima > $row->vol_ok ? $row->vol_ok : $row->vol_esptb_diterima
            ];
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }
}

