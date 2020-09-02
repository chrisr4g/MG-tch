<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Director extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'directors';

    protected $primaryKey = 'id';

    protected $hidden = ['pivot'];

    protected $fillable = ['name'];
}
