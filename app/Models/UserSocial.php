<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    use HasFactory;

    public $total_xp = 1000;

    protected $fillable = [
        "avatar", "coins", "xp"
    ];

    public function getLevelAttribute() {
        return floor($this->xp / $this->total_xp);
    }

    public function getCurrentXpAttribute() {
        return $this->xp - ($this->level * $this->total_xp);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function addCoins($amount) {
        $this->coins += $amount;
        $this->save();
    }
    public function addXp($amount) {
        $this->xp += $amount;
        $this->save();
    }
}
