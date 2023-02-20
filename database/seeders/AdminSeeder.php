<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'nama'          => 'Admin',
            'jk'            => 'Laki-laki',
            'email'         => 'admin@localhost.com',
            'foto'          => 'profile.png',
            'password'      => bcrypt('123'),
        ]);
    }
}
