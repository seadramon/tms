<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\Driver;
use Carbon\Carbon;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ArmadaController extends Controller
{
    public function index(){
        return view('pages.master.armada.index');
    }

    public function data()
    {
        $query = Armada::with('driver')->select('tms_armadas.*');

        return DataTables::eloquent($query)
                ->editColumn('tgl_stnk', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->tgl_stnk)->format('d-m-Y');
                })
                ->editColumn('tgl_kir_head', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->tgl_kir_head)->format('d-m-Y');
                })
                ->editColumn('tgl_pajak', function ($model) {
                    return Carbon::createFromFormat('Y-m-d h:i:s', $model->tgl_pajak)->format('d-m-Y');
                })
                ->addColumn('menu', function ($model) {
                    $column = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Menu
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="' . route('master-armada.edit', $model->id) . '">Edit</a></li>
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
        $jenis = [
            'trailer'       => 'Trailer',
            'dump-truck'    => 'Dump-Truck',
        ];
            
        $jenis = ["" => "Pilih Jenis Armada"] + $jenis;

        $detail = [
            'flatbed-panjang-12-meter'  => 'Flatbed Panjang 12 Meter',
            'flatbed-panjang-6-meter'   => 'Flatbed Panjang 6 Meter',
        ];
            
        $detail = ["" => "Pilih Jenis Flatbed"] + $detail;
        
        $tahun = [];

        $rentangTahun = date('Y') - 10;
        
        for($i=0; $i<10; $i++){
            $tahun[$rentangTahun + $i] = $rentangTahun + $i;
        }
        
        $tahun = ["" => "Pilih Tahun Pembuatan"] + $tahun;
        
        $status = [
            'aktif'     => 'Aktif',
            'muat'      => 'Muat',
            'storing'   => 'Storing',
        ];
            
        $status = ["" => "Pilih Status"] + $status;

        $driver = Driver::where('vendor_id', 'WBP004')
            ->get()
            ->pluck('nama', 'id')
            ->toArray();

        $driver = ["" => "Pilih Driver"] + $driver;

        return view('pages.master.armada.create', compact(
            'jenis', 'detail', 'tahun', 'status', 'driver'
        ));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'jenis'             => 'required',
                'detail'            => 'required',
                'tahun'             => 'required',
                'nopol'             => 'required',
                'status'            => 'required',
                'driver_id'         => 'required',
                'tgl_stnk'          => 'required',
                'tgl_kir_head'      => 'required',
                'tgl_kir_trailer'   => 'required',
                'tgl_pajak'         => 'required',
            ])->validate();

            $armada = new Armada();
            $armada->vendor_id = 'WBP004';
            $armada->jenis = $request->jenis;
            $armada->detail = $request->detail;
            $armada->tahun = $request->tahun;
            $armada->nopol = $request->nopol;
            $armada->status = $request->status;
            $armada->driver_id = $request->driver_id;
            $armada->tgl_stnk = Carbon::createFromFormat('d-m-Y', $request->tgl_stnk)->format('Y-m-d');
            $armada->tgl_kir_head = Carbon::createFromFormat('d-m-Y', $request->tgl_kir_head)->format('Y-m-d');
            $armada->tgl_kir_trailer = Carbon::createFromFormat('d-m-Y', $request->tgl_kir_trailer)->format('Y-m-d');
            $armada->tgl_pajak = Carbon::createFromFormat('d-m-Y', $request->tgl_pajak)->format('Y-m-d');
            $armada->save();

            if ($request->hasFile('foto_stnk')) {
                $file = $request->file('foto_stnk');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'stnk.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_stnk = $fullPath;
                $armada->save();
            }

            if ($request->hasFile('foto_kir_head')) {
                $file = $request->file('foto_kir_head');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'kir_head.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_kir_head = $fullPath;
                $armada->save();
            }

            if ($request->hasFile('foto_kir_trailer')) {
                $file = $request->file('foto_kir_trailer');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'kir_trailer.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_kir_trailer = $fullPath;
                $armada->save();
            }

            if ($request->hasFile('foto_pajak')) {
                $file = $request->file('foto_pajak');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'pajak.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_pajak = $fullPath;
                $armada->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError('An error has occurred please try again later.');
        }

        return redirect()->route('master-armada.index');
    }

    public function edit($id)
    {
        $data = Armada::find($id);

        $jenis = [
            'trailer'       => 'Trailer',
            'dump-truck'    => 'Dump-Truck',
        ];
            
        $jenis = ["" => "Pilih Jenis Armada"] + $jenis;

        $detail = [
            'flatbed-panjang-12-meter'  => 'Flatbed Panjang 12 Meter',
            'flatbed-panjang-6-meter'   => 'Flatbed Panjang 6 Meter',
        ];
            
        $detail = ["" => "Pilih Jenis Flatbed"] + $detail;
        
        $tahun = [];

        $rentangTahun = date('Y') - 10;
        
        for($i=0; $i<10; $i++){
            $tahun[$rentangTahun + $i] = $rentangTahun + $i;
        }
        
        $tahun = ["" => "Pilih Tahun Pembuatan"] + $tahun;
        
        $status = [
            'aktif'     => 'Aktif',
            'muat'      => 'Muat',
            'storing'   => 'Storing',
        ];
            
        $status = ["" => "Pilih Status"] + $status;

        $driver = Driver::where('vendor_id', 'WBP004')
            ->get()
            ->pluck('nama', 'id')
            ->toArray();

        $driver = ["" => "Pilih Driver"] + $driver;

        return view('pages.master.armada.create', compact(
            'jenis', 'detail', 'tahun', 'status', 'driver', 'data'
        ));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'jenis'             => 'required',
                'detail'            => 'required',
                'tahun'             => 'required',
                'nopol'             => 'required',
                'status'            => 'required',
                'driver_id'         => 'required',
                'tgl_stnk'          => 'required',
                'tgl_kir_head'      => 'required',
                'tgl_kir_trailer'   => 'required',
                'tgl_pajak'         => 'required',
            ])->validate();

            $armada = Armada::find($id);
            $armada->vendor_id = 'WBP004';
            $armada->jenis = $request->jenis;
            $armada->detail = $request->detail;
            $armada->tahun = $request->tahun;
            $armada->nopol = $request->nopol;
            $armada->status = $request->status;
            $armada->driver_id = $request->driver_id;
            $armada->tgl_stnk = Carbon::createFromFormat('d-m-Y', $request->tgl_stnk)->format('Y-m-d');
            $armada->tgl_kir_head = Carbon::createFromFormat('d-m-Y', $request->tgl_kir_head)->format('Y-m-d');
            $armada->tgl_kir_trailer = Carbon::createFromFormat('d-m-Y', $request->tgl_kir_trailer)->format('Y-m-d');
            $armada->tgl_pajak = Carbon::createFromFormat('d-m-Y', $request->tgl_pajak)->format('Y-m-d');
            $armada->save();

            if ($request->hasFile('foto_stnk')) {
                $file = $request->file('foto_stnk');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'stnk.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_stnk = $fullPath;
                $armada->save();
            }

            if ($request->hasFile('foto_kir_head')) {
                $file = $request->file('foto_kir_head');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'kir_head.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_kir_head = $fullPath;
                $armada->save();
            }

            if ($request->hasFile('foto_kir_trailer')) {
                $file = $request->file('foto_kir_trailer');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'kir_trailer.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_kir_trailer = $fullPath;
                $armada->save();
            }

            if ($request->hasFile('foto_pajak')) {
                $file = $request->file('foto_pajak');
			    $extension = $file->getClientOriginalExtension();

                $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                if (!Storage::disk('local')->exists($dir)) {
                    Storage::disk('local')->makeDirectory($dir, 0777, true);
                }

                $fileName = 'pajak.' . $extension;
			    $fullPath = $dir .'/'. $fileName;

                Storage::disk('local')->put($fullPath, File::get($file));

			    $armada->foto_pajak = $fullPath;
                $armada->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError('An error has occurred please try again later.');
        }

        return redirect()->route('master-armada.index');
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