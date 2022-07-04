<?php
/**
 * Created by Artdevue.
 * User: artdevue - Feedback.php
 * Date: 2020-02-05
 * Time: 22:59
 * Project: gamesgo.club
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';
    
    protected $fillable = [
        'email', 'message', 'view', 'created_at'
    ];
}