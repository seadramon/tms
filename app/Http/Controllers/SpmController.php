<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Produk;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;
use App\Models\Sp3;
use App\Models\SptbD;
use App\Models\VSpprbRi;
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
        // query 
        // from SPPB_H 
        // where no_sppb=no_spp and get NO_NPP, 
        
        // then query 
        // from SPPRB_H join tb_pat on SPPRB_H.PAT_TO=tb_pat.kd_pat 
        // where NO_NPP=NO_NPP, 
        // show kd_pat and ket
        
        $data = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $data_1 = SpprbH::where()->get();
        return response()->json($data);
        // return $request->no_spp;
    }
}
