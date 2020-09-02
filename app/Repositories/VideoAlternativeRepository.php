<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Models\VideoAlternative;

class VideoAlternativeRepository{

    public function addAlternativeVideo(int $iVideoId, string $sMovieId, array $aAlternative): void
    {
        $aFind   = ['video_id' => $iVideoId, 'url' => $aAlternative['url'], 'quality' => $aAlternative['quality']];
        $aUpdate = ['url' => $aAlternative['url']];
        try {
            $client   = new Client();
            $response = $client->request('GET', $aAlternative['url']);
            if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {
                $aUpdate['resource_is_working'] = true;
            }

        } catch ( ClientException $e ) {
        }
        VideoAlternative::updateOrCreate($aFind, $aUpdate);
    }

}
