<?php

if (!function_exists('settings')) {

    function settings($key = null, $value = null, bool $default = true)
    {

        if (empty($key))
            return app('settings')->all();

        if (!empty($key) && !is_null($value))
            return app('settings')->set($key, $value, $default);

        return app('settings')->get($key);
    }
}
