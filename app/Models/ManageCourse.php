<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageCourse extends Model
{
    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'difficulty',
        'image_url',
        'status_course',
        'coordinator',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
