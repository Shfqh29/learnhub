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
    ];

    // optional, relation ke instructor (users table nanti)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'course_id');
    }
}
