<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'thumbnail',
        'price',
        'level',
        'duration_hours',
        'instructor_id',
        'category_id',
        'is_published',
        'is_featured',
        'requirements',
        'what_you_learn',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
                    ->withPivot(['enrolled_at', 'completed_at', 'progress_percentage', 'last_accessed_at'])
                    ->withTimestamps();
    }

    public function topics()
    {
        return $this->hasMany(CourseTopic::class)->orderBy('order_index');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function progressLogs()
    {
        return $this->hasMany(CourseProgressLog::class);
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    public function scopePaid($query)
    {
        return $query->where('price', '>', 0);
    }

    // Accessors
    public function getIsFreeAttribute()
    {
        return $this->price == 0;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getRatingSummaryAttribute()
    {
        return [
            'average' => $this->average_rating,
            'total' => $this->total_reviews,
        ];
        
    }

    public function getLessonsAttribute()
    {
        return $this->topics->flatMap->videos;
    }

    // Helper methods
    public function getTotalVideos()
    {
        return $this->topics()->withCount('videos')->get()->sum('videos_count');
    }

    public function getTotalDuration()
    {
        return $this->topics()
                    ->with('videos')
                    ->get()
                    ->pluck('videos')
                    ->flatten()
                    ->sum('duration_seconds');
    }
}