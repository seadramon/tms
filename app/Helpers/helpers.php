<?php

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