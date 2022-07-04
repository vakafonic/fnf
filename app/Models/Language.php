<?php
/**
 * Created by Artdevue.
 * User: artdevue - Language.php
 * Date: 2019-12-23
 * Time: 12:32
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    //protected $table = 'languages';

    protected $fillable = [
        'name', 'code', 'locale', 'status', 'sort', 'main', 'created_by', 'updated_by'
    ];

    /**
     * @param null $value string
     * @return Language|Model|null|string
     */
    public static function getMain($value = null)
    {
        $obj = self::where('main', 1);
        if (is_null($value)) {
            return $obj->first();
        }

        return $obj->value($value);
    }

    /**
     * Return count of active lang
     * @return int
     */
    public static function getCountActive() : int
    {
        return self::whereStatus(1)->count();
    }
}