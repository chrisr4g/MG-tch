<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\Util;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Models\Movie;
use App\Models\Video;

use App\Repositories\VideoAlternativeRepository;

class VideoRepository{

    public function addVideos(array $aMovie, Movie $oMovie): void
    {
        if ( array_key_exists('videos', $aMovie) ) {
            array_walk($aMovie['videos'], function ($aVideo, $key) use ($oMovie, $aMovie) {
                $aFind   = ['title' => $aVideo['title'], 'movie_id' => $oMovie->id];
                $aUpdate = ['type' => $aVideo['type']];
                try {
                    $client   = new Client();
                    $response = $client->request('GET', $aVideo['url']);
                    if ( $response->getStatusCode() == 200 ) {
                        $aUpdate['resource_is_working'] = true;
                    }
                    
                } catch ( ClientException $e ) {
                }
                $aUpdate['url'] = $aVideo['url'];

                $oVideo = Video::updateOrCreate($aFind, $aUpdate);

                if ( array_key_exists('thumbnailUrl', $aVideo) ) {
                    try {
                        $thumbClientRequest = new Client();
                        $response           = $thumbClientRequest->request('GET', $aVideo['thumbnailUrl']);

                        if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {
                            $sExtension = Util::mime2ext($response->getHeaderLine('content-type'));
                            $sFileName  = $oVideo->id . $sExtension;

                            Storage::disk('videosThumbnails')->put($sFileName, $response->getBody()->getContents());
                            $oVideo->thumbnailUrl = '/' . strstr(Storage::disk('videosThumbnails')->url($sFileName), 'videosThumbnails');
                            $oVideo->thumbnailUrl_is_working = true;

                            $oVideo->save();
                        }
                    } catch ( ClientException $e ) {
                    }
                }

                if ( array_key_exists('alternatives', $aVideo) ) {
                    $videoAlternativeRepository = new VideoAlternativeRepository();
                    array_walk($aVideo['alternatives'], function ($aAlternative) use ($oMovie, $oVideo, $videoAlternativeRepository) {
                        $videoAlternativeRepository->addAlternativeVideo($oVideo->id, $oMovie->id, $aAlternative);
                    });
                }
            });
        }
    }

}
