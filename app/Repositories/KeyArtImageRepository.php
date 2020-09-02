<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\Util;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

use App\Models\Movie;
use App\Models\KeyArtImage;

class KeyArtImageRepository{

    public function addKeyArtImages(array $aMovie, Movie $oMovie): void
    {
        if ( array_key_exists('keyArtImages', $aMovie) ) {
            array_walk($aMovie['keyArtImages'], function ($keyArtImage, $key) use ($oMovie) {
                $aUpdate = [
                    'width'     => $keyArtImage['w'],
                    'height'    => $keyArtImage['h'],
                    'url_remote'=> $keyArtImage['url'],
                    'movie_id'  => $oMovie->id
                ];
                try {
                    $client   = new Client();
                    $response = $client->request('GET', $keyArtImage['url']);
                    
                    if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {

                        $sExtension = Util::mime2ext($response->getHeaderLine('content-type'));
                        $sFileName  = '/' . $oMovie->id . '/' . $key . '.' . $sExtension;

                        Storage::disk('keyArtImages')->put($sFileName, $response->getBody()->getContents());
                        $aUpdate['url_local'] = '/' . strstr(Storage::disk('keyArtImages')->url($sFileName), 'keyArtImages');
                    }
                } catch ( ClientException $e ) {
                    $aUpdate['url_local'] = null;
                } catch ( ConnectException $e ) {
                    $aUpdate['url_local'] = null;
                }
                KeyArtImage::updateOrCreate(
                    ['url_remote' => $keyArtImage['url']],
                    $aUpdate
                );
            });
        }
    }

}
