<?php

use AizPackages\ColorCodeConverter\Services\ColorCodeConverter;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\Settings;
use App\Models\Uploads;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;


use function GuzzleHttp\json_decode;


if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = Uploads::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return "";
    }
}

if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if (($asset = Uploads::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return null;
    }
}

if (!function_exists('my_asset')) {
    function my_asset($path, $secure = null)
    {
        if (empty($path)) return "";

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $driver = config('filesystems.default');

        try {
            if (in_array($driver, ['s3'])) {
                return \Illuminate\Support\Facades\Storage::disk($driver)->url($path);
            }
        } catch (\Exception $e) {
        }

        return asset($path, $secure);
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        // return app('url')->asset('public/' . $path, $secure);
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';

        $host = $_SERVER['HTTP_HOST'];

        // Determine subfolder depth if app is in a subfolder
        // Strip /public from the base path entirely
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $base = str_replace('/public', '', $scriptDir);
        $base = rtrim($base, '/') . '/';

        return $scheme . '://' . $host . $base;
    }
}

if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return getBaseURL() . '/public/';
        }
    }
}


// if (!function_exists('get_setting')) {
//     function get_setting($key, $default = null)
//     {
//         $settings = Cache::remember('settings', 86400, function () {
//             return Settings::all();
//         });

//         $setting = $settings->where('type', $key)->first();

//         return $setting == null ? $default : $setting->value;
//     }
// }


if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}


if (!function_exists('cache_clear')) {
    function cache_clear()
    {
        Artisan::call('optimize:clear');
    }
}
if (!function_exists('set_cookie')) {
    function set_cookie($name, $value, $time)
    {
        $cookie = cookie('example_cookie', 'cookie_value', 60);
        return response('Cookie has been set')->cookie($cookie);
    }
}
