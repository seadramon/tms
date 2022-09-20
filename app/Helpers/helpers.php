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