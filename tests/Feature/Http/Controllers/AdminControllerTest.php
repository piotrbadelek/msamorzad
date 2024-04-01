<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
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

	/** @test */
	public function can_list_time_limited_events()
	{
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));
		$response = $this->get("/admin/events");
		$response->assertStatus(200);
		$response->assertViewIs("admin.events");
	}

	/** @test */
	public function can_list_all_payments()
	{
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));
		$response = $this->get("/admin/payments");
		$response->assertStatus(200);
		$response->assertViewIs("admin.payment.list");
	}
}
