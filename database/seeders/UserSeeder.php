<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach(range(1, 5) as $index)
        {
            DB::table('users')->insert([
                'nip'           => $faker->numerify('##################'),
                'nama'          => $faker->name(),
                'jk'            => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'email'         => $faker->email(),
                'seksi'         => $faker->randomElement(['Intaltuskim', 'Intaldakim']),
                'jabatan'       => 'Kepala Biro',
                'foto'          => 'profile.png',
                'password'      => bcrypt('123'),
            ]);

        }
    }
}
