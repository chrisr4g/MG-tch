<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\MovieRepository;

class HomeController extends Controller
{
    public function moviesData(Request $request){

    	$page = (int)$request->get('page') ?? 0;
        $origin = ($request->ajax()) ? 'api' : 'web';

        $rules = [
            'location'      => 'showcase',
            'page'          => $page,
            'itemsPerRow'   => 4,
        ];

        $apiDataRepo = new MovieRepository();
        $movies = $apiDataRepo->getData($rules);

        switch ($origin){
            case 'web' : {
                return view('showcase',compact('movies'));
            }
            case 'api' : {
                $view = "";
                foreach($movies as $movie){
                    $view .= view('layouts.components.showcase_item',compact('movie'))->render();
                }
                
                return response()->json(['html'=>$view]); 
            }
        }
    }
}
