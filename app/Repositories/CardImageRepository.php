<?php

namespace App\Repositories;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\Util;

use App\Models\Movie;
use App\Models\CardImage;

class CardImageRepository{

	public function addCardImages(array $movie, Movie $uMovie):void{
		if ( array_key_exists('cardImages', $movie) ) {
            array_walk($movie['cardImages'], function ($cardImage, $key) use ($uMovie) {
                $aUpdate = [
                    'width'    	=> $cardImage['w'],
                    'height'	=> $cardImage['h'],
                    'url_remote'=> $cardImage['url'],
                    'movie_id' 	=> $uMovie->id
                ];

                try {
                    $client   = new Client();
                    $response = $client->request('GET', $cardImage['url'], ['connect_timeout' => 3]);

                    if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {

                        $sExtension = Util::mime2ext($response->getHeaderLine('content-type'));
                        $sFileName  = '/' . $uMovie->id . '/' . $key . '.' . $sExtension;

                        Storage::disk('cardImages')->put($sFileName, $response->getBody()->getContents());
                        $aUpdate['url_local'] = '/' . strstr(Storage::disk('cardImages')->url($sFileName), 'cardImages');
                    }
                } catch ( ClientException $e ) {
                	if($e->getResponse()->getStatusCode() == 404){
                		$aUpdate['url_local'] = null;
                	}
                } catch ( ConnectException $e ){
                	$aUpdate['url_local'] = null;
                } catch ( RequestException $e ) {
                    $aUpdate['url_local'] = null;
                }

                CardImage::updateOrCreate(
                    ['url_remote' => $cardImage['url']],
                    $aUpdate
                );
            });
        }
	}

}
