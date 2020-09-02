<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Movie extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'movies';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'body',
        'duration',
        'headline',
        'lastUpdated',
        'quote',
        'rating',
        'reviewAuthor',
        'skyGoId',
        'skyGoUrl',
        'sum',
        'synopsis',
        'url',
        'class',
        'year'
    ];


    public function cast(){
    	return $this->belongsToMany('App\Models\Cast','movie_cast','movie_id','cast_id');
    }

    public function directors(){
    	return $this->belongsToMany('App\Models\Director','movie_director','movie_id','director_id');
    }

    public function cert(){
        return $this->belongsToMany('App\Models\Cert','movie_cert','movie_id','cert_id');
    }
    
    public function genre(){
        return $this->belongsToMany('App\Models\Genre','movie_genre','movie_id','genre_id')->select(['name']);
    }

    public function cardImages(){
    	return $this->hasMany('App\Models\CardImage','movie_id');
    }
    
    public function keyArtImages(){
    	return $this->hasMany('App\Models\KeyArtImage','movie_id');
    }

    public function viewingWindows(){
    	return $this->hasMany('App\Models\ViewingWindow', 'movie_id');
    }

    public function videos(){
        return $this->hasMany('App\Models\Video','movie_id');
    }

    public function videoAlternatives(){
        return $this->hasManyThrough('App\Models\VideoAlternative','App\Models\Video', 'movie_id', 'video_id', 'id', 'id');
    }
}
