<?php

namespace App\Http\Controllers\Master;

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
use Illuminate\Support\Facades\Auth;

class ArmadaController extends Controller
{
    public function index(){
        return view('pages.master.armada.index');
    }

    public function data()
    {
        $query = Armada::with('driver')->select('tms_armadas.*');
        if(Auth::check()){
            $query->whereVendorId(Auth::user()->vendor_id);
        }

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
                ->rawColumns(['menu', 'v_status_label', 'status_label'])
                ->toJson();
    }

    public function create()
    {
        $jenis = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->mapWithKeys(function($item){
                return [$item->kd_material => $item->kd_material . ' | ' . $item->name];
            })
            ->all();
        $jenis = ["" => "Pilih Jenis Armada"] + $jenis;

        $tahun = [];
        $rentangTahun = date('Y') - 40;
        for($i=0; $i<40; $i++){
            $tahun[$rentangTahun + $i] = $rentangTahun + $i;
        }

        $tahun = ["" => "Pilih Tahun Pembuatan"] + $tahun;

        $status = [
            'aktif'     => 'Aktif',
            'muat'      => 'Muat',
            'storing'   => 'Storing',
        ];

        $status = ["" => "Pilih Status"] + $status;

        $driver = Driver::where('vendor_id', Auth::check() ? Auth::user()->vendor_id : 'WBP004')
            ->doesntHave('armada')
            ->get()
            ->pluck('nama', 'id')
            ->toArray();

        $driver = ["" => "Pilih Driver"] + $driver;

        return view('pages.master.armada.create', [
            'jenis'  => $jenis,
            'tahun'  => $tahun,
            'status' => $status,
            'driver' => $driver
        ]);
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();

            Validator::make($request->all(), [
                'kd_armada'         => 'required',
                'tahun'             => 'required',
                'nopol'             => 'required|unique:' . Armada::class . ',nopol',
                'status'            => 'required',
                'tgl_stnk'          => 'required',
                'tgl_kir_head'      => 'required',
                'tgl_kir_trailer'   => 'required',
                'tgl_pajak'         => 'required',
            ])->validate();

            $tr = TrMaterial::find($request->kd_armada);
            $armada = new Armada();
            $armada->vendor_id = Auth::user()->vendor_id ?? null;
            $armada->kd_armada = $request->kd_armada;
            $armada->detail = $tr->name;
            $armada->tahun = $request->tahun;
            $armada->nopol = $request->nopol;
            $armada->status = $request->status;
            $armada->driver_id = $request->driver_id ?? null;
            $armada->v_status = 'unverified';
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
            return redirect()->route('master-armada.index');
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError($e->getMessage(), "Validasi Error", ['timer' => 10000]);
            return redirect()->back();
        }

    }

    public function edit($id)
    {
        $data = Armada::find($id);

        $jenis = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->mapWithKeys(function($item){
                return [$item->kd_material => $item->kd_material . ' | ' . $item->name];
            })
            ->all();
        $jenis = ["" => "Pilih Jenis Armada"] + $jenis;

        $tahun = [];

        $rentangTahun = date('Y') - 40;

        for($i=0; $i<40; $i++){
            $tahun[$rentangTahun + $i] = $rentangTahun + $i;
        }

        $tahun = ["" => "Pilih Tahun Pembuatan"] + $tahun;

        $status = [
            'aktif'     => 'Aktif',
            'muat'      => 'Muat',
            'storing'   => 'Storing',
        ];

        $status = ["" => "Pilih Status"] + $status;

        $driver = Driver::where('vendor_id', Auth::user()->vendor_id ?? null)
            ->where(function (Builder $query) use ($data) {
                $query->doesntHave('armada');
                $query->orWhere('id', $data->driver_id);
            })
            ->get()
            ->pluck('nama', 'id')
            ->toArray();

        $driver = ["" => "Pilih Driver"] + $driver;

        return view('pages.master.armada.create', compact(
            'jenis', 'tahun', 'status', 'driver', 'data'
        ));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
            $category = ['stnk', 'kir_head', 'kir_trailer', 'pajak'];
            Validator::make($request->all(), [
                'kd_armada'         => 'required',
                'tahun'             => 'required',
                'nopol'             => 'required|unique:' . Armada::class . ',nopol,' . $id,
                'status'            => 'required',
                'tgl_stnk'          => 'required',
                'tgl_kir_head'      => 'required',
                'tgl_kir_trailer'   => 'required',
                'tgl_pajak'         => 'required',
            ])->validate();

            Armada::where('driver_id', $request->driver_id)->get()->each(function($a){
                $a->driver_id = null;
                $a->save();
            });
            $tr = TrMaterial::find($request->kd_armada);
            $armada = Armada::find($id);
            $armada->vendor_id = Auth::user()->vendor_id ?? null;
            $armada->kd_armada = $request->kd_armada;
            $armada->detail = $tr->name;
            $armada->tahun = $request->tahun;
            $armada->nopol = $request->nopol;
            $armada->status = $request->status;
            $armada->driver_id = $request->driver_id ?? null;
            $armada->tgl_stnk = Carbon::createFromFormat('d-m-Y', $request->tgl_stnk)->format('Y-m-d 00:00:00');
            $armada->tgl_kir_head = Carbon::createFromFormat('d-m-Y', $request->tgl_kir_head)->format('Y-m-d 00:00:00');
            $armada->tgl_kir_trailer = Carbon::createFromFormat('d-m-Y', $request->tgl_kir_trailer)->format('Y-m-d 00:00:00');
            $armada->tgl_pajak = Carbon::createFromFormat('d-m-Y', $request->tgl_pajak)->format('Y-m-d 00:00:00');
            $armada->save();

            foreach ($category as $row) {
                $param_foto = 'foto_' . $row;
                if ($request->hasFile($param_foto)) {
                    $file = $request->file($param_foto);
                    $extension = $file->getClientOriginalExtension();

                    $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;

                    if (!Storage::disk('local')->exists($dir)) {
                        Storage::disk('local')->makeDirectory($dir, 0777, true);
                    }

                    $fileName = $row . '.' . $extension;
                    $fullPath = $dir .'/'. $fileName;

                    Storage::disk('local')->put($fullPath, File::get($file));

                    $armada->foto_stnk = $fullPath;
                }
            }
            $armada->save();

            DB::commit();
            $flasher->addSuccess('Data has been saved successfully!');
            return redirect()->route('master-armada.index');
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
