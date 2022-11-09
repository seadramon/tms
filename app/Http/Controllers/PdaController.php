<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;

use Carbon\Carbon;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Pat;
use App\Models\Npp;
use App\Models\TrMaterial;
use App\Models\Views\VPotensiMuat;
use App\Models\Views\VSpprbRi;
use App\Models\PotensiH;
use Illuminate\Support\Facades\DB;

class PdaController extends Controller
{
    public function index(){
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $labelSemua = ["" => "Semua"];

        $kd_pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['1', '4', '5'])
            ->get()
            ->pluck('singkatan', 'kd_pat')
            ->toArray();

        $kd_pat = $labelSemua + $kd_pat;

        $ppb_muat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['2', '4', '5'])
            ->get()
            ->pluck('singkatan', 'kd_pat')
            ->toArray();

        $ppb_muat = $labelSemua + $ppb_muat;
        $bulan1 = $labelSemua + ['di' => '=', 'dari' => 'Dari'];
        $bulan2 = $labelSemua + $months;
        return view('pages.potensi-detail-armada.index', [
            'kd_pat' => $kd_pat,
            'ppb_muat' => $ppb_muat,
            'bulan1' => $bulan1,
            'bulan2' => $bulan2,
        ]);
    }

    public function data(Request $request)
    {
        $query = VPotensiMuat::with('ppbmuat')->select('*');
        if($request->unitkerja != ''){
            $query->whereKdPat($request->unitkerja);
        }
        if($request->ppbmuat != ''){
            $query->wherePpbMuat($request->ppbmuat);
        }
        if($request->bulan1 != '' && $request->bulan1 != ''){
            if($request->bulan1 == 'di'){
                $start = date('Y-' . $request->bulan2 . '-01 00:00:00');
                $end = date('Y-' . $request->bulan2 . '-t 23:59:59');
            }else{
                $start = date('Y-' . $request->bulan2 . '-01 00:00:00');
                $end = date('Y-12-t 23:59:59');
            }
            $query->whereBetween('jadwal3', [$start, $end]);
        }

        return DataTables::eloquent($query)
                ->editColumn('vol_btg', function ($model) {
                    return number_format($model->vol_btg);
                })
                ->editColumn('tonase', function ($model) {
                    return number_format(round($model->tonase));
                })
                ->editColumn('jml_rit', function ($model) {
                    return number_format(round($model->jml_rit));
                })
                ->editColumn('jadwal3', function ($model) {
                    return $model->jadwal3 ? date('d-m-y', strtotime($model->jadwal3)) : '-';
                })
                ->editColumn('jadwal4', function ($model) {
                    return $model->jadwal4 ? date('d-m-y', strtotime($model->jadwal4)) : '-';
                })
                ->addColumn('rit_hari', function ($model) {
                    if($model->jadwal3 == null || $model->jadwal4 == null){
                        return 0;
                    }
                    return round($model->jml_rit / (strtotime($model->jadwal4) - strtotime($model->jadwal3)) / (3600*24), 2);
                })
                ->addColumn('status', function ($model) {
                    $column = '';

                    return $column;
                })
                ->addColumn('menu', function ($model) {
                    $column = '<a href="' . route('potensi.detail.armada.edit', ['no_npp' => $model->no_npp]) . '" class="btn btn-outline btn-sm btn-outline-dashed btn-outline-dark btn-active-light-dark">Edit</a>';

                    return $column;
                })
                ->rawColumns(['menu', 'status'])
                ->toJson();
    }

    public function create(){
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        return view('pages.potensi-detail-armada.create', ['pat' => $pat]);
    }

    public function edit($no_npp){

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

            $potensiH = PotensiH::where('no_npp',$row->no_npp)
                    ->where('pat_to', $row->ppb_muat)
                    ->first();

            $collection_table->push((object)[
                'no_npp' => $row->no_npp,
                'ppb_muat' => $row->ppb_muat,
                'vol_btg' => $row->vol_btg,
                'tonase' => $row->tonase,
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
                'destination' => $sqlNpp->kab. ',' . $sqlNpp->kec,
                'potensiH' => $potensiH
            ]);
        }


        $trmaterial = TrMaterial::where('kd_jmaterial', 'T')->get();
        // return response()->json($collection_table);
        return view('pages.potensi-detail-armada.create', ['pat' => $pat, 'muat' => $collection_table, 'trmaterial' => $trmaterial]);
    }

    public function store(Request $request){

        $i = 0;

        foreach($request->no_npp as $row){

            $data = PotensiH::where('no_npp',$request->no_npp[$i])
                        ->where('pat_to', $request->ppb_muat[$i])
                        ->first();

            if($data == null){
                $data = new PotensiH();
            }

            $data->no_npp = $request->no_npp[$i];
            $sKdMaterial = explode('|',$request->kd_material[$i]);
            $data->kd_material = $sKdMaterial[0];
            $data->jenis_armada = $sKdMaterial[1];
            $data->pat_to = $request->ppb_muat[$i];
            $data->source_lat = $request->source_lat[$i];
            $data->source_long = $request->source_long[$i];
            $data->dest_lat = $request->dest_lat[$i];
            $data->dest_long = $request->dest_long[$i];

            $ck = 'checkpoint_'.$i+1;
            $jalan = 'jalan_'.$i+1;
            $jalan2 = 'jalan2_'.$i+1;
            $jembatan = 'jembatan_'.$i+1;
            $jalan_alternatif = 'jalan_alternatif_'.$i+1;
            $jalan_alternatif2 = 'jalan_alternatif2_'.$i+1;
            $langsir = 'langsir_'.$i+1;
            $jarak_langsir = 'jarak_langsir_'.$i+1;
            $metode = 'metode_'.$i+1;

            $data->checkpoints = $request->$ck ? json_encode($request->$ck) : null;
            $data->rute = null;
            $data->jalan = $request->$jalan;
            $data->jalan2 = $request->$jalan2;
            $data->jembatan = $request->$jembatan;
            $data->jalan_alt = $request->$jalan_alternatif;
            $data->jalan_alt2 = $request->$jalan_alternatif2;
            $data->langsir = $request->$langsir;
            $data->jarak_langsir = $request->$jarak_langsir;
            $data->metode = $request->$metode;
            $data->save();
            $i++;

        }

        //  return response()->json($request);

        return redirect()->route('potensi.detail.armada.index');
        // return redirect()->route('potensi.detail.armada.edit', ['no_npp' => $request->no_npp[0]]);
    }

}
