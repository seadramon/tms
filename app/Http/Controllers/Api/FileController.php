<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function viewer($path)
    {
        $content_type = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'pdf'  => 'application/pdf'
        ];
        $code = 404;
        $file = null;
        $size = 0;
        $ext  = 'jpg';

        if(str_contains($path, '|') || str_contains($path, '.')){
            $path_file = str_replace('|', '/', $path);
            $splitted = explode('.', $path_file);
            $ext = end($splitted);

            $cek = Storage::has($path_file);
            if($cek){
                $file = Storage::get($path_file);
                $size = Storage::size($path_file);
                $code = 200;
            }
        }else{
            $personal = Personal::select('employee_id', 'signature')->find($path);
            $file = $personal->signature;
            $size = null;
            $code = 200;
        }
        return response()->make($file, $code, ['Content-Type' => $content_type[$ext] ?? null, "Content-Length" => $size]);
        // return response()->make(Pic::find('WB980004')->signature, 200, ['Content-Type' => 'image/jpeg']);
    }
}

