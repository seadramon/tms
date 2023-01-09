<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\LoginResource;
use App\Models\Driver;
use App\Models\GpsLog;
use App\Models\Personal;
use App\Models\SptbH;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DriverController extends Controller
{

    public function penerimaan(Request $request)
    {
        try {
			DB::beginTransaction();
            $sptb = SptbH::findOrFail($request->sptb);
            $sptb->app_pelanggan = 1;
            $sptb->tgl_sampai = date('Y-m-d H:i:s');
            $sptb->penerima_nama = $request->penerima;
            if ($request->hasFile('ttd')) {
                $file = $request->file('ttd');
                $extension = $file->getClientOriginalExtension();

                $dir = 'sptb/' . date('Ym', strtotime($sptb->tgl_sptb)) . '/' . Str::of($sptb->no_sptb)->slug('-');
                cekDir($dir);

                $filename = 'penerima_ttd.jpg';
                $fullpath = $dir .'/'. $filename;

                Storage::disk('local')->put($fullpath, File::get($file));

                $sptb->penerima_ttd = $fullpath;
            }
            $sptb->save();
            DB::commit();

            return response()->json([
                'message' => 'success',
                'data' => null
            ])->setStatusCode(200, 'OK');
        } catch(Exception $e) {
			DB::rollback();

			return response()->json([
                'message' => 'failed',
                'data' => $e->getMessage()
            ])->setStatusCode(500, 'OK');
		}
    }

    public function gpsLog(Request $request)
    {
        $gps = new GpsLog;
        $gps->no_sptb   = $request->no_sptb;
        $gps->nopol     = $request->nopol;
        $gps->latitude  = $request->latitude;
        $gps->longitude = $request->longitude;
        $gps->save();

        return response()->json([
            'message' => 'success',
            'data' => null
        ])->setStatusCode(200, 'OK');
    }

    public function sptbList(Request $request)
    {

        $data = DB::select("select distinct a.no_sptb, d.singkatan2 as sbu , to_char(tgl_berangkat,'dd/mm/yyyy')tgl_berangkat, nvl(to_char(tgl_sampai,'dd/mm/yyyy'),' ')tgl_sampai, app_pelanggan from WOS.SPTB_H a 
        inner join spprb_h b on a.no_spprb = b.no_spprb
        inner join spprb_d c on b.no_spprb = c.no_spprb
        inner join tb_sbu d on substr(c.kd_produk,1,1) = d.kd_sbu
        where no_pol ='".$request->nopol."' and app_pelanggan = '".$request->status."'
        order by app_pelanggan asc, tgl_berangkat desc");

        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }
}

