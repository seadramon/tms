<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('nominal')) {
    function nominal($nominal){
        return number_format($nominal, 2);
    }
}

if (!function_exists('full_url_from_path')) {
    function full_url_from_path($path) {
        if($path == null){
            return null;
        }
        return route('api.file.viewer', ['path' => str_replace("/", "|", $path)]);;
    }
}

if (!function_exists('getNow')) {
	function getNow()
	{
	    $data = DB::select("SELECT TO_CHAR(SYSDATE, 'YYYY-MM-DD HH24:MI:SS') AS saiki FROM dual");
	    
	    return $data[0]->saiki;
	}
}

if (!function_exists('cekDir')) {
    function cekDir($dir, $disk = "local")
    {
        /*if (!\Storage::disk('sftp')->exists($dir)) {
            Storage::disk('sftp')->makeDirectory($dir);
        }*/
        
        if (!Storage::disk($disk)->exists($dir)) {
            Storage::disk($disk)->makeDirectory($dir, 0777, true);
        }
    }
}

if (!function_exists('getListBulan')) {
    function getListBulan()
    {
        return [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
    }
}