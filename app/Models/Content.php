<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'contents'; // 🔴 IMPORTANT

    protected $fillable = [
    'course_id',
    'title_id',
    'item_name',
    'description',
    'content_type',
    'file_path',
    'file_type',
];

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }
}
