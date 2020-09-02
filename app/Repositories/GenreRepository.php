<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\Genre;

class GenreRepository{
	
	public function addGenres(array $movie, Movie $uMovie):void{
		if ( array_key_exists('genres', $movie) ) {

			$genreIDs = [];

            array_walk($movie['genres'], function ($genre, $key) use ($uMovie,&$genreIDs) {
                
                $sDelimterFound = false;
                $delimiters = ['&', '/'];

		        foreach ($delimiters as $delimiter){
		            if(strpos($genre, $delimiter) !== false){
		                $sDelimterFound = $delimiter;
		            }
		        }

		        if($sDelimterFound){
		            $genresParsed = explode($sDelimterFound, $genre);
		            foreach ($genresParsed as $genreParsed){
		                $iGenre = Genre::updateOrCreate(
		                    ['name' => trim($genreParsed)]
		                );
		                $genreIDs[] = $iGenre->id;

		            }
		        }else{
                    $iGenre = Genre::updateOrCreate(
                        ['name' => trim($genre)]
                    );
                    $genreIDs[] = $iGenre->id;
		        }
            });

            $uMovie->genre()->sync($genreIDs);
        }
	}

}