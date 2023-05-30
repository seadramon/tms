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

class ArmadaController extends Controller
{
    public function index(){
        return view('pages.verifikasi.armada.index');
    }

    public function data()
    {
        $query = Armada::with('driver', 'vendor')->whereVStatus('unverified')->select('tms_armadas.*');

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
                    $column = '<a class="btn btn-light-success btn-sm" href="' . route('verifikasi-armada.verify', $model->id) . '">Verify</a>';

                    return $column;
                })
                ->rawColumns(['menu'])
                ->toJson();
    }

    public function verify($id)
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
        return view('pages.verifikasi.armada.verify', compact(
            'verify', 'verify_', 'data'
        ));
    }

    public function verified(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
            $category = ['stnk', 'kir_head', 'kir_trailer', 'pajak'];
            Validator::make($request->all(), [
                'v_stnk'        => 'required',
                'v_kir_head'    => 'required',
                'v_kir_trailer' => 'required',
                'v_pajak'       => 'required',
                'visual'        => 'required',
                'kelengkapan'   => 'required',
                'kondisi_ban'   => 'required'
            ])->validate();

            $armada = Armada::find($id);
            $armada->visual      = $request->visual;
            $armada->kelengkapan = $request->kelengkapan;
            $armada->kondisi_ban = $request->kondisi_ban;
            foreach ($category as $row) {
                $param = "v_" . $row;
                if($request->$param != ""){
                    $armada->$param = $request->$param;
                }
            }
            $armada->save();

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
            return redirect()->route('verifikasi-armada.index');
        } catch(Exception $e) {
            DB::rollback();
            
            $flasher->addError($e->getMessage());
            return redirect()->back();
        }

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = Armada::find($request->id);
            $data->delete();

            DB::commit();

            return response()->json(['result' => 'success'])->setStatusCode(200, 'OK');
        } catch(Exception $e) {
            DB::rollback();

            return response()->json(['result' => $e->getMessage()])->setStatusCode(500, 'ERROR');
        }
    }

}