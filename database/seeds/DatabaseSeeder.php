<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CastTable::class);
        $this->call(DirectorsTable::class);
        $this->call(CertTable::class);
        $this->call(GenresTable::class);
        $this->call(MoviesTable::class);
    }
}
