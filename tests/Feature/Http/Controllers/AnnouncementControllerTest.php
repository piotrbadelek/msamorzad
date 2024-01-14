<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AnnouncementControllerTest extends TestCase
{
	use WithFaker;

	/** @test */
	public function announcements_can_be_listed()
	{
		$user = User::factory()->create([
			"type" => "przewodniczacy",
			"samorzadType" => "przewodniczacy"
		]);
		Auth::login($user);
		$response = $this->get("/announcements");
		$response->assertStatus(200);
		$response->assertViewIs("announcement.list");
	}

	/** @test */
	public function announcements_can_be_listed_by_unprivileged_users()
	{
		$user = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$response = $this->get("/announcements");
		$response->assertStatus(200);
		$response->assertViewIs("announcement.list");
	}

	/** @test */
	public function announcement_create_form_can_be_shown()
	{
		$user = User::factory()->create([
			"samorzadType" => "przewodniczacy"
		]);
		Auth::login($user);
		$response = $this->get("/announcements/new");
		$response->assertStatus(200);
		$response->assertViewIs("announcement.create");
	}

	/** @test */
	public function announcement_create_form_cannot_be_shown_to_students()
	{
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$response = $this->get("/announcements/new");
		$response->assertStatus(403);
	}

	/** @test */
	public function global_announcement_can_be_created()
	{
		$user = User::factory()->create([
			"samorzadType" => "przewodniczacy"
		]);
		Auth::login($user);

		$title = $this->faker->text(64);
		$description = $this->faker->text(256);

		$response = $this->post("/announcements/new", [
			"title" => $title,
			"description" => $description,
			"postArea" => "school"
		]);
		$response->assertStatus(302);
		$this->assertDatabaseHas("announcements", [
			"title" => $title,
			"description" => $description,
			"global" => true
		]);
	}

	/** @test */
	public function classwide_announcement_can_be_created()
	{
		$user = User::factory()->create([
			"type" => "przewodniczacy"
		]);
		Auth::login($user);

		$title = $this->faker->text(64);
		$description = $this->faker->text(256);

		$response = $this->post("/announcements/new", [
			"title" => $title,
			"description" => $description,
			"postArea" => "class"
		]);
		$response->assertStatus(302);
		$this->assertDatabaseHas("announcements", [
			"title" => $title,
			"description" => $description,
			"global" => false
		]);
	}

	/** @test */
	public function classwide_announcement_cannot_be_created_by_unprivileged_users()
	{
		$user = User::factory()->create([
			"type" => "student",
			"samorzadType" => "przewodniczacy"
		]);
		Auth::login($user);

		$response = $this->post("/announcements/new", [
			"title" => "test",
			"description" => "test",
			"postArea" => "class"
		]);
		$response->assertStatus(400);
	}

	/** @test */
	public function global_announcement_cannot_be_created_by_unprivileged_users()
	{
		$user = User::factory()->create([
			"type" => "przewodniczacy",
			"samorzadType" => "student"
		]);
		Auth::login($user);

		$response = $this->post("/announcements/new", [
			"title" => "test",
			"description" => "test",
			"postArea" => "school"
		]);
		$response->assertStatus(400);
	}

	/** @test */
	public function announcement_cannot_be_created_by_a_student()
	{
		$user = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student"
		]);
		Auth::login($user);

		$response = $this->post("/announcements/new", [
			"title" => "test",
			"description" => "test",
			"postArea" => "class"
		]);
		$response->assertStatus(403);
	}

	/** @test */
	public function announcement_deletion_form_can_be_shown()
	{
		$user = User::factory()->create([
			"type" => "przewodniczacy"
		]);
		Auth::login($user);
		$announcement = Announcement::factory()->create([
			"global" => 0
		]);
		$response = $this->get("/announcements/{$announcement->id}/delete");
		$response->assertStatus(200);
		$response->assertViewIs("announcement.delete");
	}

	/** @test */
	public function announcement_deletion_form_cannot_be_shown_to_users_from_other_classes()
	{
		$user = User::factory()->create([
			"type" => "przewodniczacy",
			"classunit_id" => 1
		]);
		Auth::login($user);
		$announcement = Announcement::factory()->create([
			"global" => 0,
			"classunit_id" => 2
		]);
		$response = $this->get("/announcements/{$announcement->id}/delete");
		$response->assertStatus(403);
	}

	/** @test */
	public function announcement_deletion_form_cannot_be_shown_to_regular_students()
	{
		$user = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$announcement = Announcement::factory()->create([
			"global" => 0
		]);
		$response = $this->get("/announcements/{$announcement->id}/delete");
		$response->assertStatus(403);
	}

	/** @test */
	public function announcement_can_be_deleted()
	{
		$user = User::factory()->create([
			"type" => "przewodniczacy",
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$announcement = Announcement::factory()->create([
			"global" => 0
		]);
		$response = $this->delete("/announcements/{$announcement->id}");
		$response->assertStatus(302);
	}

	/** @test */
	public function announcement_cannot_be_deleted_by_a_student()
	{
		$user = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$announcement = Announcement::factory()->create([
			"global" => 1
		]);
		$response = $this->delete("/announcements/{$announcement->id}");
		$response->assertStatus(403);
	}
}
