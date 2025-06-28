<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'avatar',
        'role_id',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function coursesAsInstructor()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
                    ->withPivot(['enrolled_at', 'completed_at', 'progress_percentage', 'last_accessed_at'])
                    ->withTimestamps();
    }

    public function progressLogs()
    {
        return $this->hasMany(CourseProgressLog::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function courseReviews()
{
    return $this->hasMany(CourseReview::class);
}

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isTeacher()
    {
        return $this->role->name === 'teacher';
    }

    public function isStudent()
    {
        return $this->role->name === 'student';
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Scope untuk user dengan events
    public function scopeWithEvents($query)
    {
        return $query->with('events');
    }

    // Method untuk mendapatkan events hari ini
    public function getTodayEvents()
    {
        return $this->events()
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();
    }

    // Method untuk mendapatkan upcoming events
    public function getUpcomingEvents($limit = 5)
    {
        return $this->events()
            ->where('start_time', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }
}