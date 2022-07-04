<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function getCroppedImageUrl(array $params, string $imgPath, string $defaultImgPath)
    {
        return !empty($imgPath)
            ? Storage::disk('images')->url(str_replace('.', '-' . $params['w'] . 'x' . $params['h'] . '.', $imgPath))
            : asset($defaultImgPath);
    }
}
