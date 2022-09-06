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


class PdaController extends Controller
{
    public function create(){
        return view('pages.potensi-detail-armada.create');
    }

}