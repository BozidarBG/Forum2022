<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ts=['Money', 'Politics', 'Sport', 'Cars', 'IT', 'Nature','TV', 'Music', 'Movies', 'Pets'];
        foreach ($ts as $c){
            DB::table('categories')->insert([
                'name' => $c,
                'slug' => Str::slug($c),
                'color'=>$this->makeColor(),
            ]);
        }
    }

    private function makeColor() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}
