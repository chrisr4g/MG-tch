<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MovieClass extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'movies_class';
}
