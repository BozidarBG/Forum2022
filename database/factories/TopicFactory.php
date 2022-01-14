<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title=$this->faker->sentence(true);
        return [
            'title'=>$title,
            'slug'=>Str::slug($title),
            'description'=>$this->faker->paragraph(true),
            'user_id'=>random_int(1,33),
            'category_id'=>random_int(1,10),
            
        ];
    }
}
