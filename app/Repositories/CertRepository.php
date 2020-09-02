<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\Cert;

class CertRepository{

	public function addCert(array $movie, Movie $uMovie):void{
		if ( array_key_exists('cert', $movie) ) {

            $iCert = Cert::updateOrCreate(
                ['name' => $movie['cert']]
            );

            $uMovie->cert()->sync($iCert->id);
        }
	}

}