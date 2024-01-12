<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
	public function login_displays_validation_errors(): void
	{
		$response = $this->post("/login", [
			"username" => "abc",
			"password" => "abc"
		]);

		$response->assertStatus(302);
		$response->assertSessionHasErrors(["message"]);
	}

	/** @test */
	public function login_authenticates_and_redirects_user_to_password_change(): void
	{
		$user = User::factory()->create();

		$response = $this->post("/login", [
			"username" => $user->username,
			"password" => "password"
		]);

		$response->assertRedirect("/change-password?changingForFirstTime=true");
		$this->assertAuthenticatedAs($user);
	}

	/** @test */
	public function login_authenticates_and_redirects_user_to_home(): void
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false
		]);

		$response = $this->post("/login", [
			"username" => $user->username,
			"password" => "password"
		]);

		$response->assertRedirect("/");
		$this->assertAuthenticatedAs($user);
	}

	/** @test */
	public function password_change_form_changes_password(): void
	{
		$user = User::factory()->create();

		$oldPassword = "password";
		$newPassword = Str::password(10);

		Auth::login($user);

		$response = $this->post("/change-password", [
			"password" => $newPassword
		]);

		$user->refresh();

		$response->assertRedirect("/oobe");
		$this->assertAuthenticatedAs($user);

		$this->assertFalse(Hash::check($oldPassword,
			$user->password));

		$this->assertTrue(Hash::check($newPassword, $user->password));
	}

	/** @test */
	public function password_change_form_displays_validation_errors(): void {
		$user = User::factory()->create();
		Auth::login($user);

		$response = $this->post("/change-password", [
			"password" => "h"
		]);

		$response->assertStatus(302);
		$response->assertSessionHasErrors(["password"]);
	}

	/** @test */
	public function logout_logs_user_out(): void {
		$user = User::factory()->create();
		Auth::login($user);

		$response = $this->post("/logout");
		$response->assertStatus(302);

		$this->assertFalse(Auth::check());
	}

	/** @test */

	public function displays_login_form(): void {
		$response = $this->get("/login");

		$response->assertStatus(200);
		$response->assertViewIs("login");
	}

	/** @test */
	public function displays_change_password_form(): void {
		$user = User::factory()->create();
		Auth::login($user);
		$response = $this->get("/change-password");

		$response->assertStatus(200);
		$response->assertViewIs("change-password");
	}
}
