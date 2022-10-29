<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;

use Carbon\Carbon;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Session;
use Storage;
use Validator;

use App\Models\Pat;
use App\Models\Npp;
use App\Models\TrMaterial;
use App\Models\Views\VPotensiMuat;
use App\Models\Views\VSpprbRi;
use App\Models\PotensiH;


class PdaController extends Controller
{
    public function create(){
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        return view('pages.potensi-detail-armada.create', ['pat' => $pat]);
    }

    public function edit($no_npp){
        //$no_npp = '211A0009BL';
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        $muat = VPotensiMuat::with('pat')->where('no_npp',$no_npp)->get();


        $collection_table = new Collection();
        foreach($muat as $row){
            $spprbRi = VSpprbRi::
                        select('kd_produk','pat_to','no_npp','vol_spprb')
                        ->with(['produk' => function($sql){
                            $sql->select('kd_produk','tipe', 'vol_m3');
                        }])
                        ->with(['ppb_muat' =>function($sql){
                            $sql->select('kd_pat','lat_gps','lng_gps');
                        }])
                        ->where('no_npp',$row->no_npp)
                        ->where('pat_to',$row->ppb_muat)
                        ->groupBy('kd_produk','pat_to','no_npp','vol_spprb')
                        ->get();

            $sqlNpp = Npp::select('npp.nama_proyek',
                        'npp.nama_pelanggan',
                        'npp.no_npp',
                        'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                        'tb_pat.ket as pat',
                        'npp.kd_pat',
                        'tb_pat.singkatan',
                        'info_pasar_h.lat as info_pasar_lat','info_pasar_h.lang as info_pasar_long',
                        'tb_region.lat as tb_region_lat','tb_region.lang as tb_region_long')
                    ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                    ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                    ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                    ->where('npp.no_npp', $row->no_npp)
                    ->first();

            $collection_table->push((object)[
                'no_npp' => $row->no_npp,
                'vol_btg' => $row->vol_btg,
                'jadwal3' => $row->jadwal3,
                'jadwal4' => $row->jadwal4,
                'jml_rit' => $row->jml_rit,
                'pat' => $row->pat->ket,
                'jarak_km' => $row->jarak_km,
                'spprbri' => $spprbRi,
                'lat_source' => $spprbRi[0]->ppb_muat->lat_gps,
                'long_source' => $spprbRi[0]->ppb_muat->lng_gps,
                'lat_dest' => $sqlNpp->info_pasar_lat ?? $sqlNpp->tb_region_lat,
                'long_dest' => $sqlNpp->info_pasar_long ?? $sqlNpp->tb_region_long,
                'destination' => $sqlNpp->kab. ',' . $sqlNpp->kec
            ]);
        }


        $trmaterial = TrMaterial::where('kd_jmaterial','T')->get();
        // return response()->json($collection_table);
        return view('pages.potensi-detail-armada.create', ['pat' => $pat, 'muat' => $collection_table, 'trmaterial' => $trmaterial]);
    }

    public function store(Request $request){
        $data = new PotensiH();
        $data->no_npp = $request->no_npp;
        $data->kd_material = $request->kd_material;
        $data->jenis_armada = $request->jenis_armada;
        $data->pat_to = $request->pbb_muat;
        return response()->json($request);
    }

}
