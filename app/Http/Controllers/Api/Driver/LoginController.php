<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\LoginResource;
use App\Models\Driver;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $driver = Driver::whereHas('armada', function($sql) use ($request) {
                $sql->whereNopol($request->nopol);
            })
            ->whereNoHp($request->no_hp)
            ->firstOrFail();
        
        return new LoginResource($driver);
    }
}

