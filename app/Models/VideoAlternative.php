<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoAlternative extends Model
{
    public $timestamps = true;

    public $incrementing = true;

    protected $table = 'videos_alternatives';

    protected $primaryKey = 'id';

    protected $fillable = ['video_id','quality','url','resource_is_working'];
}
