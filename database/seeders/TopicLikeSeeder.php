<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;

class TopicLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();
        foreach ($users as $user){
            $topicsArr=[];
            for($i=0; $i<30; $i++){
                $randomTopicId=random_int(1,300);
                if(!in_array($randomTopicId, $topicsArr)){
                    $topicsArr[]=$randomTopicId;
                    $x=new \App\Models\TopicLike();
                    $x->user_id=$user->id;
                    $x->topic_id=random_int(1,300);
                    $x->type=random_int(0,1) == "0" ? 'l': 'd';
                    $x->save();
                }
               
            }

        }
    }
}
