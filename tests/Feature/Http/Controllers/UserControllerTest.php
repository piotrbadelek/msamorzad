<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
	use WithFaker;

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
	public function lists_users()
	{
		$this->generateAdminUser();
		$response = $this->get("/admin/user");
		$response->assertStatus(200);
		$response->assertViewIs("admin.user.list");
	}

	/** @test */
	public function user_without_admin_permission_cannot_list_users()
	{
		$user = User::factory()->create();
		Auth::login($user);
		$response = $this->get("/admin/user");
		$response->assertStatus(403);
	}

	/** @test */
	public function display_user_details()
	{
		$this->generateAdminUser();
		$user = User::factory()->create();
		$response = $this->get("/admin/user/" . $user->id);
		$response->assertStatus(200);
		$response->assertViewIs("admin.user.show");
	}

	/** @test */
	public function display_delete_form()
	{
		$this->generateAdminUser();
		$user = User::factory()->create();
		$response = $this->get("/admin/user/" . $user->id . "/delete");
		$response->assertStatus(200);
		$response->assertViewIs("admin.user.delete");
	}

	/** @test */
	public function user_deletion_and_message_transfer_to_ghost_user()
	{
		$this->generateAdminUser();
		$user = User::factory()->create();

		$ghost = User::where("username", "ghost")->first();
		if (!isset($ghost)) {
			$ghost = User::factory()->create([
				"username" => "ghost"
			]);
		}

		$messages = Message::factory(3)->create([
			"user_id" => $user->id
		]);

		$response = $this->delete("/admin/user/" . $user->id);

		$this->assertDatabaseMissing("users", ["id" => $user->id]);

		foreach ($messages as $message) {
			$this->assertDatabaseHas("messages", [
				"id" => $message->id,
				"user_id" => $ghost->id
			]);
		}

		$response->assertRedirect("/admin/user");
	}

	/** @test */
	public function nonexistent_user_deletion_results_in_404_error()
	{
		$this->generateAdminUser();

		// Attempt to delete a nonexistent user
		$response = $this->delete("/admin/user/999999");

		// Assert a 404 status code
		$response->assertStatus(404);
	}

	/** @test */
	public function change_password_sets_password()
	{
		$this->generateAdminUser();

		$user = User::factory()->create();

		$response = $this->get("/admin/user/" . $user->id . "/reset_password");
		$response->assertStatus(200);
		$this->assertDatabaseHas("users", [
			"id" => $user->id,
			"hasNotChangedPassword" => true
		]);
	}

	/** @test */
	public function nonexistent_user_password_reset_results_in_404_error()
	{
		$this->generateAdminUser();

		$response = $this->get("/admin/user/999999/reset_password");

		$response->assertStatus(404);
	}

	/** @test */
	public function display_update_user_form()
	{
		$this->generateAdminUser();

		$user = User::factory()->create();

		$response = $this->get("/admin/user/" . $user->id . "/update");
		$response->assertStatus(200);
		$response->assertViewIs("admin.user.update");
	}

	/** @test */
	public function user_updates_write_to_database()
	{
		$this->generateAdminUser();

		$user = User::factory()->create();

		$fakeData = $this->getFakeUserData();

		$response = $this->patch("/admin/user/" . $user->id, [
			"username" => $fakeData["username"],
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => $fakeData["type"],
			"samorzadType" => $fakeData["samorzadType"]
		]);

		$response->assertStatus(302);
		$this->assertDatabaseHas("users", [
			"id" => $user->id,
			"username" => $fakeData["username"],
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => $fakeData["type"],
			"samorzadType" => $fakeData["samorzadType"]
		]);
	}

	/** @test */
	public function user_updates_without_changing_username_write_to_database()
	{
		$this->generateAdminUser();

		$user = User::factory()->create();

		$fakeData = $this->getFakeUserData();

		$response = $this->patch("/admin/user/" . $user->id, [
			"username" => $user->username,
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => $fakeData["type"],
			"samorzadType" => $fakeData["samorzadType"]
		]);

		$response->assertStatus(302);
		$this->assertDatabaseHas("users", [
			"id" => $user->id,
			"username" => $user->username,
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => $fakeData["type"],
			"samorzadType" => $fakeData["samorzadType"]
		]);
	}

	/** @test */
	public function displays_create_user_form()
	{
		$this->generateAdminUser();

		$response = $this->get("/admin/user/new");
		$response->assertStatus(200);
		$response->assertViewIs("admin.user.create");
	}

	/** @test */
	public function user_gets_created()
	{
		$this->generateAdminUser();

		$fakeData = $this->getFakeUserData();

		$response = $this->post("/admin/user", [
			"username" => $fakeData["username"],
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => $fakeData["type"],
			"samorzadType" => $fakeData["samorzadType"]
		]);

		$this->assertDatabaseHas("users", [
			"username" => $fakeData["username"],
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => $fakeData["type"],
			"samorzadType" => $fakeData["samorzadType"]
		]);
	}

	/** @test */
	public function user_with_missing_data_errors()
	{
		$this->generateAdminUser();

		$response = $this->post("/admin/user");
		$response->assertSessionHasErrors(["username", "name", "classunit_id", "type", "samorzadType"]);
	}

	/** @test */
	public function teacher_gets_created()
	{
		$this->generateAdminUser();

		$fakeData = $this->getFakeUserData();

		$response = $this->post("/admin/user", [
			"username" => $fakeData["username"],
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => "nauczyciel",
			"samorzadType" => $fakeData["samorzadType"]
		]);

		$this->assertDatabaseHas("users", [
			"username" => $fakeData["username"],
			"name" => $fakeData["name"],
			"classunit_id" => $fakeData["classunit_id"],
			"type" => "nauczyciel",
			"samorzadType" => $fakeData["samorzadType"],
			"notManagingAClass" => false
		]);
	}

	protected function getFakeUserData(): array
	{
		$types = ["student", "przewodniczacy", "nauczyciel", "wiceprzewodniczacy", "skarbnik"];

		return [
			"username" => $this->faker->userName(),
			"name" => $this->faker->name(),
			"classunit_id" => 1,
			"type" => $types[array_rand($types)],
			"samorzadType" => $types[array_rand($types)]
		];
	}
}
