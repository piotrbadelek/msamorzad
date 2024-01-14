<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    /** @test */
	public function user_can_subscribe_to_push() {
		$user = User::factory()->create();
		Auth::login($user);
		$response = $this->post("/push", [
			'endpoint' => 'example-endpoint',
			'keys' => [
				'auth' => 'example-auth-token',
				'p256dh' => 'example-public-key',
			]
		]);

		$response->assertJson(["success" => true])->assertStatus(200);
		$this->assertDatabaseHas("push_subscriptions", [
			"endpoint" => "example-endpoint",
			"public_key" => "example-public-key",
			"auth_token" => "example-auth-token",
			"subscribable_id" => $user->id
		]);
	}

	/** @test */
	public function push_subscription_with_missing_parameters_fails() {
		$user = User::factory()->create();
		Auth::login($user);
		$response = $this->post("/push");
		$this->assertDatabaseMissing("push_subscriptions", [
			"subscribable_id" => $user->id
		]);
	}
}
