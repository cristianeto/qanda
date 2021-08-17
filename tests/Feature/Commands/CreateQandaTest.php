<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateQandaTest extends TestCase
{
    use  RefreshDatabase;

    /** @test */
    public function guest_can_call_the_menu()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('*** Welcome Guest ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 6, [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ]);

    }

    /** @test */
    public function a_specific_user_can_call_the_menu()
    {
        $this->artisan('qanda:interactive cris@test.com')
            ->expectsOutput('*** Welcome Cris ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 6, [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ]);

    }

    /** @test */
    public function guests_can_create_a_question()
    {
        //Arrange
        $question = Question::factory()->create();

        //Act
        $result = $this->artisan('qanda:interactive')

        //Assert
            ->expectsOutput('*** Welcome Guest ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'Create a question', [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Creating a question...')
            ->expectsQuestion('Write a question',$question->description)
            ->expectsQuestion('Write a answer to the previous question',$question->answer)
            ->assertExitCode(0);
        $this->assertDatabaseHas('questions', [
            'description' => $question->description,
            'answer' => $question->answer,
            'status'=> $question->status,
        ]);
    }

    /** @test */
    public function a_specific_user_can_create_a_question()
    {
        //Arrange
        $question = Question::factory()->create();

        //Act
        $result = $this->artisan('qanda:interactive cris@test.com')

        //Assert
            ->expectsOutput('*** Welcome Cris ***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program', 'Create a question', [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ])
            ->expectsOutput('Creating a question...')
            ->expectsQuestion('Write a question',$question->description)
            ->expectsQuestion('Write a answer to the previous question',$question->answer)
            ->assertExitCode(0);
        $this->assertDatabaseHas('questions', [
            'description' => $question->description,
            'answer' => $question->answer,
            'status'=> $question->status,
        ]);
    }
}
