<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MovieDirector extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'movie_director';
}
