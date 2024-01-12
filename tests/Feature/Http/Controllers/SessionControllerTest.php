<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionControllerTest extends TestCase
{
    /** @test */
    public function unauthenticated_user_redirects_to_login_form(): void
    {
        $response = $this->get("/");

        $response->assertStatus(302);
    }

	/** @test */
	public function login_displays_validation_errors(): void {
		$response = $this->post("/login", []);

		$response->assertStatus(302);
		$response->assertSessionHasErrors(["username", "password"]);
	}
}
