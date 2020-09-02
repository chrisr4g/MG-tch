<?php

	namespace App\Repositories;

	use App\Models\Movie;

	class MovieRepository {

		public function getData(array $rules = []):array{
	        $result = [];
			if(!empty($rules) && isset($rules['location'])){

				switch($rules['location']){
					case 'showcase' : {
						$result = $this->getShowcaseMovieData($rules['page'],$rules['itemsPerRow']);
						break;
					}

					case 'movie' : {
						$result = $this->getMovieData($rules['movieId']);
						break;
					}

					default : {
						return ['Invalid location!'];
					}
				}
			} else {
				$result = $this->getAllMoviesData();
			}

			return $result;
		}

		private function getShowcaseMovieData(int $page, int $itemsPerRow):array{

			$movies = Movie::with([
					'cert:name',
					'cast:name',
					'genre:name',
					'keyArtImages:movie_id,url_local'
				])
				->skip($page*$itemsPerRow)
				->take($itemsPerRow)
				->get();

			$result = [];
			foreach($movies as $movie){

	    		$result[] = [
	    			'id'			=> $movie->id,
	    			'headline'		=> $movie->headline,
	    			'synopsis'		=> $movie->synopsis,
	    			'rating'		=> $movie->rating,
	    			'cert'			=> $movie->cert,
	    			'quote'			=> $movie->quote,
	    			'reviewAuthor'	=> $movie->reviewAuthor,
	    			'genres'		=> $movie->genre,
	    			'class'			=> $movie->class,
	    			'duration'		=> $movie->duration,
	    			'year'			=> $movie->year,
	    			'keyArtImages'	=> $movie->keyArtImages
	    		];
    		}

			return $result;
		}

		private function getMovieData(string $movieId):array{
			$movie = Movie::with([
				'cert:name',
				'cast:name',
				'genre:name',
				'keyArtImages:movie_id,url_local,width,height',
				'cardImages:url_local,width,height,movie_id',
				'videos:id,title,type,url,thumbnailUrl,movie_id',
				'videoAlternatives',
				'viewingWindows',
				'directors:name'
			])->find($movieId);

			return array(
				'id'				=> $movie->id,
    			'headline'			=> $movie->headline,
    			'synopsis'			=> $movie->synopsis,
    			'rating'			=> $movie->rating,
    			'cert'				=> $movie->cert,
    			'quote'				=> $movie->quote,
    			'reviewAuthor'		=> $movie->reviewAuthor,
    			'genres'			=> $movie->genre,
    			'class'				=> $movie->class,
    			'duration'			=> $movie->duration,
    			'year'				=> $movie->year,
    			'cardImages'		=> $movie->cardImages,
    			'keyArtImages'		=> $movie->keyArtImages,
    			'videos'			=> $movie->videos,
    			'videoAlternatives'	=> $movie->videoAlternatives,
    			'directors'			=> $movie->directors,
    			'cast'				=> $movie->cast
			);
		}

		private function getAllMoviesData(){
			$movie = Movie::with([
				'cert:name',
				'cast:name',
				'genre:name',
				'keyArtImages:movie_id,url_local,width,height',
				'cardImages:url_local,width,height,movie_id',
				'videos:movie_id,title,type,url,thumbnailUrl',
				'videoAlternatives',
				'viewingWindows',
				'directors:name'
			])->get();

			return json_decode(json_encode($movie),true);
		}
	}