<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\NppResource;
use App\Models\Npp;

class NppController extends Controller
{

    public function index($pat)
    {
        $npp = Npp::whereKdPat($pat)->get();
        
        return NppResource::collection($npp);
    }
}

