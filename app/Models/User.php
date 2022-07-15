<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'is_admin'
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
    ];

    protected static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->social()->create([
                "xp" => 2500
            ]);
        });
    }

    public function social() {
        return $this->hasOne(\App\Models\UserSocial::class);
    }

    public function sessions() {
        return $this->hasMany(\App\Models\Session::class);
    }

    public function getActiveSessionsAttribute() {
        return $this->sessions()->where("status", "in progress")->get() ?? collect([]);
    }

    public function takenQuiz($quiz_id) {
        $sessions = $this->sessions()->where("status", "complete")->where("quiz_id", $quiz_id)->get();
        return ($sessions->count() > 0);
    }
}
