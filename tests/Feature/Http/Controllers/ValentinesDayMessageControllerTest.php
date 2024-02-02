<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ValentinesDayMessageControllerTest extends TestCase
{
	use WithFaker;

	/** @test */
    public function can_display_index(): void
    {
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));

        $response = $this->get("/valentine");

        $response->assertStatus(200);
		$response->assertViewIs("valentine.index");
    }

	/** @test */
	public function can_send_a_latter(): void {
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));

		$recipient = $this->faker->name();
		$content = $this->faker()->text(random_int(32, 512));

		$response = $this->post("/valentine", [
			"recipient" => $recipient,
			"content" => $content
		]);

		$response->assertStatus(302);
		$response->assertSessionHas("confirmation");

		$this->assertDatabaseHas("valentines_day_messages", [
			"recipient" => $recipient,
			"content" => $content
		]);
	}

	/** @test */
	public function can_export_messages(): void {
		Auth::login(User::factory()->create([
			"isAdministrator" => true
		]));

		$response = $this->get("/valentine/export");
		$response->assertStatus(200);
		$response->assertHeader("Content-Type", "application/pdf");
	}
}
