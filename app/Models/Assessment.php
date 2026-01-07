<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'total_marks',
        'duration',
        'attempts_allowed',
        'show_scores',
        'start_date',
        'due_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'show_scores' => 'boolean'
    ];

    // Relationships
    public function course()
    {
        // Change 'Course' to your actual model name if different
        // For example: return $this->belongsTo(ManageCourse::class, 'course_id');
        return $this->belongsTo(ManageCourse::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    // Helper methods
    public function isQuizType()
    {
        return in_array($this->type, ['quiz', 'exam']);
    }

    public function isAssignmentType()
    {
        return in_array($this->type, ['assignment', 'lab_exercise']);
    }

    public function isOpen()
    {
        $now = now();
        return $this->status === 'published'
            && ($this->start_date === null || $this->start_date <= $now)
            && ($this->due_date === null || $this->due_date >= $now);
    }

    public function getStudentAttempts($userId)
    {
        return $this->attempts()->where('user_id', $userId)->count();
    }

    public function canStudentAttempt($userId)
    {
        $attemptCount = $this->getStudentAttempts($userId);
        return $attemptCount < $this->attempts_allowed;
    }
}
