<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::Truncate();
        Question::Truncate();

        //User 1
        Question::create(['description'=>'In which country was Messi born?', 'answer'=>'Argentina',  'status'=>'NOT ANSWERED', 'user_id' => 1]);
        Question::create(['description'=>'2 + 2?', 'answer'=>'4',  'status'=>'NOT ANSWERED', 'user_id' => 1]);
        Question::create(['description'=>'Capital of France?', 'answer'=>'Paris', 'status'=>'NOT ANSWERED', 'user_id' => 1]);
        Question::create(['description'=>'Which city hosted the last Olympic games?', 'answer'=>'Tokyo',  'status'=>'NOT ANSWERED', 'user_id' => 1]);

        //User 2
        Question::create(['description'=>'Who was the Roman god of war?', 'answer'=>'Ares',  'status'=>'NOT ANSWERED', 'user_id' => 2]);
        Question::create(['description'=>'How many tentacles does a squid have?', 'answer'=>'8',  'status'=>'NOT ANSWERED', 'user_id' => 2]);
        Question::create(['description'=>'What is man\'s best friend?', 'answer'=>'Dog' , 'status'=>'NOT ANSWERED', 'user_id' => 2]);
        Question::create(['description'=>'About what percentage of the Earth\'s surface is water?', 'answer'=>'90%',  'status'=>'NOT ANSWERED', 'user_id' => 2]);
        Question::create(['description'=>'2*2', 'answer'=>'4',  'status'=>'NOT ANSWERED', 'user_id' => 2]);
    }
}
