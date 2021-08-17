<?php

namespace Tests\Feature\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListQuestionsTest extends TestCase
{
    use  RefreshDatabase;

    /** @test */
    public function guests_can_fetch_all_his_questions_with_correct_answer()
    {
        //Arrange
        $user = User::factory()->create(['name'=>'Guest', 'email'=>'guest@test.com', 'password'=>'password']);
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive')

            //Assert
            ->expectsOutput('*** Welcome Guest ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'List all questions' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Fetching all my questions...')
            ->expectsTable([
                'Question',
                'Answer',
            ], [
                [$questions[0]->description, $questions[0]->answer],
                [$questions[1]->description, $questions[1]->answer],
                [$questions[2]->description, $questions[2]->answer],
            ])->assertExitCode(0);
    }

    /** @test */
    public function a_specific_user_can_fetch_all_his_questions_with_correct_answer()
    {
        //Arrange
        $user = User::factory()->create();
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive '.$user->email)

            //Assert
            ->expectsOutput('*** Welcome '.$user->name.' ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'List all questions' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Fetching all my questions...')
            ->expectsTable([
                'Question',
                'Answer',
            ], [
                [$questions[0]->description, $questions[0]->answer],
                [$questions[1]->description, $questions[1]->answer],
                [$questions[2]->description, $questions[2]->answer],
            ])->assertExitCode(0);
    }

    /** @test */
    public function guests_can_practice()
    {
        //Arrange
        $user = User::factory()->create(['name'=>'Guest', 'email'=>'guest@test.com', 'password'=>'password']);
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive')

            //Assert
            ->expectsOutput('*** Welcome Guest ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'List all questions' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Fetching all my questions...')
            ->expectsTable([
                'Question',
                'Answer',
            ], [
                [$questions[0]->description, $questions[0]->answer],
                [$questions[1]->description, $questions[1]->answer],
                [$questions[2]->description, $questions[2]->answer],
            ])->assertExitCode(0);
    }

    /** @test */
    public function a_specific_user_can_practice()
    {
        //Arrange
        $user = User::factory()->create();
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive '.$user->email)

            //Assert
            ->expectsOutput('*** Welcome '.$user->name.' ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'List all questions' , [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Fetching all my questions...')
            ->expectsTable([
                'Question',
                'Answer',
            ], [
                [$questions[0]->description, $questions[0]->answer],
                [$questions[1]->description, $questions[1]->answer],
                [$questions[2]->description, $questions[2]->answer],
            ])->assertExitCode(0);
    }
}
