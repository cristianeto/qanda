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
    public function it_can_fetch_all_questions()
    {
        //Arrange
        $user = User::factory()->create();
        $questions = Question::factory()->count(3)->create(['user_id'=>$user->id]);

        //Act
        $this->artisan('qanda:interactive')

            //Assert
            ->expectsOutput('*** Welcome to QANDA program***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program:', 'List all questions' , [
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
