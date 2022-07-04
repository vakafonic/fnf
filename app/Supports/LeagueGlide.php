<?php

namespace App\Supports;

use Illuminate\Support\Facades\Log;
//use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Storage;
use League\Glide\ServerFactory;

class LeagueGlide
{
    /**
     * @var ServerFactory
     */
    private $serverFactory;

    /**
     * LeagueGlide constructor.
     */
    public function __construct()
    {
        $this->serverFactory = ServerFactory::create(config('glide'));
    }

    public function outputImage(string $imagePath, array $params)
    {
        return $this->serverFactory->outputImage($imagePath, $params);
    }

    public function getImageAsBase64(string $imagePath, array $params)
    {
        return $this->serverFactory->getImageAsBase64($imagePath, $params);
    }

    public function makeImage(string $defaultImagePath, string $imagePath = null, array $params = [])
    {
        if (!$imagePath || $imagePath === $defaultImagePath || !is_file(public_path('/storage/images/' . $imagePath))) {
            return asset($defaultImagePath);
        }

//        if (
//            empty($params['fm'])
//            && (
//                Agent::isChrome()
//                || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)
//                || (!Agent::isSafari() && !Agent::is('Trident'))
//            )
//        ) {
//            $params['fm'] = 'webp';
//            /*$params['q']  = '90';
//            $params['bg'] = 'white';*/
//        }
        try {
            return asset('storage/images' . $this->checkSlash($this->serverFactory->makeImage($imagePath, $params)));
        } catch (\Exception $e) {
            Log::debug($e);

            return config('site.game.image_cat.default');
        }
    }

    public function checkSlash(string $string): string
    {
        $array = str_split($string);
        if ($array[0] == '/') {
            return $string;
        } else {
            return '/' . $string;
        }
    }

    public function deleteCache(string $path): bool
    {
        return $this->serverFactory->deleteCache($path);
    }
}
