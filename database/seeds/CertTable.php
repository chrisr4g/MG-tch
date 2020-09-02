<?php

use App\Models\Cert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CertTable extends Seeder
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
            if ( array_key_exists('cert', $aMovie) ) {
                Cert::updateOrCreate(
                    ['name' => $aMovie['cert']]
                );
            }
        }
    }
}
