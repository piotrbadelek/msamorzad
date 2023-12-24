<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Announcement;
use App\Models\Classunit;
use App\Models\Contest;
use App\Models\Message;
use App\Models\Payment;
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
		\App\Models\User::factory(10)->create();
		Message::factory(30)->create();
		Contest::factory(6)->create();
		Announcement::factory(5)->create();

		\App\Models\User::factory()->create([
			'username' => 'test.teacher',
			'type' => 'nauczyciel',
			"notManagingAClass" => false
		]);

		\App\Models\User::factory()->create([
			'username' => 'test.admin',
			'type' => 'student',
			'samorzadType' => 'student',
			"isAdministrator" => true
		]);

		// \App\Models\User::factory()->create([
		//     'name' => 'Test User',
		//     'email' => 'test@example.com',
		// ]);
	}
}
