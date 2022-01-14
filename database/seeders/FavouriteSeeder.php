<?php

namespace Database\Seeders;

use App\Models\FavouriteTopic;
use App\Models\FavouriteComment;
use Illuminate\Database\Seeder;

class FavouriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<34;$i++){
            $arrOfLikedtopics=[];
            for($x=1; $x<11; $x++){
                $randomTopicId=random_int(1,300);
                if(!in_array($randomTopicId, $arrOfLikedtopics)){
                    $fav=new FavouriteTopic();
                    $fav->user_id=$i;
                    $fav->topic_id=$randomTopicId;
                    $fav->save();
                    $arrOfLikedtopics[]=$randomTopicId;
                }
            }
        }

        for($i=1; $i<34;$i++){
            $arrOfLikedComments=[];
            for($x=1; $x<11; $x++){
                $randomCommentId=random_int(1,928);
                if(!in_array($randomCommentId, $arrOfLikedComments)){
                    $fav=new FavouriteComment();
                    $fav->user_id=$i;
                    $fav->comment_id=$randomCommentId;
                    $fav->save();
                    $arrOfLikedComments[]=$randomCommentId;
                }
            }
        }
    }
}
