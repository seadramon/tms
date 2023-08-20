<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\LoginResource;
use App\Http\Resources\Internal\SpmListResource;
use App\Http\Resources\Internal\SptbListResource;
use App\Models\Driver;
use App\Models\GpsLog;
use App\Models\Personal;
use App\Models\SpmH;
use App\Models\SpprbH;
use App\Models\SptbH;
use App\Services\KalenderService;
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
            $sptb->jam_sampai = date('H:i');
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
            if ($request->hasFile('suratjalan')) {
                $file = $request->file('suratjalan');
                $extension = $file->getClientOriginalExtension();

                $dir = 'sptb/' . date('Ym', strtotime($sptb->tgl_sptb)) . '/' . Str::of($sptb->no_sptb)->slug('-');
                cekDir($dir);

                $filename = 'suratjalan.jpg';
                $fullpath = $dir .'/'. $filename;

                Storage::disk('local')->put($fullpath, File::get($file));

                $sptb->suratjalan_path = $fullpath;
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
        $sptb = SptbH::whereNoSptb($request->no_sptb)->first();

        return response()->json([
            'message' => 'success',
            'data' => ['sendGps' => ($sptb->app_pelanggan ?? null) != 1]
        ])->setStatusCode(200, 'OK');
    }

    public function sptbList(Request $request)
    {
        // $data = SptbH::with()
        $data = DB::select("select distinct a.no_sptb, d.singkatan2 as sbu , to_char(tgl_berangkat,'dd/mm/yyyy')tgl_berangkat, nvl(to_char(tgl_sampai,'dd/mm/yyyy'),' ')tgl_sampai, app_pelanggan, e.nama_proyek, g.kabupaten_name, g.kecamatan_name, a.no_npp 
            from WOS.SPTB_H a 
            inner join spprb_h b on a.no_spprb = b.no_spprb
            inner join spprb_d c on b.no_spprb = c.no_spprb
            inner join tb_sbu d on substr(c.kd_produk,1,1) = d.kd_sbu
            inner join npp e on a.no_npp=e.no_npp
            left join info_pasar_h f on e.no_info=f.no_info
            left join tb_region g on f.kd_region=g.kd_region
            where replace(no_pol, ' ','') ='".$request->nopol."' and app_pelanggan = '".$request->status."' order by app_pelanggan asc, tgl_berangkat desc");
        
        if($request->status == 0){
            $data = collect($data)->groupBy(function($item){ return $item->no_npp . '|' . $item->tgl_berangkat; });
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }
    
    public function sptbDetail(Request $request)
    {
        $data = [];
        $sptb = SptbH::with('npp', 'sptbd.produk')->whereNoSptb($request->param1)->first();
        // $data = DB::select("select a.no_sptb,to_char(a.tgl_sptb,'dd/mm/yyyy')tgl_sptb,a.no_npp, c.nama_proyek, c.nama_pelanggan,
        // a.angkutan,no_pol,tujuan,e.kd_produk,tipe,e.vol,nvl(d.satuan,'')satuan, a.barcode_img 
        // from sptb_h a
        // inner join npp c on a.no_npp=c.no_npp 
        // inner join sptb_d e on a.no_sptb=e.no_sptb
        // inner join tb_produk d on e.kd_produk=d.kd_produk
        // where a.no_sptb='$request->param1'");
        $data["header"] = [
            'no_sptb' => $sptb->no_sptb,
            // 'tgl_sptb' => $sptb->tgl_sptb ? date('d/m/Y', strtotime($sptb->tgl_sptb)) : '',
            'tgl_sptb' => $sptb->tgl_berangkat ? date('d/m/Y', strtotime($sptb->tgl_berangkat)) : '',
            'no_npp' => $sptb->no_npp,
            'nama_proyek' => $sptb->npp->nama_proyek,
            'nama_pelanggan' => $sptb->npp->nama_pelanggan,
            'angkutan' => $sptb->angkutan,
            'no_pol' => $sptb->no_pol,
            'app_pelanggan' => $sptb->app_pelanggan,
            'tujuan' => $sptb->tujuan,
            'nama_penerima' => $sptb->penerima_nama,
            'tgl_sampai' => $sptb->tgl_sampai ? date('d/m/Y', strtotime($sptb->tgl_sampai)) : '',
            'ttd_penerima' => full_url_from_path($sptb->penerima_ttd ?? 'penerima_ttd.jpg'),
        ];
        $body = [];
        foreach ($sptb->sptbd as $row) {
            $body[] = [
                'kd_produk' => $row->kd_produk,
                'tipe' => $row->produk->tipe,
                'satuan' => $row->produk->satuan,
                'vol' => $row->vol,
            ];
        }
        $data["body"] = $body;

        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }

    public function daily(Request $request)
    {
        if($request->type == 'sptb'){
            $temp = (new KalenderService($request->start, $request->end))->rekapDailySptb($request->nopol, 'vendor');
            $color = [
                'history_sptb' => '#8fbea5'
            ];
        }
		return response()->json([
            'data' => $temp,
            'color' => $color,
        ]);
    }

    public function sptbDaily(Request $request)
    {
        $sptb = SptbH::with('npp', 'spmh', 'ppb_muat')
            ->with(['sptbd' => function($sql){
                $sql->leftJoin('tb_sbu', DB::raw('substr(sptb_d.kd_produk,1,1)'), '=', 'tb_sbu.kd_sbu');
            }])
            ->whereBetween('tgl_berangkat', [date('Y-m-d 00:00:00', strtotime($request->tgl)), date('Y-m-d 23:59:59', strtotime($request->tgl))])
            ->whereRaw("app_pelanggan = '1' and replace(no_pol, ' ','') ='" . $request->nopol . "'")
            ->get();
		
        return SptbListResource::collection($sptb);
    }

    public function spmList(Request $request)
    {
        $query = SpmH::with('sppb.npp', 'vendor', 'pat', 'spmd.sbu', 'sptbh')
            ->whereBetween('tgl_spm', [date('Y-m-d 00:00:00', strtotime($request->tgl)), date('Y-m-d 23:59:59', strtotime($request->tgl))])
            ->whereRaw("replace(no_pol, ' ','') ='" . $request->nopol . "'");

        $spm = $query->get();


        return SpmListResource::collection($spm);
    }
}

