<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Pelabuhan;
use App\Models\Vendor;
use Carbon\Carbon;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class PelabuhanController extends Controller
{
    public function index(){
        return view('pages.master.pelabuhan.index');
    }

    public function data()
    {
        $query = Pelabuhan::select('*');

        return DataTables::eloquent($query)
                ->addColumn('menu', function ($model) {
                    $column = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="' . route('master-pelabuhan.edit', $model->id) . '">Edit</a></li>
                                <li><a class="dropdown-item delete" href="javascript:void(0)" data-id="' .$model->id. '" data-toggle="tooltip" data-original-title="Delete">Delete</a></li>
                            </ul>
                            </div>';

                    return $column;
                })
                ->rawColumns(['menu'])
                ->toJson();
    }

    public function create()
    {
        // $jenisSim = [
        //     'A' => 'A',
        //     'B' => 'B',
        //     'B2' => 'B2'
        // ];
        // $status = [
        //     'aktif' => 'Aktif',
        //     'tidak_aktif' => 'Tidak Aktif'
        // ];
            
        // $jenisSim = ["" => "Pilih Jenis SIM"] + $jenisSim;
        // $status = ["" => "Pilih Status"] + $status;

        return view('pages.master.pelabuhan.create', []);
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'nama'          => 'required',
            ])->validate();

            $data = new Pelabuhan();
            $data->nama = $request->nama;
            $data->save();

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
            return redirect()->route('master-pelabuhan.index');
        } catch(Exception $e) {
            DB::rollback();
            
            $flasher->addError($e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data = Pelabuhan::find($id);

        return view('pages.master.pelabuhan.create', compact(
            'data'
        ));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'nama'          => 'required'
            ])->validate();

            $data = Pelabuhan::find($id);

            $data->nama = $request->nama;
            $data->save();

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
            return redirect()->route('master-pelabuhan.index');
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
            $data = Pelabuhan::find($request->id);
            $data->delete();

            DB::commit();

            return response()->json(['result' => 'success'])->setStatusCode(200, 'OK');
        } catch(Exception $e) {
            DB::rollback();

            return response()->json(['result' => $e->getMessage()])->setStatusCode(500, 'ERROR');
        }
    }
}