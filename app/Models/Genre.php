<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'genres';

    protected $primaryKey = 'id';

    protected $hidden = ['pivot'];

    protected $fillable = ['name'];

    public function movies(){
    	return $this->belongsToMany('App\Models\Movie','movie_genre','genre_id','movie_id')->select(['name']);
    }
}
