<?php

use App\Http\Helpers\Util;
use App\Models\CardImage;
use App\Models\Genre;
use App\Models\KeyArtImage;
use App\Models\MovieCert;
use App\Models\MovieClass;
use App\Models\MovieGenre;
use App\Models\VideoAlternative;
use App\Models\Video;
use App\Models\ViewingWindow;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Movie;
use App\Models\Cast;
use App\Models\MovieCast;
use App\Models\Director;
use App\Models\MovieDirector;
use App\Models\Cert;

class MoviesTable extends Seeder
{
    const delimiters = ['&', '/'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sJson   = Storage::disk('files')->get('showcase.json');
        $aMovies = json_decode($sJson, true);

        foreach ( $aMovies as $aMovie ) {
            $oMovie = Movie::updateOrCreate(
                ['id' => $aMovie['id']],
                [
                    'id'           => $aMovie['id'],
                    'body'         => $aMovie['body'],
                    'duration'     => $aMovie['duration'],
                    'headline'     => $aMovie['headline'],
                    'lastUpdated'  => $aMovie['lastUpdated'],
                    'quote'        => $aMovie['quote'] ?? null,
                    'rating'       => $aMovie['rating'] ?? null,
                    'reviewAuthor' => $aMovie['reviewAuthor'] ?? null,
                    'skyGoId'      => $aMovie['skyGoId'] ?? ($aMovie['sgid'] ?? null),
                    'skyGoUrl'     => $aMovie['skyGoUrl'] ?? ($aMovie['sgUrl'] ?? null),
                    'sum'          => $aMovie['sum'],
                    'synopsis'     => $aMovie['synopsis'],
                    'url'          => $aMovie['url'],
                    'class'        => $aMovie['class'] ?? null,
                    'year'         => $aMovie['year']
                ]
            );

            $this->addCast($aMovie, $oMovie);
            $this->addDirectors($aMovie, $oMovie);
            $this->addCert($aMovie, $oMovie);
            $this->addGenres($aMovie, $oMovie);
            $this->addCardImages($aMovie, $oMovie);
            $this->addKeyArtImages($aMovie, $oMovie);
            $this->addVideos($aMovie, $oMovie);
            $this->addViewingWindow($aMovie, $oMovie);

        }
    }

