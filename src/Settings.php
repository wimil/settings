<?php

namespace Wimil\Settings;

use Illuminate\Support\Facades\Cache;

class Settings
{
    private $model;
    private $cacheName;

    public function __construct()
    {
        $this->model = config('settings.model');
        $this->cacheName = config('settings.cacheName');
    }

    public function load()
    {
        try {
            return $this->model::all()
                ->pluck('value', 'key')
                ->map(function ($value) {
                    if ($this->isJson($value)) {
                        $value = json_decode($value, true);
                    }
                    return $value;
                })
                ->all();
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function all()
    {
        $cache = cache();
        if ($this->cacheIsTaggable()) {
            $cache->tags($this->cacheName);
        }

        return $cache->rememberForever($this->cacheName, function () {
            return $this->load();
        });
    }

    public function get($key = null)
    {
        if (!empty($key)) {
            if (is_array($key)) {
                $keys = $key;
                return array_filter($this->all(), function ($key) use ($keys) {
                    return in_array($key, $keys);
                }, ARRAY_FILTER_USE_KEY);
            }
            return data_get($this->all(), $key);
        }

        return null;
    }

    public function set($key, $value, $default = false)
    {
        if (!empty($key)) {
            $newValue = is_array($value) ? json_encode($value, true) : $value;
            try {
                $this->model::updateOrCreate(['key' => $key], ['value' => $newValue]);
                $this->flush();
                return $default ? $value : true;
            } catch (\Throwable $th) {
                return false;
            }
        }

        return null;
    }

    public function sync()
    {
        $override = config('settings.override');
        if (!empty($override)) {
            $settings = $this->all();
            foreach ($override as $configKey => $settingKey) {
                if (!empty($configKey) && !empty($settingKey)) {
                    config([$configKey => data_get($settings, $settingKey)]);
                }
            }
        }
    }

    public function flush()
    {
        if ($this->cacheIsTaggable()) {
            Cache::tags($this->cacheName)->flush();
        } else {
            Cache::forget($this->cacheName);
        }
    }
    private function isJson($str)
    {
        if ($str == '[]')
            return true;

        $json = json_decode($str);
        return $json && $str != $json;
    }

    private function cacheIsTaggable()
    {
        return method_exists(Cache::store(config('cache.default'))->getStore(), 'tags');
    }
}
