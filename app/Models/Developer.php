<?php
/**
 * Created by Artdevue.
 * User: artdevue - Developer.php
 * Date: 2020-01-05
 * Time: 01:57
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Developer extends Model
{
    protected $fillable = [
        'ur', 'name', 'created_by', 'updated_by'
    ];

    /**
     * Set the developer's alias.
     *
     * @param string $value
     * @return void
     */
    public function setUrlAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['url'] = Str::slug($value);
        }
    }
}