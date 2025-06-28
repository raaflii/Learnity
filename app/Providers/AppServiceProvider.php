<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('siswa.components.stats-card', 'stats-card');
        Blade::component('siswa.components.layout', 'layout');
        Blade::component('siswa.components.calendar-modal', 'calendar-modal');
        Blade::component('siswa.components.course-card', 'siswa.course-card');
        Blade::component('siswa.components.course-modal', 'course-modal');
        Blade::component('siswa.components.header', 'header');
        Blade::component('siswa.components.sidebar-content', 'sidebar-content');
        Blade::component('siswa.components.sidebar', 'sidebar');
        Blade::component('siswa.components.my-course-card', 'my-course-card');
        Blade::component('siswa.components.edit-profile', 'edit-profile');
        Blade::component('admin.components.theme-toggle', 'theme-toggle');

        Blade::component('admin.components.theme-toggle', 'admin.theme-toggle');
        Blade::component('admin.components.layout', 'admin.layout');
        Blade::component('admin.components.header', 'admin.header');
        Blade::component('admin.components.sidebar-content', 'admin.sidebar-content');
        Blade::component('admin.components.sidebar', 'admin.sidebar');
        Blade::component('admin.components.stat-card', 'admin.stat-card');

        Blade::component('pengajar.components.theme-toggle', 'pengajar.theme-toggle');
        Blade::component('pengajar.components.layout', 'pengajar.layout');
        Blade::component('pengajar.components.header', 'pengajar.header');
        Blade::component('pengajar.components.sidebar-content', 'pengajar.sidebar-content');
        Blade::component('pengajar.components.sidebar', 'pengajar.sidebar');
        Blade::component('pengajar.components.detail-course', 'pengajar.detail-course');

        Blade::component('landing.components.course-card', 'course-card');
        Blade::component('landing.components.cta-section', 'cta-section');
        Blade::component('landing.components.feature-card', 'feature-card');
        Blade::component('landing.components.featured-course', 'featured-course');
        Blade::component('landing.components.header', 'header');
        Blade::component('landing.components.footer', 'footer');
        Blade::component('landing.components.hero-section', 'hero-section');
        Blade::component('landing.components.light-dark-mode-toggle', 'light-dark-mode-toggle');
        Blade::component('landing.components.pricing', 'pricing');
        Blade::component('landing.components.quality-section', 'quality-section');
        Blade::component('landing.components.stat', 'stat');
        Blade::component('landing.components.testimonial-card', 'testimonial-card');
        Blade::component('landing.components.testimonial-section', 'testimonial-section');
        
        // Uncomment dibawah ini untuk enroll pake ngrok
        // if (config('app.env') === 'production' || 
        //     request()->header('x-forwarded-proto') === 'https' ||
        //     str_contains(config('app.url'), 'ngrok')) {
        //     URL::forceScheme('https');
        // }
    }
}
