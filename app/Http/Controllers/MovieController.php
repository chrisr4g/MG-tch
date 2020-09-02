<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\MovieRepository;


class MovieController extends Controller
{
    public function movieData(string $movieId){
		
    	$rules = [
            'location'  => 'movie',
            'movieId'	=> (string)$movieId
        ];

        $apiDataRepo = new MovieRepository();
        $movie = $apiDataRepo->getData($rules);
        
        return view('movie',compact('movie'));
    }
}
