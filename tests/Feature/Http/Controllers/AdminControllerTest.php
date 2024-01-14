<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
	/** @test */
	public function can_list_admin_options()
	{
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));
		$response = $this->get("/admin");
		$response->assertStatus(200);
		$response->assertViewIs("admin.list");
	}

	/** @test */
	public function regular_user_cannot_list_admin_options()
	{
		Auth::login(User::factory()->create([
			"isAdministrator" => false
		]));
		$response = $this->get("/admin");
		$response->assertStatus(403);
	}
}
