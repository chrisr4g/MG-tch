<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MovieGenre extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'movie_genre';
}
