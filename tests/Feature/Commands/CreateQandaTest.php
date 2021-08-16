<?php

namespace Tests\Feature\Commands;

use App\Models\Question;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateQandaTest extends TestCase
{
    use  RefreshDatabase;

    /** @test */
    public function command_calls_the_menu()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('*** Welcome to QANDA program***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program:', 6, [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ]);

    }

    /** @test */
    public function command_cant_create_a_question()
    {
        //Arrange
        $question = Question::factory()->create();

        //Act
        $result = $this->artisan('qanda:interactive')

        //Assert
            ->expectsOutput('*** Welcome to QANDA program***')
            ->expectsChoice('Choose an option between [0-4], or write "exit" to stop this program:', 'Create a question', [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
            ]);
        $result->expectsOutput('Creating a question...')
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
