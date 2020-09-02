<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'videos';

    protected $primaryKey = 'id';

    protected $fillable = ['title','type','url','thumbnailUrl','thumbnailUrl_is_working','resource_is_working','movie_id'];

    public function videoAlternatives(){
    	return $this->hasMany('App\Models\VideoAlternative','video_id');
    }

    public function movie(){
    	return $this->belongsTo('App\Models\Movie','movie_id');
    }
}
