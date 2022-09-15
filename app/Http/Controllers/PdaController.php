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


class PdaController extends Controller
{
    public function create(){
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        return view('pages.potensi-detail-armada.create', ['pat' => $pat]);
    }

}