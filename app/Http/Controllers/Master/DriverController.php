<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Driver;
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

class DriverController extends Controller
{
    public function index(){
        return view('pages.master.driver.index');
    }

    public function data()
    {
        $query = Driver::with('vendor')->select('*');

        return DataTables::eloquent($query)
                ->editColumn('tgl_lahir', function ($model) {
                    return Carbon::parse($model->tgl_lahir)->diff(Carbon::now())->y . ' tahun';
                })
                ->editColumn('tgl_bergabung', function ($model) {
                    return Carbon::parse($model->tgl_bergabung)->diff(Carbon::now())->y . ' tahun';
                })
                ->editColumn('sim_expired', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->sim_expired)->format('d-m-Y');
                })
                ->addColumn('menu', function ($model) {
                    $column = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="' . route('master-driver.edit', $model->id) . '">Edit</a></li>
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
        $jenisSim = [
            'A' => 'A',
            'B' => 'B',
            'B2' => 'B2'
        ];
            
        $jenisSim = ["" => "Pilih Jenis SIM"] + $jenisSim;

        return view('pages.master.driver.create', compact(
            'jenisSim'
        ));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'nama'      => 'required',
                'no_hp'     => 'required',
                'sim_jenis' => 'required',
            ])->validate();

            $driver = new Driver();
            $driver->vendor_id = 'WBP004';
            $driver->nama = $request->nama;
            $driver->tgl_lahir = Carbon::createFromFormat('d-m-Y', $request->tgl_lahir)->format('Y-m-d');
            $driver->no_hp = $request->no_hp;
            $driver->sim_jenis = $request->sim_jenis;
            $driver->sim_expired = Carbon::createFromFormat('d-m-Y', $request->sim_expired)->format('Y-m-d');
            $driver->tgl_bergabung = Carbon::createFromFormat('d-m-Y', $request->tgl_bergabung)->format('Y-m-d');
            $driver->save();

            if ($request->hasFile('foto_sim')) {
                $file = $request->file('foto_sim');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $driver->vendor_id . '/' . 'driver/' . $driver->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'sim.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $driver->sim_path = $fullPath;
                $driver->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError('An error has occurred please try again later.');
        }

        return redirect()->route('master-driver.index');
    }

    public function edit($id)
    {
        $data = Driver::find($id);

        $jenisSim = [
            'A' => 'A',
            'B' => 'B',
            'B2' => 'B2'
        ];
            
        $jenisSim = ["" => "Pilih Jenis SIM"] + $jenisSim;

        return view('pages.master.driver.create', compact(
            'data', 'jenisSim'
        ));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'nama'      => 'required',
                'no_hp'     => 'required',
                'sim_jenis' => 'required',
            ])->validate();

            $driver = Driver::find($id);

            $driver->vendor_id = 'WBP004';
            $driver->nama = $request->nama;
            $driver->tgl_lahir = Carbon::createFromFormat('d-m-Y', $request->tgl_lahir)->format('Y-m-d');
            $driver->no_hp = $request->no_hp;
            $driver->sim_jenis = $request->sim_jenis;
            $driver->sim_expired = Carbon::createFromFormat('d-m-Y', $request->sim_expired)->format('Y-m-d');
            $driver->tgl_bergabung = Carbon::createFromFormat('d-m-Y', $request->tgl_bergabung)->format('Y-m-d');
            $driver->save();

            if ($request->hasFile('foto_sim')) {
                $file = $request->file('foto_sim');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $driver->vendor_id . '/' . 'driver/' . $driver->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'sim.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $driver->sim_path = $fullPath;
                $driver->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError('An error has occurred please try again later.');
        }

        return redirect()->route('master-driver.index');
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {               
            $data = Driver::find($request->id);
            $data->delete();

            DB::commit();

            return response()->json(['result' => 'success'])->setStatusCode(200, 'OK');
        } catch(Exception $e) {
            DB::rollback();

            return response()->json(['result' => $e->getMessage()])->setStatusCode(500, 'ERROR');
        }
    }
}