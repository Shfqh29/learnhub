<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $table = 'titles'; // 🔴 IMPORTANT (force table)

    protected $fillable = [
        'course_id',
        'title',
    ];

    public function contents()
    {
        return $this->hasMany(Content::class, 'title_id');
    }
}
