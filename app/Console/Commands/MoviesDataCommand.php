<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

use Illuminate\Support\Facades\Storage;

use App\Models\Movie;

use App\Repositories\MovieRepository;
use App\Repositories\CardImageRepository;
use App\Repositories\CastRepository;
use App\Repositories\CertRepository;
use App\Repositories\DirectorRepository;
use App\Repositories\KeyArtImageRepository;
use App\Repositories\ViewingWindowRepository;
use App\Repositories\VideoRepository;
use App\Repositories\VideoAlternativeRepository;
use App\Repositories\GenreRepository;

class MoviesDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:showcase {url} {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get`s movies showcase data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() : void
    {
        $options = [
            'headers' => [
                "Accept: application/json"
            ]
        ];

        if(Storage::disk('files')->exists($this->argument('filename'))){
            $fileLastModified =  Storage::disk('files')->lastModified($this->argument('filename'));

            $options['headers'][] = "If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',$fileLastModified);
        }

        $responseData = $this->sendRequest($options);
        if(!in_array($responseData['code'], [304, 500])){
            $this->storeData($responseData['response'])->processData();
        }
    }

    private function adaptString(string $string):string{

        $map = [
            chr(0x8A) => chr(0xA9),
            chr(0x8C) => chr(0xA6),
            chr(0x8D) => chr(0xAB),
            chr(0x8E) => chr(0xAE),
            chr(0x8F) => chr(0xAC),
            chr(0x9C) => chr(0xB6),
            chr(0x9D) => chr(0xBB),
            chr(0xA1) => chr(0xB7),
            chr(0xA5) => chr(0xA1),
            chr(0xBC) => chr(0xA5),
            chr(0x9F) => chr(0xBC),
            chr(0xB9) => chr(0xB1),
            chr(0x9A) => chr(0xB9),
            chr(0xBE) => chr(0xB5),
            chr(0x9E) => chr(0xBE),
            chr(0x80) => '&euro;',
            chr(0x82) => '&sbquo;',
            chr(0x84) => '&bdquo;',
            chr(0x85) => '&hellip;',
            chr(0x86) => '&dagger;',
            chr(0x87) => '&Dagger;',
            chr(0x89) => '&permil;',
            chr(0x8B) => '&lsaquo;',
            chr(0x91) => '&lsquo;',
            chr(0x92) => '&rsquo;',
            chr(0x93) => '&ldquo;',
            chr(0x94) => '&rdquo;',
            chr(0x95) => '&bull;',
            chr(0x96) => '&ndash;',
            chr(0x97) => '&mdash;',
            chr(0x99) => '&trade;',
            chr(0x9B) => '&rsquo;',
            chr(0xA6) => '&brvbar;',
            chr(0xA9) => '&copy;',
            chr(0xAB) => '&laquo;',
            chr(0xAE) => '&reg;',
            chr(0xB1) => '&plusmn;',
            chr(0xB5) => '&micro;',
            chr(0xB6) => '&para;',
            chr(0xB7) => '&middot;',
            chr(0xBB) => '&raquo;',
        ];
        $string = html_entity_decode(mb_convert_encoding(strtr($string, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');

        return $string;
    }

    private function storeData(string $data){

        if(Storage::disk('files')->exists($this->argument('filename'))){
            Storage::disk('files')->delete($this->argument('filename'));
        }

        Storage::disk('files')->put($this->argument('filename'),$data);

        return $this;

    }

    private function sendRequest(array $options):array{
        try {
            $client = new Client();
            $response = $client->request('GET', $this->argument('url'), $options);

            $type = $response->getHeader('content-type');
            $parsed = Psr7\parse_header($type);

            $original_body = (string)$response->getBody();
            $original_body = $this->adaptString($original_body);

            $adaptedBody = mb_convert_encoding($original_body, 'UTF-8', $parsed[0]['charset'] ?? 'UTF-8');

            return [
                'response'  => $adaptedBody,
                'code'      => $response->getStatusCode()
            ];
        }
        catch ( ClientException $e ) {
            return [
                'response'  => '',
                'code'      => 500
            ];
        }
        catch (\Exception $e){
            return [
                'response'  => '',
                'code'      => 500
            ];
        }
    }

    private function processData():void{

        if(!Storage::disk('files')->exists($this->argument('filename'))){
            abort(404,"File not found!");
        } else {

            $repositories = [
                'cast'              => new CastRepository(),
                'director'          => new DirectorRepository(),
                'cert'              => new CertRepository(),
                'genre'             => new GenreRepository(),
                'cardImage'         => new CardImageRepository(),
                'keyArtImage'       => new KeyArtImageRepository(),
                'video'             => new VideoRepository(),
                'videoAlternative'  => new VideoAlternativeRepository(),
                'viewingWindow'     => new ViewingWindowRepository(),
            ];

            $movies = json_decode(Storage::disk('files')->get($this->argument('filename')), true);

            foreach ( $movies as $movie ) {
                $uMovie = Movie::updateOrCreate(
                    ['id' => $movie['id']],
                    [
                        'id'           => $movie['id'],
                        'body'         => $movie['body'],
                        'duration'     => $movie['duration'],
                        'headline'     => $movie['headline'],
                        'lastUpdated'  => $movie['lastUpdated'],
                        'quote'        => $movie['quote'] ?? null,
                        'rating'       => $movie['rating'] ?? null,
                        'reviewAuthor' => $movie['reviewAuthor'] ?? null,
                        'skyGoId'      => $movie['skyGoId'] ?? ($movie['sgid'] ?? null),
                        'skyGoUrl'     => $movie['skyGoUrl'] ?? ($movie['sgUrl'] ?? null),
                        'sum'          => $movie['sum'],
                        'synopsis'     => $movie['synopsis'],
                        'url'          => $movie['url'],
                        'class'        => $movie['class'] ?? null,
                        'year'         => $movie['year']
                    ]
                );


                $repositories['cast']->addCast($movie, $uMovie);
                $repositories['director']->addDirectors($movie, $uMovie);
                $repositories['cert']->addCert($movie, $uMovie);
                $repositories['genre']->addGenres($movie, $uMovie);
                $repositories['cardImage']->addCardImages($movie, $uMovie);
                $repositories['keyArtImage']->addKeyArtImages($movie, $uMovie);
                $repositories['video']->addVideos($movie, $uMovie);
                $repositories['viewingWindow']->addViewingWindow($movie, $uMovie);
            }
        }
    }
}
