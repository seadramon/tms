<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\SptbListResource;
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

class PelangganController extends Controller
{

    public function search(Request $request)
    {
        $perPage = !empty($request->perpage) ? $request->perpage : 20;

    	$data = Pelanggan::select('*');
        if ($request->has('search')) {
            $data->where(DB::raw("LOWER(nama)"), 'like', '%' . strtolower($request->search) . '%');
        }
        
        return PelangganResource::collection($data->paginate($perPage)->appends($request->except(['page','_token'])), "minimal");
    }

    public function register(Request $request)
    {
        $code    = 200;
        $message = "Success";
        $data    = null;

        try {
            Validator::make($request->all(), [
                'pelanggan_id' => ['required', Rule::exists('pelanggan', 'pelanggan_id')],
                'nama'         => 'required',
                'ktp'          => 'required|size:16|unique:App\Models\PelangganUser,ktp',
                'no_hp'        => 'required|starts_with:08|min:8|max:12',
                'password'     => 'required'
            ])->validate();

            $pelanggan = new PelangganUser;
            $pelanggan->pelanggan_id = $request->pelanggan_id; 
            $pelanggan->nama         = $request->nama; 
            $pelanggan->ktp          = $request->ktp; 
            $pelanggan->no_hp        = $request->no_hp; 
            $pelanggan->jabatan      = $request->jabatan; 
            $pelanggan->password     = Hash::make($request->password); 
            if ($request->hasFile('foto')) {
			    $file = $request->file('foto');

			    $dir = 'pelanggan/' . $request->pelanggan_id;
			    cekDir($dir);

			    $filename = $request->ktp . '.jpg';
			    $fullpath = $dir .'/'. $filename;

			    Storage::disk('local')->put($fullpath, File::get($file));
                $pelanggan->foto_path = $fullpath; 
			}
            $pelanggan->save();

        } catch (Exception $e) {
            $code = 400;
            $message = $e->getMessage();
        }

        return response()->json([
            'success' => $code == 200 ? true : false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function login(Request $request)
    {
        $code    = 200;
        $message = "Success";
        $data    = null;

        try {
            Validator::make($request->all(), [
                'no_hp'    => 'required|starts_with:08|min:8|max:12',
                'password' => 'required'
            ])->validate();
            $user = PelangganUser::with('pelanggan')->where('no_hp', $request->no_hp)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'no_hp' => ['The provided credentials are incorrect.'],
                ]);
            }
            $data['token'] = $user->createToken('mobile')->plainTextToken;
            $data['user'] = $user;
        } catch (Exception $e) {
            $code = 400;
            $message = $e->getMessage();
        }

        return response()->json([
            'success' => $code == 200 ? true : false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function login1(Request $request)
    {
        return response()->json($request->user());
    }

    public function daily(Request $request)
    {
        if($request->type == 'sptb'){
            $temp = (new KalenderService($request->start, $request->end))->rekapDailySptb($request->nohp, 'pelanggan');
            $color = [
                'sptb_on_progress' => '#8fbea5'
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
            ->whereHas('pelanggan_npp.pelanggan_user', function($sql) use($request) {
                $sql->where('no_hp', $request->nohp);
            })
            ->where('app_pelanggan', '<>', '1')
            ->get();
		
        return SptbListResource::collection($sptb);
    }

    public function produkDetail(Request $request)
    {
        $data = [];
        $sptb = SptbH::with('sptbd2.produk')->whereNoSptb($request->param1)->first();
        // $data = DB::select("select a.no_sptb,to_char(a.tgl_sptb,'dd/mm/yyyy')tgl_sptb, c.tipe, b.stockid, b.status, b.kd_produk,to_char(b.tgl_produksi,'dd/mm/yyyy')tgl_produksi, b.path_produk_rusak, b.keterangan
        // from sptb_h a
        // inner join sptb_d2 b on a.no_sptb=b.no_sptb
        // inner join tb_produk c on b.kd_produk = c.kd_produk
        // where a.no_sptb='$request->param1'");
        $items = [];
        foreach ($sptb->sptbd2 as $row) {
            $items[] = [
                "tipe" => $row->produk->tipe,
                "kd_produk" => $row->kd_produk,
                "stockid" => $row->stockid,
                "status" => $row->status,
                "keterangan" => $row->keterangan,
                "foto_kerusakan" => str_contains($row->path_produk_rusak, 'https://api.wika-beton.co.id/web-service/wton-mobile/shared/foto_produk_Rusak') ? $row->path_produk_rusak : full_url_from_path($row->path_produk_rusak ?? 'penerima_ttd.jpg'),
                "tgl_produksi" => $row->tgl_produksi ? date('d/m/Y', strtotime($row->tgl_produksi)) : ''
            ];
        }
        $data =$items;

        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }

    public function produkKonfirmasi(Request $request)
    {
        $code    = 200;
        $message = "Berhasil update status produk";
        $data    = null;

        try {
            $sptbd2 = SptbD2::whereStockid($request->stockid)->first();
            $sptbd2->status = $request->status;
            $sptbd2->keterangan = $request->keterangan;
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $dir = 'produk_rusak/' . $request->stockid;
                cekDir($dir);

                $filename = strtotime(now()) . '.jpg';
                $fullpath = $dir .'/'. $filename;

                Storage::disk('local')->put($fullpath, File::get($file));
                $sptbd2->path_produk_rusak = $fullpath; 
            }
            $sptbd2->save();
        } catch (Exception $e) {
            $code = 400;
            $message = $e->getMessage();
        }

        return response()->json([
            'success' => $code == 200 ? true : false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function progressProyek($no_hp)
    {
        $sql = "select distinct a.no_npp, e.nama_proyek, d.alamat_proyek, to_char(d.renc_pelaksanaan_1,'dd-mm-yyyy')||'/'||to_char(d.renc_pelaksanaan_2,'dd-mm-yyyy') as rencana_pelaksanaan 
            from tms_pelanggan_npps a
            inner join tms_pelanggan_users f on a.pelanggan_user_id = f.id
            inner join wos.spnpp b on a.no_npp = b.no_npp
            inner join wos.k_pesanan_d c on b.no_konfirmasi = c.no_konfirmasi
            inner join wos.npp e on a.no_npp = e.no_npp
            inner join wos.info_pasar_h d on e.NO_INFO = d.no_info  
            where f.no_hp = '".$no_hp."'";

        $results = DB::select("select distinct a.no_npp, e.nama_proyek, d.alamat_proyek, to_char(d.renc_pelaksanaan_1,'dd-mm-yyyy')||'/'||to_char(d.renc_pelaksanaan_2,'dd-mm-yyyy') as rencana_pelaksanaan 
            from tms_pelanggan_npps a
            inner join tms_pelanggan_users f on a.pelanggan_user_id = f.id
            inner join wos.spnpp b on a.no_npp = b.no_npp
            inner join wos.k_pesanan_d c on b.no_konfirmasi = c.no_konfirmasi
            inner join wos.npp e on a.no_npp = e.no_npp
            inner join wos.info_pasar_h d on e.NO_INFO = d.no_info  
            where f.no_hp = '" . $no_hp . "'");
        
        $data = [];
        foreach($results as $row){
            $data[] = [     
                "no_npp" => $row->no_npp,
                "nama_proyek" => $row->nama_proyek,
                "alamat_proyek" => $row->alamat_proyek,
                "rencana_pelaksanaan" => $row->rencana_pelaksanaan
            ];
        }
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }
    
    public function totalPengiriman(Request $request)
    {
        $sql = "select count(no_sptb)jumlah_sptb
        from sptb_h a
        inner join usradm.usr_pelanggan_d e on a.no_npp = e.no_npp                                        
        where trunc(a.tgl_berangkat) = to_date('".$request->curr_date."','dd/mm/yyyy') and app_pelanggan = 0 and e.no_hp ='".$request->no_hp."'";

        $start = date('d/m/Y', strtotime("-7 days " . $request->curr_date));
        $end = date('d/m/Y', strtotime($request->curr_date));
        $results = DB::select("select count(no_sptb) as jumlah_sptb
            from sptb_h a
            inner join tms_pelanggan_npps e on a.no_npp = e.no_npp   
            inner join tms_pelanggan_users f on e.pelanggan_user_id = f.id                                  
            where trunc(a.tgl_berangkat) between to_date('".$start."','dd/mm/yyyy') and to_date('".$end."','dd/mm/yyyy') and app_pelanggan = 0 and f.no_hp ='".$request->no_hp."'");
        
        $data = [
            'jumlah_sptb' => $results[0]->jumlah_sptb
        ];
        return response()->json([
            'message' => 'success',
            'data' => $data
        ])->setStatusCode(200, 'OK');
    }

}

