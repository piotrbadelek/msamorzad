<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Classunit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ClassunitControllerTest extends TestCase
{
	protected function generateAdminUser(): User
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"isAdministrator" => true
		]);
		Auth::login($user);
		return $user;
	}

	/** @test */
	public function classunits_get_listed()
	{
		$this->generateAdminUser();
		$response = $this->get("/admin/classunit");
		$response->assertStatus(200);
		$response->assertViewIs("admin.classunit.list");
	}

	/** @test */
	public function classunits_dont_get_listed_to_regular_users()
	{
		Auth::login(User::factory()->create());
		$response = $this->get("/admin/classunit");
		$response->assertStatus(403);
	}

	/** @test */
	public function classunit_deletion_form_gets_shown()
	{
		$this->generateAdminUser();
		$classunit = Classunit::factory()->create();
		$response = $this->delete("/admin/classunit/{$classunit->id}");
		// Redirect to verify password
		$response->assertStatus(302);
	}

	/** @test */
	public function classunit_create_form_gets_shown()
	{
		$this->generateAdminUser();
		$response = $this->get("/admin/classunit/new");
		$response->assertStatus(200);
	}

	/** @test */
	public function classunit_gets_created()
	{
		$classunit_name = rand(1, 4) . " i-m";

		$this->generateAdminUser();
		$response = $this->post("/admin/classunit", [
			"name" => $classunit_name
		]);
		$response->assertStatus(302);
		$response->assertRedirect("/admin/classunit");
		$this->assertDatabaseHas("classunits", [
			"name" => $classunit_name
		]);
	}

}
