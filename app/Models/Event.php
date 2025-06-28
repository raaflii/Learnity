<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'type',
        'color',
        'all_day',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Auto set user_id when creating event
        static::creating(function ($event) {
            if (!$event->user_id && Auth::check()) {
                $event->user_id = Auth::id();
            }
        });
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter berdasarkan bulan (yang diperlukan controller)
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('start_time', $year)
                    ->whereMonth('start_time', $month)
                    ->where('user_id', Auth::id())
                    ->orderBy('start_time');
    }

    // Scope untuk upcoming events (yang diperlukan controller)
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', Carbon::now())
                    ->where('user_id', Auth::id())
                    ->orderBy('start_time');
    }

    // Scope untuk filter berdasarkan status
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Scope untuk filter berdasarkan type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope untuk filter berdasarkan user (jika diperlukan admin)
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessor untuk format tanggal yang user-friendly
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time->format('d M Y H:i');
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time->format('d M Y H:i');
    }

    // Check apakah event sedang berlangsung
    public function getIsOngoingAttribute()
    {
        $now = Carbon::now();
        return $now->between($this->start_time, $this->end_time);
    }

    // Check apakah event sudah lewat
    public function getIsPastAttribute()
    {
        return Carbon::now()->gt($this->end_time);
    }

    // Check apakah event upcoming
    public function getIsUpcomingAttribute()
    {
        return Carbon::now()->lt($this->start_time);
    }

    // Get duration in minutes
    public function getDurationInMinutesAttribute()
    {
        return Carbon::parse($this->start_time)->diffInMinutes(Carbon::parse($this->end_time));
    }

    // Get duration formatted
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration_in_minutes;
        
        if ($minutes < 60) {
            return $minutes . ' minutes';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes == 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        }
        
        return $hours . 'h ' . $remainingMinutes . 'm';
    }
}