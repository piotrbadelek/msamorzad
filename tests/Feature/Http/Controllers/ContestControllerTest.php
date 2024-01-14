<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ContestControllerTest extends TestCase
{
	use WithFaker;

	/** @test */
	public function contests_get_listed()
	{
		Auth::login(User::factory()->create());
		$response = $this->get("/contests");
		$response->assertStatus(200);
		$response->assertViewIs("contest.list");
	}

	/** @test */
	public function contest_gets_shown()
	{
		Auth::login(User::factory()->create());
		$contest = Contest::factory()->create();
		$response = $this->get("/contests/{$contest->id}");
		$response->assertStatus(200);
		$response->assertViewIs("contest.show");
	}

	/** @test */
	public function user_can_enlist_in_contest()
	{
		$user = User::factory()->create();
		Auth::login($user);
		$contest = Contest::factory()->create();
		$response = $this->get("/contests/{$contest->id}/enlist");
		$response->assertStatus(302);
		$contest->refresh();
		$this->assertTrue(in_array($user->id, json_decode($contest->enlisted)));
	}

	/** @test */
	public function user_can_unenlist_from_contest()
	{
		$user = User::factory()->create();
		Auth::login($user);
		$contest = Contest::factory()->create([
			"enlisted" => json_encode([$user->id])
		]);
		$response = $this->get("/contests/{$contest->id}/enlist");
		$response->assertStatus(302);
		$contest->refresh();
		$this->assertTrue(!in_array($user->id, json_decode($contest->enlisted)));
	}

	/** @test */
	public function teacher_cannot_enlist_in_contest()
	{
		$user = User::factory()->create([
			"type" => "nauczyciel"
		]);
		Auth::login($user);
		$contest = Contest::factory()->create();
		$response = $this->get("/contests/{$contest->id}/enlist");
		$response->assertStatus(403);
	}

	/** @test */
	public function contest_can_be_created()
	{
		$user = User::factory()->create([
			"type" => "nauczyciel"
		]);
		Auth::login($user);

		$title = $this->faker->text(64);
		$description = $this->faker()->text(512);

		$response = $this->post("/contests/new", [
			"title" => $title,
			"description" => $description
		]);
		$response->assertStatus(302);
		$this->assertDatabaseHas("contests", [
			"title" => $title,
			"description" => $description
		]);
	}

	/** @test */
	public function contest_cannot_be_created_by_a_student()
	{
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$response = $this->post("/contests/new", [
			"title" => "test123",
			"description" => "test1234"
		]);
		$response->assertStatus(403);
	}

	/** @test */
	public function contest_deletion_form_can_be_shown()
	{
		$user = User::factory()->create([
			"type" => "nauczyciel"
		]);
		Auth::login($user);
		$contest = Contest::factory()->create();
		$response = $this->get("/contests/{$contest->id}/delete");
		$response->assertStatus(200);
	}

	/** @test */
	public function contest_deletion_form_cannot_be_shown_to_a_student()
	{
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$contest = Contest::factory()->create();
		$response = $this->get("/contests/{$contest->id}/delete");
		$response->assertStatus(403);
	}

	/** @test */
	public function contest_can_get_deleted()
	{
		$user = User::factory()->create([
			"type" => "nauczyciel"
		]);
		Auth::login($user);
		$contest = Contest::factory()->create();
		$response = $this->delete("/contests/{$contest->id}");
		$response->assertStatus(302);
		$this->assertDatabaseMissing("contests", [
			"id" => $contest->id
		]);
	}

	/** @test */
	public function contest_cannot_get_deleted_by_a_student()
	{
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$contest = Contest::factory()->create();
		$response = $this->delete("/contests/{$contest->id}");
		$response->assertStatus(403);
	}

	/** @test */
	public function contest_creation_form_can_be_shown() {
		$user = User::factory()->create([
			"type" => "nauczyciel"
		]);
		Auth::login($user);
		$response = $this->get("/contests/new");
		$response->assertStatus(200);
	}

	/** @test */
	public function contest_creation_form_cannot_be_shown_to_a_student() {
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$response = $this->get("/contests/new");
		$response->assertStatus(403);
	}
}
