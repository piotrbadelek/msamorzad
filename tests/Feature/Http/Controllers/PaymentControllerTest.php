<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
	protected function generateSkarbnik(): User
	{
		$user = User::factory()->create([
			"hasNotChangedPassword" => false,
			"type" => "skarbnik",
			"classunit_id" => 1
		]);
		Auth::login($user);
		return $user;
	}

	/** @test */
	public function lists_payments()
	{
		$this->generateSkarbnik();
		$response = $this->get("/skladki");
		$response->assertStatus(200);
		$response->assertViewIs("payment.list");
	}

	/** @test */
	public function deny_listing_payments_for_teachers_without_a_class()
	{
		$user = User::factory()->create([
			"type" => "nauczyciel",
			"notManagingAClass" => true
		]);
		Auth::login($user);

		$response = $this->get("/skladki");
		$response->assertStatus(403);
	}

	/** @test */
	public function display_payment_details()
	{
		$this->generateSkarbnik();

		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$response = $this->get("/skladki/" . $payment->id);
		$response->assertStatus(200);
		$response->assertViewIs("payment.show");
	}

	/** @test */
	public function deny_payment_details_to_students()
	{
		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$user = User::factory()->create([
			"classunit_id" => 1
		]);
		Auth::login($user);

		$response = $this->get("/skladki/" . $payment->id);
		$response->assertStatus(403);
	}

	/** @test */
	public function deny_payment_details_to_other_classes()
	{
		$payment = Payment::factory()->create([
			"classunit_id" => 2
		]);

		$user = User::factory()->create([
			"classunit_id" => 1
		]);
		Auth::login($user);

		$response = $this->get("/skladki/" . $payment->id);
		$response->assertStatus(403);
	}

	/** @test */
	public function mark_user_as_paid()
	{
		$user = $this->generateSkarbnik();

		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$response = $this->get("/skladki/" . $payment->id . "/" . $user->id);
		$response->assertStatus(302);

		$payment->refresh();
		$this->assertTrue(in_array($user->id, json_decode($payment->paid)));
	}

	/** @test */
	public function mark_user_as_not_paid()
	{
		$user = $this->generateSkarbnik();

		$payment = Payment::factory()->create([
			"classunit_id" => 1,
			"paid" => json_encode([$user->id])
		]);

		$response = $this->get("/skladki/" . $payment->id . "/" . $user->id);
		$response->assertStatus(302);

		$payment->refresh();
		$this->assertTrue(!in_array($user->id, json_decode($payment->paid)));
	}

	/** @test */
	public function prohibit_marking_students_from_other_classes_as_paid()
	{
		$this->generateSkarbnik();
		$user = User::factory()->create([
			"classunit_id" => 2
		]);
		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);
		$response = $this->get("/skladki/" . $payment->id . "/" . $user->id);
		$response->assertStatus(400);
	}

	/** @test */
	public function prohibit_regular_students_marking_students_as_paid()
	{
		$this->generateSkarbnik();
		$user = User::factory()->create([
			"classunit_id" => 1,
			"type" => "student"
		]);

		Auth::login($user);

		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);
		$response = $this->get("/skladki/" . $payment->id . "/" . $user->id);
		$response->assertStatus(403);
	}

	/** @test */
	public function show_payment_creation_form()
	{
		$this->generateSkarbnik();
		$response = $this->get("/skladki/new");

		$response->assertStatus(200);
		$response->assertViewIs("payment.create");
	}

	/** @test */
	public function deny_showing_payment_creation_form_to_regular_students()
	{
		$user = User::factory()->create([
			"classunit_id" => 1,
			"samorzadType" => "student"
		]);
		Auth::login($user);
		$response = $this->get("/skladki/new");

		$response->assertStatus(403);
	}

	/** @test */
	public function show_payment_deletion_form()
	{
		$this->generateSkarbnik();

		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$response = $this->get("/skladki/{$payment->id}/delete");

		$response->assertStatus(200);
		$response->assertViewIs("payment.delete");
	}

	/** @test */
	public function deny_payment_deletion_form_to_other_classes()
	{
		$this->generateSkarbnik();

		$payment = Payment::factory()->create([
			"classunit_id" => 2
		]);

		$response = $this->get("/skladki/{$payment->id}/delete");

		$response->assertStatus(403);
	}

	/** @test */
	public function create_payment()
	{
		$user = $this->generateSkarbnik();

		$amount = rand(1, 999);
		$deadline = date('Y-m-d H:i:s', time() + 604800);

		$response = $this->post("/skladki/new", [
			"money" => $amount,
			"title" => "test123",
			"deadline" => $deadline
		]);

		$response->assertStatus(302);
		$this->assertDatabaseHas("payments", [
			"amount" => $amount,
			"title" => "test123",
			"deadline" => $deadline,
			"classunit_id" => $user->classunit_id
		]);
	}

	/** @test */
	public function deny_creating_payment_to_regular_students()
	{
		$user = User::factory()->create([
			"classunit_id" => 1,
			"samorzadType" => "student"
		]);
		Auth::login($user);

		$amount = rand(1, 999);
		$deadline = date('Y-m-d H:i:s', time() + 604800);

		$response = $this->post("/skladki/new", [
			"money" => $amount,
			"title" => "test123",
			"deadline" => $deadline
		]);

		$response->assertStatus(403);
	}

	/** @test */
	public function delete_payment()
	{
		$this->generateSkarbnik();
		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$response = $this->delete("/skladki/{$payment->id}");

		$response->assertStatus(302);
		$this->assertDatabaseMissing("payments", [
			"id" => $payment->id
		]);
	}

	/** @test */
	public function deny_deleting_payment_to_regular_students()
	{
		$user = User::factory()->create([
			"classunit_id" => 1
		]);
		Auth::login($user);

		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$response = $this->delete("/skladki/{$payment->id}");

		$response->assertStatus(403);
	}

	/** @test */
	public function generate_payment_confirmation()
	{
		$this->generateSkarbnik();
		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$response = $this->get("/skladki/{$payment->id}/pdf");
		$response->assertStatus(200);
		$response->assertHeader("Content-Type", "application/pdf");
		$response->assertHeader("Content-Disposition", "attachment; filename=\"payment_closed_" . Str::slug($payment->title) . ".pdf\"");
	}

	/** @test */
	public function deny_generating_payment_confirmations_to_regular_students() {
		$payment = Payment::factory()->create([
			"classunit_id" => 1
		]);

		$user = User::factory()->create([
			"classunit_id" => 1
		]);
		Auth::login($user);
		$response = $this->get("/skladki/{$payment->id}/pdf");
		$response->assertStatus(403);
	}

	/** @test */
	public function deny_generating_payment_confirmations_to_users_from_different_classes() {
		$this->generateSkarbnik();
		$payment = Payment::factory()->create([
			"classunit_id" => 2
		]);

		$response = $this->get("/skladki/{$payment->id}/pdf");
		$response->assertStatus(403);
	}
}
