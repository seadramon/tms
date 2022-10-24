<?php

namespace App\Http\Controllers;

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
        $trmaterial = TrMaterial::where('kd_jmaterial','T')->get();
        // return response()->json($muat);
        return view('pages.potensi-detail-armada.create', ['pat' => $pat, 'muat' => $muat, 'trmaterial' => $trmaterial]);
    }

}
