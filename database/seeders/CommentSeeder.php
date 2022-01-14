<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //for every topic
        for($i=1; $i<300; $i++){
            $num=random_int(1,5);
            //number of comments
            for($x=1; $x<=$num; $x++){
                $topic=Topic::where('id', $i)->first();
                if($topic){
                    $topic->comments()->create([
                        'user_id'=>random_int(1,33),
                        'body'=>"Comment no. ".$x." for topic no. ".$i
                    ]);
                }
            }
        }
    }
}
