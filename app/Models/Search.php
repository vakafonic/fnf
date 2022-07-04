<?php
/**
 * Created by Artdevue.
 * User: artdevue - Search.php
 * Date: 2020-02-12
 * Time: 00:05
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $table = "search";

    protected $fillable = [
        'word', 'count'
    ];

    /**
     * @param string $word
     * @return bool
     */
    public static function addWord(string $word) : bool
    {
        // Search this word in the data table
        $word_table = self::where('word', 'like', $word)->first();

        if ($word_table) {
            $word_table->increment('count');
        } else {
            // Add new word
            self::insert(compact('word'));
        }

        return true;
    }
}