    private function addCast(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('cast', $aMovie) ) {
            array_walk($aMovie['cast'], function ($cast, $key) use ($oMovie) {
                $oCast = Cast::where('name', $cast['name'])->first();
                MovieCast::updateOrCreate(
                    ['movie_id' => $oMovie->id, 'cast_id' => $oCast->id]
                );
            });
        }
    }

    private function addDirectors(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('directors', $aMovie) ) {
            array_walk($aMovie['directors'], function ($director, $key) use ($oMovie) {
                $oDirector = Director::where('name', $director['name'])->first();
                MovieDirector::updateOrCreate(
                    ['movie_id' => $oMovie->id, 'director_id' => $oDirector->id]
                );
            });
        }
    }

    private function addCardImages(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('cardImages', $aMovie) ) {
            array_walk($aMovie['cardImages'], function ($cardImage, $key) use ($oMovie) {
                $aUpdate = [
                    'width'    => $cardImage['w'],
                    'height'   => $cardImage['h'],
                    'movie_id' => $oMovie->id
                ];
                try {
                    $client   = new Client();
                    $response = $client->request('GET', $cardImage['url']);
                    if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {
                        $sExtension = Util::mime2ext($response->getHeaderLine('content-type'));
                        $sFileName  = '/' . $oMovie->id . '/' . $key . '.' . $sExtension;
                        Storage::disk('cardImages')->put($sFileName, $response->getBody()->getContents());
                        $aUpdate['url_local'] = Storage::disk('cardImages')->getDriver()->getAdapter()->getPathPrefix() . $sFileName;
                    }
                } catch ( GuzzleHttp\Exception\ClientException $e ) {
                }
                CardImage::updateOrCreate(
                    ['url_remote' => $cardImage['url']],
                    $aUpdate
                );
            });
        }
    }

    private function addKeyArtImages(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('keyArtImages', $aMovie) ) {
            array_walk($aMovie['keyArtImages'], function ($keyArtImage, $key) use ($oMovie) {
                $aUpdate = [
                    'width'    => $keyArtImage['w'],
                    'height'   => $keyArtImage['h'],
                    'movie_id' => $oMovie->id
                ];
                try {
                    $client   = new Client();
                    $response = $client->request('GET', $keyArtImage['url']);
                    if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {
                        $sExtension = Util::mime2ext($response->getHeaderLine('content-type'));
                        $sFileName  = '/' . $oMovie->id . '/' . $key . '.' . $sExtension;
                        Storage::disk('keyArtImages')->put($sFileName, $response->getBody()->getContents());
                        $aUpdate['url_local'] = Storage::disk('keyArtImages')->getDriver()->getAdapter()->getPathPrefix() . $sFileName;
                    }
                } catch ( GuzzleHttp\Exception\ClientException $e ) {
                }
                KeyArtImage::updateOrCreate(
                    ['url_remote' => $keyArtImage['url']],
                    $aUpdate
                );
            });
        }
    }

    private function addCert(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('cert', $aMovie) ) {
            $oCert = Cert::where('name', $aMovie['cert'])->first();
            MovieCert::updateOrCreate(
                ['movie_id' => $oMovie->id, 'cert_id' => $oCert->id]
            );
        }
    }

    private function addGenres(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('genres', $aMovie) ) {
            array_walk($aMovie['genres'], function ($sGenre) use ($oMovie) {
                $this->addGenre($sGenre, $oMovie);
            });
        }
    }

    private function addGenre($sGenre, App\Models\Movie $oMovie): void
    {
        $sDelimterFound = false;
        foreach ( self::delimiters as $delimiter ) {
            if ( strpos($sGenre, $delimiter) !== false ) {
                $sDelimterFound = $delimiter;
            }
        }
        if ( $sDelimterFound ) {
            $aGenresSparsed = explode($sDelimterFound, $sGenre);
            foreach ( $aGenresSparsed as $sGenreSparsed ) {
                $oGenre = Genre::where('name', trim($sGenreSparsed))->first();
                MovieGenre::updateOrCreate(
                    ['movie_id' => $oMovie->id, 'genre_id' => $oGenre->id]
                );
            }
        }
        else {
            $oGenre = Genre::where('name', $sGenre)->first();
            MovieGenre::updateOrCreate(
                ['movie_id' => $oMovie->id, 'genre_id' => $oGenre->id]
            );
        }
    }

    private function addVideos(array $aMovie, App\Models\Movie $oMovie): void
    {
        if ( array_key_exists('videos', $aMovie) ) {
            array_walk($aMovie['videos'], function ($aVideo, $key) use ($oMovie, $aMovie) {
                $aFind   = ['title' => $aVideo['title'], 'movie_id' => $oMovie->id];
                $aUpdate = ['type' => $aVideo['type']];
                try {
                    $client   = new Client();
                    $response = $client->request('GET', $aVideo['url']);
                    if ( $response->getStatusCode() == 200 ) {
                        $aUpdate['url']                 = $aVideo['url'];
                        $aUpdate['resource_is_working'] = true;
                    }
                } catch ( GuzzleHttp\Exception\ClientException $e ) {
                }

                $oVideo = Video::updateOrCreate($aFind, $aUpdate);

                if ( array_key_exists('thumbnailUrl', $aVideo) ) {
                    try {
                        $thumbClientRequest = new Client();
                        $response           = $thumbClientRequest->request('GET', $aVideo['thumbnailUrl']);
                        if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {
                            $sExtension = Util::mime2ext($response->getHeaderLine('content-type'));
                            $sFileName  = $oVideo->id . $sExtension;
                            Storage::disk('videosThumbnails')->put($sFileName, $response->getBody()->getContents());
                            $oVideo->thumbnailUrl            = Storage::disk('videosThumbnails')->getDriver()->getAdapter()->getPathPrefix() . $sFileName;
                            $oVideo->thumbnailUrl_is_working = true;
                            $oVideo->save();
                        }
                    } catch ( GuzzleHttp\Exception\ClientException $e ) {
                    }
                }

                if ( array_key_exists('alternatives', $aVideo) ) {
                    array_walk($aVideo['alternatives'], function ($aAlternative) use ($oMovie, $oVideo) {
                        $this->addAlternativeVideo($oVideo->id, $oMovie->id, $aAlternative);
                    });
                }
            });
        }
    }

    private function addAlternativeVideo(int $iVideoId, string $sMovieId, array $aAlternative): void
    {
        $aFind   = ['video_id' => $iVideoId, 'url' => $aAlternative['url'], 'quality' => $aAlternative['quality']];
        $aUpdate = [];
        try {
            $client   = new Client();
            $response = $client->request('GET', $aAlternative['url']);
            if ( $response->getStatusCode() == 200 && $response->getBody()->isReadable() ) {
                $aUpdate['resource_is_working'] = true;
            }

        } catch ( GuzzleHttp\Exception\ClientException $e ) {
        }
        VideoAlternative::updateOrCreate($aFind, $aUpdate);
    }

    private function addViewingWindow(array $aMovie, App\Models\Movie $oMovie): void
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
