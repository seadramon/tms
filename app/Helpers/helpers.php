<?php

if (!function_exists('nominal')) {
    function nominal($nominal){
        return number_format($nominal, 2);
    }
}