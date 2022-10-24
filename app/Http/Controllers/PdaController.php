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
use App\Models\TrMaterial;
use App\Models\Views\VPotensiMuat;
use App\Models\Views\VSpprbRi;


class PdaController extends Controller
{
    public function create(){
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        return view('pages.potensi-detail-armada.create', ['pat' => $pat]);
    }

    public function edit($no_npp){
        $no_npp = '211A0009BL';
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        $muat = VPotensiMuat::with('pat')->where('no_npp',$no_npp)->get();

        // query from v_spprb_ri
        // where no_npp = no_npp and pat_to = v_potensi_muat.ppb_muat
        // join to tb_produk using kd_produk
        // groupby spprb_d.kd_produk, tb_produk.tipe

        $collection_table = new Collection();
        foreach($muat as $row){
            $spprbRi = VSpprbRi::with('produk')
                        ->where('no_npp',$row->no_npp)
                        ->where('pat_to',$row->ppb_muat)
                       // ->groupBy('spprb_d.kd_produk','tb_produk.tipe')
                        ->get();

            $collection_table->push((object)[
                'no_npp' => $row->no_npp,
                'vol_btg' => $row->vol_btg,
                'jadwal3' => $row->jadwal3,
                'jadwal4' => $row->jadwal4,
                'jml_rit' => $row->jml_rit,
                'pat' => $row->pat->ket,
                'jarak_km' => $row->jarak_km,
                'spprbri' => $spprbRi
            ]);
        }


        $trmaterial = TrMaterial::where('kd_jmaterial','T')->get();
        // return response()->json($muat);
        return view('pages.potensi-detail-armada.create', ['pat' => $pat, 'muat' => $collection_table, 'trmaterial' => $trmaterial]);
    }

}
