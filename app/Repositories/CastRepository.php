<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\Cast;

class CastRepository{
	
	public function addCast(array $movie, Movie $uMovie):void{
		if ( array_key_exists('cast', $movie) ) {

			$castIDs = [];
            array_walk($movie['cast'], function ($cast, $key) use ($uMovie,&$castIDs) {
                $iCast = Cast::updateOrCreate(
                    ['name' => $cast['name']]
                );
                $castIDs[] = $iCast->id;
            });

            $uMovie->cast()->sync($castIDs);
        }
	}

}