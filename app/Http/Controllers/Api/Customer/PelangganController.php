<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\PelangganResource;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}

