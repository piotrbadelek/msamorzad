<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classunit extends Model
{
	use HasFactory;

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}

	public function getTeacherAttribute()
	{
		return User::where("classunit_id", $this->id)->where("type", "nauczyciel")->first();
	}

	public function getSamorzadAttribute()
	{
		return User::where("classunit_id", $this->id)->whereIn("type", ["skarbnik", "wiceprzewodniczacy", "przewodniczacy", "nauczyciel"])->get();
	}
}
