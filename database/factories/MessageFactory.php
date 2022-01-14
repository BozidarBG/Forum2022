<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $from=random_int(1,33);
        $to=$this->toUser($from);
        return [
            'from_user'=>$from,
            'to_user'=>$to,
            'channel'=>$this->getChannelName($from, $to),
            'message'=>$this->faker->sentence(true),
        ];
    }

    private function getChannelName($from, $to) : string {
        return $from > $to ? $to.'_'.$from : $from.'_'.$to;
    }

    private function toUser($from){
        
            $to=random_int(1,33);
            //info($to);
            if($to==$from){
                if($from==1){
                    return 2;
                }else if($to==33){
                    return 32;
                }
            }else{
                return $to;
            }
        
    }
}
