<?php

if (!function_exists('storagePath')) {
    function storagePath($path) {
        return __DIR__ . '/' . $path;
    }
}

if (!function_exists('ngstates')) {
    function ngstates() {
        return new Coderatio\NGStates\NGStates();
    }
}