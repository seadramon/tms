<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('nominal')) {
    function nominal($nominal, $decimal = 2){
        return number_format($nominal, $decimal);
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
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];
    }
}

if (!function_exists('terbilang')) {
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }           
        return $hasil;
    }
}

if (!function_exists('penyebut')) {
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
}