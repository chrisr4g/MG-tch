<?php

use Illuminate\Database\Seeder;
use App\Models\Director;
use Illuminate\Support\Facades\Storage;

class DirectorsTable extends Seeder
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
            if ( array_key_exists('directors', $aMovie) ) {
                array_walk($aMovie['directors'], function ($director, $key) {
                    Director::updateOrCreate(
                        ['name' => $director['name']]
                    );
                });
            }
        }
    }
}
