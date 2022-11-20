<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
            'name'=>"Philippine Countryville College, Inc. <br> (Formerly: Philippine Computer College, Inc.)",
            'address'=>"P2b, Sayre Highway, Panadtalan, 8714 Maramag Province of Bukidnon",
            'website'=>"www.philcountryville.net",
            'email'=>"philippinecountryvillecollege@gmail.com",
        ]);
    }
}
