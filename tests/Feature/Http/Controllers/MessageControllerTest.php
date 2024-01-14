<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
	use WithFaker;

	protected function generateSchoolCouncilMember(): User
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"samorzadType" => "skarbnik",
			"classunit_id" => 1
		]);
		Auth::login($user);
		return $user;
	}

	/** @test */
	public function lists_messages()
	{
		Auth::login(User::factory()->create());
		$response = $this->get("/messages");
		$response->assertStatus(200);
		$response->assertViewIs("message.list");
	}

	/** @test */
	public function creates_message()
	{
		$user = User::factory()->create();
		Auth::login($user);
		$question = $this->faker->text(256);
		$response = $this->post("/messages", [
			"question" => $question
		]);
		$response->assertStatus(302);
		$this->assertDatabaseHas("messages", [
			"question" => $question,
			"user_id" => $user->id
		]);
	}

	/** @test */
	public function shows_message()
	{
		$this->generateSchoolCouncilMember();
		$message = Message::factory()->create();
		$response = $this->get("/messages/{$message->id}");
		$response->assertStatus(200);
		$response->assertViewIs("message.show");
	}

	/** @test */
	public function prohibit_regular_students_from_seeing_messages()
	{
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$message = Message::factory()->create();
		$response = $this->get("/messages/{$message->id}");
		$response->assertStatus(403);
	}

	/** @test */
	public function update_message()
	{
		$this->generateSchoolCouncilMember();
		$message = Message::factory()->create();
		$messageResponse = $this->faker->text(256);
		$response = $this->patch("/messages/{$message->id}", [
			"response" => $messageResponse
		]);
		$response->assertStatus(302);
		$this->assertDatabaseHas("messages", [
			"id" => $message->id,
			"question" => $message->question,
			"response" => $messageResponse,
			"user_id" => $message->user_id
		]);
	}

	/** @test */
	public function deny_updating_messages_to_regular_students() {
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$message = Message::factory()->create();
		$response = $this->patch("/messages/{$message->id}", [
			"response" => "test123"
		]);
		$response->assertStatus(403);
	}

	/** @test */
	public function display_message_deletion_form() {
		$this->generateSchoolCouncilMember();
		$message = Message::factory()->create();
		$response = $this->get("/messages/{$message->id}/delete");
		$response->assertStatus(200);
		$response->assertViewIs("message.delete");
	}

	/** @test */
	public function deny_showing_message_deletion_form_to_regular_students() {
		$user = User::factory()->create([
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$message = Message::factory()->create();
		$response = $this->get("/messages/{$message->id}/delete");
		$response->assertStatus(403);
	}

	/** @test */
	public function delete_message() {
		$this->generateSchoolCouncilMember();
		$message = Message::factory()->create();
		$response = $this->delete("/messages/{$message->id}");
		$response->assertStatus(302);
		$this->assertDatabaseMissing("messages", [
			"id" => $message->id
		]);
	}

	/** @test */
	public function prohibit_deleting_messages_by_class_councils() {
		$user = User::factory()->create([
			"samorzadType" => "student",
			"type" => "przewodniczacy"
		]);
		Auth::login($user);
		$message = Message::factory()->create();
		$response = $this->delete("/messages/{$message->id}");
		$response->assertStatus(403);
	}
}
