<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pelanggan\PelangganResource;
use App\Models\Pelanggan;
use App\Models\PelangganUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class PelangganController extends Controller
{

    public function search(Request $request)
    {
        $perPage = !empty($request->perpage) ? $request->perpage : 20;

    	$data = Pelanggan::select('*');
        if ($request->has('search')) {
            $data->where(DB::raw("LOWER(nama)"), 'like', '%' . strtolower($request->search) . '%');
        }
        
        return PelangganResource::collection($data->paginate($perPage)->appends($request->except(['page','_token'])), "minimal");
    }

    public function register(Request $request)
    {
        $code    = 200;
        $message = "Success";
        $data    = null;

        try {
            Validator::make($request->all(), [
                'pelanggan_id' => ['required', Rule::exists('pelanggan', 'pelanggan_id')],
                'nama'         => 'required',
                'ktp'          => 'required|size:16|unique:App\Models\PelangganUser,ktp',
                'no_hp'        => 'required',
                'password'     => 'required'
            ])->validate();

            $pelanggan = new PelangganUser;
            $pelanggan->pelanggan_id = $request->pelanggan_id; 
            $pelanggan->nama         = $request->nama; 
            $pelanggan->ktp          = $request->ktp; 
            $pelanggan->no_hp        = $request->no_hp; 
            $pelanggan->jabatan      = $request->jabatan; 
            $pelanggan->password     = Hash::make($request->password); 
            if ($request->hasFile('foto')) {
			    $file = $request->file('foto');

			    $dir = 'pelanggan/' . $request->pelanggan_id;
			    cekDir($dir);

			    $filename = $request->ktp . '.jpg';
			    $fullpath = $dir .'/'. $filename;

			    Storage::disk('local')->put($fullpath, File::get($file));
                $pelanggan->foto_path = $fullpath; 
			}
            $pelanggan->save();

        } catch (Exception $e) {
            $code = 400;
            $message = $e->getMessage();
        }

        return response()->json([
            'success' => $code == 200 ? true : false,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}

