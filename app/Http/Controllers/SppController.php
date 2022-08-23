<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;
use App\Models\Sp3;
use App\Models\SptbD;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use DB;
use Session;
use Validator;
use Storage;

class SppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.spp.index');
    }

    public function data()
    {
        $query = SppbH::with(['spprb', 'detail'])->select('*');

        return DataTables::eloquent($query)
            ->editColumn('jadwal1', function ($model) {
                return date('d-m-Y', strtotime($model->jadwal1));
            })
            ->editColumn('jadwal2', function ($model) {
                return date('d-m-Y', strtotime($model->jadwal2));
            })
            ->addColumn('no_sp3', function($model) {
                $nosp3 = "";

                if (!empty($model->spprb)) {
                    $temp = Sp3::where('no_npp', $model->spprb->no_npp)->first();

                    if ($temp) {
                        $nosp3 = $temp->no_sp3;
                    }
                }

                return $nosp3;
            })
            ->addColumn('waktu', function($model) {
                $ret = 0;
                if (!empty($model->jadwal1) && !empty($model->jadwal2)) {
                    $a = $this->diffDate($model->jadwal1, date('d-m-Y'));
                    $b = $this->diffDate($model->jadwal1, $model->jadwal2);

                    if ($b > 0) {
                        $ret = round(($a / $b) * 100);
                    }
                }

                return $ret;
            })
            ->addColumn('vol', function($model) {
                $res = "";

                $sptb = SptbD::where('no_spprb', $model->no_spprb)->sum('vol');
                $sppb = $model->detail->sum('vol');

                if ($sppb > 0) {
                    $res = $sptb / $sppb;
                }

                return $res;
            })
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
            ->rawColumns(['menu', 'waktu'])
            ->toJson();
    }

    private function diffDate($date1, $date2)
    {
        $j1 = date_create($date1);
        $j2 = date_create($date2);
        $interval = date_diff($j1, $j2);

        $ret = $interval->format('%d');

        return $ret;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
