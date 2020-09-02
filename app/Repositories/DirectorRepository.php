<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\Director;

class DirectorRepository{
	
	public function addDirectors(array $movie, Movie $uMovie):void{
		if ( array_key_exists('directors', $movie) ) {

			$directorIDs = [];
            array_walk($movie['directors'], function ($director, $key) use ($uMovie,&$directorIDs) {
                $iDirector = Director::updateOrCreate(
                    ['name' => $director['name']]
                );
                $directorIDs[] = $iDirector->id;
            });

            $uMovie->directors()->sync($directorIDs);
        }
	}

}