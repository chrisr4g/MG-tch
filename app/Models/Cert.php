<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cert extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'cert';

    protected $primaryKey = 'id';

    protected $hidden = ['pivot'];

    protected $fillable = ['name'];

    public function movies(){
    	return $this->belongsToMany('App\Models\Movie','movie_cert','cert_id','movie_id');
    }
}
