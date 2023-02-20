<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Time;



class JamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Time::create([
        'mulai'     => '07:00',
        'selesai'   => '08:00',
        'ket'       => 'Masuk',
       ]);

       Time::create([
        'mulai'     => '16:00',
        'selesai'   => '17:00',
        'ket'       => 'Pulang',
       ]);

    }
}
