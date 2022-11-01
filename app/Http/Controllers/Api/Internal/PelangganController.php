<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\NppResource;
use App\Http\Resources\Internal\PelangganUserResource;
use App\Models\Npp;
use App\Models\PelangganUser;

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
}

