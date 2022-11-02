<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\NppResource;
use App\Http\Resources\Internal\PelangganUserResource;
use App\Models\Npp;
use App\Models\PelangganNpp;
use App\Models\PelangganUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{

    public function index()
    {
        $data = PelangganUser::whereStatus('new')->get();
        
        return PelangganUserResource::collection($data);
    }

    public function nppList($pat)
    {
        $npp = Npp::whereKdPat($pat)->get();
        
        return NppResource::collection($npp);
    }

    public function approve(Request $request)
    {
        $code    = 200;
        $message = "Success";
        $data    = null;
        try {
            DB::beginTransaction();

            $user = PelangganUser::whereStatus('new')->whereId($request->user_id)->firstOrFail();
            $user->status = 'active';
            $user->save();
            foreach ($request->npp as $row) {
                $user_npp = new PelangganNpp;
                $user_npp->pelanggan_user_id = $user->id;
                $user_npp->no_npp = $row;
                $user_npp->save();
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
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

