<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pelanggan\NppResource;
use App\Http\Resources\Pelanggan\PelangganResource;
use App\Models\Npp;
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
use Illuminate\Validation\ValidationException;
use Throwable;

class NppController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $npp = Npp::whereKdPat($user->pelanggan->pat_pelanggan)->get();
        
        return NppResource::collection($npp);
    }
}

