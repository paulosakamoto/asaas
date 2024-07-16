<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_redirection_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
