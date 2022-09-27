<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Produk;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;
use App\Models\Sp3;
use App\Models\SpmH;
use App\Models\SptbD;
use App\Models\VSpprbRi;
use App\Models\Vendor;
use App\Models\Npp;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SpmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $no_spp = SppbH::where('app2',1)->orWhere('app3',1)->get();
        return view('pages.spm.create', [
            'no_spp' => $no_spp
        ]);
    }

    public function getPbbMuat(Request $request){

        $data = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $data_1 = SpprbH::with('pat')->where('no_npp',$data->no_npp)->get();
        return response()->json($data_1);

    }

    public function getDataBox2(Request $request){

        $no_spp = $request->no_spp;

        $detail_spp = SppbD::with('produk')->where('no_sppb',$request->no_spp)->get();

        $collection_table = new Collection();
        foreach($detail_spp as $item){
            $sppdis_vol_btg = SpmH::where('no_sppb',1)->first();

            $collection_table->push((object)[
                'type_produk' => $item->produk->kd_produk.' - '.$item->produk->tipe,
                'spp_vol_btg' => $item->app2_vol,
                'spp_vol_ton' => 'aa',
                'sppdis_vol_btg' => 'bb'
            ]);
        }


        $no_npp = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $no_spprb = SpprbH::where('no_npp',$no_npp->no_npp)->first();
        $pelanggan = Npp::select('nama_pelanggan','nama_proyek')->where('no_npp',$no_npp->no_npp)->first();
        $vendor_angkutan = Vendor::where('vendor_id','LIKE','WB%')->where('sync_eproc',1)->get();
        $tujuan = Npp::with('infoPasar.region')->first();

        $kondisiPenyerahan = [
            'L' => 'LOKO',
            'F' => 'FRANKO',
            'T' => 'TERPASANG',
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($no_npp->no_npp, -1))];

        $html = view('pages.spm.box2', [
            'no_npp' => $no_npp->no_npp,
            'detail_spp' => $collection_table,
            'no_spp' => $no_spp,
            'vendor_angkutan' => $vendor_angkutan,
            'kp'=> $kondisiPenyerahanDipilih,
            'pelanggan' => $pelanggan->nama_pelanggan,
            'nama_proyek' => $pelanggan->nama_proyek
        ])->render();

        return response()->json( array('success' => true, 'html'=> $html) );
    }
}
