<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'todo',
        'completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCompletedAttribute($value)
    {
        return $value == '1' ? true : false;
    }

    public function getFileAttribute($value)
    {
        return $value ? asset(Storage::url($value)) : null;
    }
}
