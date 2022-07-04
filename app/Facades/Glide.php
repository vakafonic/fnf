<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Glide extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'glide';
    }
}
