<?php

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GenresTable extends Seeder
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
        $aMovies = json_decode($sJson,true);

        foreach ( $aMovies as $aMovie ) {
            if ( array_key_exists('genres', $aMovie) ) {
                array_walk($aMovie['genres'], function ($sGenre) {
                    $this->addGenre($sGenre);
                });
            }
        }
    }

    private function addGenre($sGenre)
    {
        $sDelimterFound = false;
        foreach (self::delimiters as $delimiter){
            if(strpos($sGenre, $delimiter) !== false){
                $sDelimterFound = $delimiter;
            }
        }
        if($sDelimterFound){
            $aGenresSparsed = explode($sDelimterFound, $sGenre);
            foreach ($aGenresSparsed as $sGenreSparsed){
                Genre::updateOrCreate(
                    ['name' => trim($sGenreSparsed)]
                );
            }
        }else{
            Genre::updateOrCreate(
                ['name' => trim($sGenre)]
            );
        }
    }
}
