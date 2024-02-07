<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Payment extends Model
{
	use HasFactory;
	use HasPushSubscriptions;

	protected $fillable = ["paid"];

	public function classUnit()
	{
		return $this->belongsTo(Classunit::class, "classunit_id");
	}

	public function getExcludedStudentsArrayAttribute()
	{
		return json_decode($this->excludedStudents) ?? [];
	}
}
