<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    public function getIsAdminAttribute() {
        return $this->type != "student";
    }

	/**
	 * Return if the user has a position in the school council or is a teacher
	 * @return bool
	 */
	public function getIsSamorzadAttribute() {
		return $this->samorzadType != "student";
	}

	/**
	 * Return if the user either has a position in the school council, has a position in the class council, or is a teacher
	 * @return bool
	 */
	public function getIsPrivilegedAttribute() {
		return $this->isAdmin || $this->isSamorzad;
	}

	/**
	 * Return if the user is a teacher
	 * @return bool
	 */
	public function getIsTeacherAttribute() {
		return $this->type == "nauczyciel";
	}
}
