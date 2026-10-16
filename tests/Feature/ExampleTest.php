<?php

namespace Tests\Feature;

<<<<<<< HEAD
// use Illuminate\Foundation\Testing\RefreshDatabase;
=======
use Illuminate\Foundation\Testing\RefreshDatabase;
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
<<<<<<< HEAD
     */
    public function test_the_application_returns_a_successful_response(): void
=======
     *
     * @return void
     */
    public function test_example()
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
