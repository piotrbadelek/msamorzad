<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StatisticsControllerTest extends TestCase
{
	/** @test */
	public function test_example(): void
	{
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));
		$response = $this->get("admin/stats/active-users");
		$response->assertStatus(200);
		$response->assertViewIs("admin.stats.active-user-ratio");
	}
}
