<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ts=[ 'Happy', 'Competition', 'Buy', 'Sell', 'Europe', 'Africa', 'Audi', 'Opel', 'Elections', 'Poverty', 'Bil Gates', 'Elon Musk', 'Nikola Tesla', 'Bitcoins', 'Life', 'Money', 
            'Football', 'Basketball', 'NBA', 'Instagram', 'New','Facebook', 'Tattoos'];
        foreach ($ts as $c){
            DB::table('tags')->insert([
                'name' => $c,
                'slug' => Str::slug($c),
            ]);
        }
    }

    
}
