<?php

namespace App\Http\Controllers\Verifikasi;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\Driver;
use App\Models\TrMaterial;
use Carbon\Carbon;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    public function index(){
        return view('pages.verifikasi.history.index');
    }

    public function data()
    {
        $query = Armada::with('driver', 'vendor')->whereNotIn('v_status', ['unverified'])->select('tms_armadas.*');

        return DataTables::eloquent($query)
                ->editColumn('tgl_stnk', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->tgl_stnk)->format('d-m-y');
                })
                ->editColumn('tgl_kir_head', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->tgl_kir_head)->format('d-m-y');
                })
                ->editColumn('tgl_pajak', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->tgl_pajak)->format('d-m-y');
                })
                ->editColumn('detail', function ($model) {
                    return $model->detail;
                })
                ->addColumn('menu', function ($model) {
                    $column = '<a class="btn btn-light-dark btn-sm" href="' . route('history-armada.show', $model->id) . '">View</a>';

                    return $column;
                })
                ->rawColumns(['menu', 'v_status_label', 'status_label'])
                ->toJson();
    }

    public function show($id)
    {
        $data = Armada::with('jenis', 'driver')->find($id);
        $verify = [
            '' => 'Unverified',
            'fair' => 'Fair',
            'fit' => 'Fit',
            'deluxe' => 'Deluxe'
        ];
        $verify_ = [
            '' => 'Unverified',
            'fit' => 'Fit',
            'deluxe' => 'Deluxe'
        ];
        return view('pages.verifikasi.history.show', compact(
            'verify', 'verify_', 'data'
        ));
    }
}