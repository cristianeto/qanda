<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    use  RefreshDatabase;


    /** @test */
    public function command_calls_multiple_choices()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('*** Welcome to QANDA program***')
            ->expectsChoice('Choose an option between [0-5], or write "exit" to stop this program:', 1, [
                'Create a question',
                'List all questions',
                'Practice',
                'Stats',
                'Reset',
                'Exit',
            ]);

    }
}
