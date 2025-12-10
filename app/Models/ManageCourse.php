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
    ];

    // optional, relation ke instructor (users table nanti)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
