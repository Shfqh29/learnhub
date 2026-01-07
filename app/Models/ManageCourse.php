<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageCourse extends Model
{
    protected $table = 'manage_courses';

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'difficulty',
        'image_url',
        'status_course',
        'coordinator',
        'form', // ✅ THIS MUST EXIST
    ];

    // Relation to teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relation to contents (Module 3)
    public function contents()
    {
        return $this->hasMany(Content::class, 'course_id');
    }

    // Relation to assessments (Module 4)
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'course_id');
    }
}

