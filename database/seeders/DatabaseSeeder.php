<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Announcement;
use App\Models\Classunit;
use App\Models\Contest;
use App\Models\Message;
use App\Models\Payment;
use App\Models\User;
use App\Models\ValentinesDayMessage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		Classunit::factory(5)->create();
		Payment::factory(8)->create();
		User::factory(10)->create();
		Message::factory(30)->create();
		Contest::factory(6)->create();
		Announcement::factory(5)->create();
		ValentinesDayMessage::factory(16)->create();

		User::factory()->create([
			'username' => 'test.teacher',
			'type' => 'nauczyciel',
			"notManagingAClass" => false,
			"isAdministrator" => true
		]);

		User::factory()->create([
			'username' => 'test.admin',
			'type' => 'student',
			'samorzadType' => 'student',
			"isAdministrator" => true
		]);

		$ghostClassunit = Classunit::factory()->create([
			"name" => "Brak klasy"
		]);

		User::factory()->create([
			"name" => "Konto usunięte",
			'username' => 'ghost',
			'type' => 'student',
			'samorzadType' => 'student',
			"isAdministrator" => true,
			"classunit_id" => $ghostClassunit->id
		]);

		// \App\Models\User::factory()->create([
		//     'name' => 'Test User',
		//     'email' => 'test@example.com',
		// ]);
	}
}
