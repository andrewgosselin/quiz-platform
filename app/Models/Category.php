<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        "name", "image"
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = \Illuminate\Support\Str::slug($model->name);
        });
    }

    public function quizzes() {
        return $this->hasMany(\App\Models\Quiz::class);
    }
}
