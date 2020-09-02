<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cast extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'cast';

    protected $primaryKey = 'id';

    protected $hidden = ['pivot'];

    protected $fillable = ['name'];

    public function movies(){
    	return $this->belongsToMany('App\Models\Movie','movie_cast','genre_id','movie_id');
    }
}
