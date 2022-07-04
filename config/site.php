<?php
/**
 * Created by Artdevue.
 * User: artdevue - site.php
 * Date: 2020-01-09
 * Time: 22:21
 * Project: gamesgo.club
 */

return [
    'genres'          => [
        'image' => [
            'width'           => 100,
            'height'          => 100,
            'quality'         => 100,
            'watermark'       => false,
            'crop'            => false,
            'patch_watermark' => '/images/watermark_100x100.png',
            'default'         => '/images/default/default_100.jpg'
        ]
    ],
    'heroes'          => [
        'image' => [
            'width'           => 100,
            'height'          => 100,
            'quality'         => 100,
            'watermark'       => false,
            'crop'            => false,
            'patch_watermark' => '/images/watermark_100x100.png',
            'default'         => '/images/default/default_100.jpg'
        ]
    ],
    'game'            => [
        'min_description' => 100,
        'min_how_play' => 100,
        'image'     => [
            'width'           => 300,
            'height'          => 300,
            'quality'         => 100,
            'watermark'       => false,
            'crop'            => false,
            'patch_watermark' => '/images/watermark_300x300.png',
            'default'         => '/images/default/default_300.jpg'
        ],
        'image_cat' => [
            'width'           => 300,
            'height'          => 225,
            'quality'         => 100,
            'watermark'       => false,
            'crop'            => false,
            'patch_watermark' => '/images/watermark_300x225.png',
            'default'         => '/images/default/default_300x225.jpg'
        ],
        'new'       => 20, // Counts of days from publication
    ],
    'menu'            => [
        'view_categories' => 30,
        'view_games'      => 10
    ],
    'page'            => [
        'per_page' => 30,
        'series'   => ['d' => 30, 'm' => 16],
    ],
    'comments'        => [
        'per_page'   => 10,
        'interval'   => 5, // In minutes between adding a new comment
        'max_length' => 2000
    ],
    'count_favorites' => 30,
    'count_views'     => 30,
    'mail_feedback'   => 'pr.konstantin@gmail.com',
];