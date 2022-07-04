<?php

/*use Illuminate\Http\Request;
use League\Glide\Responses\LaravelResponseFactory;*/

return [
    //'response' => new LaravelResponseFactory(new Request()),
    'source' => storage_path('app'),
    'cache' => storage_path('app/public'),
    'cache_path_prefix' => '.cache',
    'source_path_prefix' => 'public',
    'cache_with_file_extensions' => true,
//    'watermarks_path_prefix' => 'public'
];
