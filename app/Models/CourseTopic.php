<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order_index',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function videos()
    {
        return $this->hasMany(TopicVideo::class, 'topic_id')->orderBy('order_index');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}