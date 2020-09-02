<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class KeyArtImage extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'key_art_images';

    protected $primaryKey = 'id';

    protected $fillable = ['url_local', 'url_remote', 'width', 'height', 'movie_id'];

    public function movie(){
    	return $this->belongsTo('App\Models\Movie','movie_id');
    }
}
