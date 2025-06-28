<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TopicVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'description',
        'video_type',
        'video_path',
        'duration_seconds',
        'order_index',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // Relationships
    public function topic()
    {
        return $this->belongsTo(CourseTopic::class, 'topic_id');
    }

    public function progressLogs()
    {
        return $this->hasMany(CourseProgressLog::class);
    }

    public function progress()
    {
        return $this->hasMany(VideoProgress::class, 'video_id');
    }

    public function userProgress()
    {
        return $this->hasOne(VideoProgress::class, 'video_id')
                    ->where('user_id', Auth::id());
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getVideoEmbedUrlAttribute()
    {
        if ($this->video_type === 'youtube') {
            $videoId = $this->extractYouTubeId($this->video_path);
            return "https://www.youtube.com/embed/{$videoId}";
        }
        
        return $this->video_path;
    }

    // Helper methods
    private function extractYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $url, $matches);
        return $matches[1] ?? '';
    }
}