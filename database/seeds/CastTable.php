<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Cast;

class CastTable extends Seeder
{
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
            if ( array_key_exists('cast', $aMovie) ) {
                array_walk($aMovie['cast'], function ($cast, $key) {
                    Cast::updateOrCreate(
                        ['name' => $cast['name']]
                    );
                });
            }
        }
    }
}
