<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Sp3;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use DB;
use Session;
use Validator;
use Storage;

class Sp3Controller extends Controller
{
    public function index(){
        $labelSemua = ["" => "Semua"];

        $pat = Pat::all()->pluck('ket', 'kd_pat')->toArray();
        $pat = $labelSemua + $pat;

        $periode = [];

        for($i=0; $i<10; $i++){
            $year = date('Y', strtotime('-' . $i . ' years'));
            $periode[$year] = $year;
        }

        $status = [
            'draft'             => 'Draft',
            'belum_verifikasi'  => 'Belum Verifikasi',
            'aktif'             => 'Aktif',
            'selesai'           => 'Selesai'
        ];

        $rangeCutOff = [
            'sd'    => 's/d',
            'di'    => 'di'
        ];

        $monthCutOff = [
            'januari'   => 'Januari',
            'februari'  => 'Februari',
            'maret'     => 'Maret',
            'april'     => 'April',
            'mei'       => 'Mei',
            'juni'      => 'Juni',
            'juli'      => 'Juli',
            'agustus'   => 'Agustus',
            'september' => 'September',
            'oktober'   => 'Oktober',
            'november'  => 'November',
            'desember'  => 'Desember'
        ];

        return view('pages.sp3.index', compact(
            'pat', 'periode', 'status', 'rangeCutOff', 'monthCutOff'
        ));
    }

    public function data()
    {
        $query = Sp3::with('vendor')->select('*');

        return DataTables::eloquent($query)
                ->addColumn('menu', function ($model) {
                    $edit = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Adendum</a></li>
                                <li><a class="dropdown-item" href="#">Approve</a></li>
                                <li><a class="dropdown-item" href="#">Print</a></li>
                                <li><a class="dropdown-item" href="#">Hapus</a></li>
                            </ul>
                            </div>';

                    return $edit;
            })
            ->rawColumns(['menu'])
            ->toJson();
    }
}