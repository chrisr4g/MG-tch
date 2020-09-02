<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\ViewingWindow;

class ViewingWindowRepository {
	
	public function addViewingWindow(array $aMovie, Movie $oMovie): void
    {
        if ( array_key_exists('viewingWindow', $aMovie) ) {
            ViewingWindow::updateOrCreate(
                ['movie_id' => $oMovie->id],
                [
                    'title'      => $aMovie['viewingWindow']['title'] ?? null,
                    'startDate'  => $aMovie['viewingWindow']['startDate'],
                    'endDate'    => $aMovie['viewingWindow']['endDate'] ?? null,
                    'wayToWatch' => $aMovie['viewingWindow']['wayToWatch'],
                ]
            );
        }
    }

}