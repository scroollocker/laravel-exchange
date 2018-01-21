<?php

if (!function_exists('assetV')) {
    function assetV($path, $secure = null)
    {
        $filename = public_path($path);
        $timestamp = filemtime($filename);


        if ($timestamp) {
            $path .= '?version_token='.$timestamp;
        }

        return asset($path, $secure);
    }
}