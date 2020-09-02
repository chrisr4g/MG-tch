<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MovieCast extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'movie_cast';
}
