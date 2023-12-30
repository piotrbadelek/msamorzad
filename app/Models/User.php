<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	use HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
		"hasNotChangedPassword"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function classUnit() {
        return $this->belongsTo(Classunit::class, "classunit_id");
    }

	/**
	 * Return if the user has a position in the class council or is a teacher.
	 * @return bool
	 */
    public function getIsSamorzadKlasowyAttribute(): bool {
        return $this->type != "student";
    }

	/**
	 * Return if the user has a position in the school council or is a teacher
	 * @return bool
	 */
	public function getIsSamorzadSzkolnyAttribute(): bool {
		return $this->samorzadType != "student";
	}

	/**
	 * Return if the user either has a position in the school council, has a position in the class council, or is a teacher
	 * @return bool
	 */
	public function getIsPrivilegedAttribute(): bool {
		return $this->isSamorzadKlasowy || $this->isSamorzadSzkolny;
	}

	/**
	 * Return if the user is a teacher
	 * @return bool
	 */
	public function getIsTeacherAttribute(): bool {
		return $this->type == "nauczyciel";
	}
}
