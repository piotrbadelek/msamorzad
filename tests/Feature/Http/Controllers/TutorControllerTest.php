<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TutorControllerTest extends TestCase
{
	use WithFaker;

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

	protected function generateStudent(): User
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1,
			"notManagingAClass" => true
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
	public function denies_listing_students_to_regular_students(): void
	{
		$this->generateStudent();

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
	public function denies_showing_student_details_from_other_classes(): void
	{
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
	public function denies_showing_student_details_to_users_who_are_not_tutors(): void
	{
		$this->generateStudent();

		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id");

		$response->assertStatus(403);
	}

	/** @test */
	public function resets_a_students_password(): void
	{
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
		$this->generateStudent();

		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id/reset_password");

		$response->assertStatus(403);
	}

	/** @test */
	public function shows_student_update_form(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id/update");

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.update");
	}

	/** @test */
	public function denies_showing_student_update_form_to_teachers_from_other_classes(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 2
		]);

		$response = $this->get("/tutor/students/$student->id/update");

		$response->assertStatus(403);
	}

	/** @test */
	public function updates_student_info(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$types = ["student", "przewodniczacy", "wiceprzewodniczacy", "skarbnik"];
		$username = $this->faker()->userName();
		$name = $this->faker()->name();
		$type = $types[array_rand($types)];

		$response = $this->patch("/tutor/students/$student->id", [
			"username" => $username,
			"name" => $name,
			"type" => $type
		]);

		$response->assertStatus(302);
		$this->assertDatabaseHas("users", [
			"username" => $username,
			"name" => $name,
			"type" => $type,
			"id" => $student->id
		]);
	}

	/** @test */
	public function denies_changing_student_type_to_teacher(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$username = $this->faker()->userName();
		$name = $this->faker()->name();
		$type = "nauczyciel";

		$response = $this->patch("/tutor/students/$student->id", [
			"username" => $username,
			"name" => $name,
			"type" => $type
		]);

		$response->assertStatus(400);
	}

	/** @test */
	public function denies_updating_student_info_to_regular_students(): void
	{
		$this->generateStudent();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$types = ["student", "przewodniczacy", "wiceprzewodniczacy", "skarbnik"];
		$username = $this->faker()->userName();
		$name = $this->faker()->name();
		$type = $types[array_rand($types)];

		$response = $this->patch("/tutor/students/$student->id", [
			"username" => $username,
			"name" => $name,
			"type" => $type
		]);

		$response->assertStatus(403);
	}

	/** @test */
	public function shows_student_deletion_form(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->get("/tutor/students/$student->id/delete");

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.delete");
	}

	/** @test */
	public function denies_showing_deletion_forms_of_students_belonging_to_other_classes(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 2
		]);

		$response = $this->get("/tutor/students/$student->id/delete");

		$response->assertStatus(403);
	}

	/** @test */
	public function deletes_student(): void
	{
		$this->generateTutor();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->delete("/tutor/students/$student->id");

		$response->assertStatus(302);
		$this->assertDatabaseMissing("users", [
			"id" => $student->id,
			"username" => $student->username
		]);
	}

	/** @test */
	public function denies_deleting_student_to_regular_users(): void
	{
		$this->generateStudent();
		$student = User::factory()->create([
			"type" => "student",
			"samorzadType" => "student",
			"classunit_id" => 1
		]);

		$response = $this->delete("/tutor/students/$student->id");

		$response->assertStatus(403);
	}

	/** @test */
	public function denies_deleting_own_user(): void {
		$tutor = $this->generateTutor();

		$response = $this->delete("/tutor/students/$tutor->id");

		$response->assertStatus(400);
	}

	/** @test */
	public function shows_student_creation_form(): void {
		$this->generateTutor();

		$response = $this->get("/tutor/students/new");

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.create");
	}

	/** @test */
	public function denies_showing_student_creation_form_to_regular_students(): void {
		$this->generateStudent();

		$response = $this->get("/tutor/students/new");

		$response->assertStatus(403);
	}

	/** @test */
	public function creates_student(): void {
		$tutor = $this->generateTutor();

		$types = ["student", "przewodniczacy", "wiceprzewodniczacy", "skarbnik"];
		$username = $this->faker()->userName();
		$name = $this->faker()->name();
		$type = $types[array_rand($types)];

		$response = $this->post("/tutor/students", [
			"username" => $username,
			"name" => $name,
			"type" => $type
		]);

		$response->assertStatus(200);
		$response->assertViewIs("tutor.student.created");
		$this->assertDatabaseHas("users", [
			"username" => $username,
			"name" => $name,
			"type" => $type,
			"classunit_id" => $tutor->classunit_id
		]);
	}

	/** @test */
	public function denies_teacher_creation(): void {
		$this->generateTutor();

		$username = $this->faker()->userName();
		$name = $this->faker()->name();
		$type = "nauczyciel";

		$response = $this->post("/tutor/students", [
			"username" => $username,
			"name" => $name,
			"type" => $type
		]);

		$response->assertStatus(400);
	}

	/** @test */
	public function denies_student_creation_to_regular_users(): void {
		$this->generateStudent();

		$username = $this->faker()->userName();
		$name = $this->faker()->name();
		$type = "student";

		$response = $this->post("/tutor/students", [
			"username" => $username,
			"name" => $name,
			"type" => $type
		]);

		$response->assertStatus(403);
	}
}
