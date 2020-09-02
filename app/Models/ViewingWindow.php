<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ViewingWindow extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'viewing_windows';

    protected $primaryKey = 'id';

    protected $fillable = ['title','startDate','wayToWatch','endDate','movie_id'];

    public function movie(){
    	return $this->belongsTo('App\Models\Movie','movie_id');
    }
}
