<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TutorControllerTest extends TestCase
{
	protected function generateTutor(): User
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"type" => "nauczyciel",
			"classunit_id" => 1,
			"notManagingAClass" => false
		]);
		Auth::login($user);
		return $user;
	}

	/** @test */
	public function lists_students(): void
	{
		$this->generateTutor();
		$response = $this->get("/tutor/students");

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.list");
	}

	/** @test */
	public function denies_listing_students_to_regular_students(): void {
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"type" => "student",
			"classunit_id" => 1,
			"notManagingAClass" => true
		]);
		Auth::login($user);

		$response = $this->get("/tutor/students/");

		$response->assertStatus(403);
	}

	/** @test */
	public function shows_student_details(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id");

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.show");
	}

	/** @test */
	public function denies_showing_student_details_from_other_classes(): void {
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 2
		]);

		$response = $this->get("/tutor/students/$student->id");

		$response->assertStatus(403);
	}

	/** @test */
	public function denies_showing_student_details_to_users_who_are_not_tutors(): void {
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"type" => "student",
			"classunit_id" => 1,
			"notManagingAClass" => true
		]);
		Auth::login($user);

		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id");

		$response->assertStatus(403);
	}

	/** @test */
	public function resets_a_students_password(): void {
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id/reset_password");

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.password_changed");
	}

	/** @test */
	public function denies_resetting_a_students_password_to_a_regular_student(): void
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"type" => "student",
			"classunit_id" => 1,
			"notManagingAClass" => true
		]);
		Auth::login($user);

		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id/reset_password");

		$response->assertStatus(403);
	}
}
