<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    private function userId()
    {
        if (!Auth::check()) {
            abort(401, 'Unauthorized: Please login');
        }
        return Auth::id();
    }

    public function index(Request $request)
    {
        $user = User::find($this->userId());
        
        // Get current month and year from request or use current
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        // Generate calendar data
        $calendarDays = $this->generateCalendarDays($month, $year);

        return view('siswa.calendar', [
            'title' => 'Calendar',
            'calendarDays' => $calendarDays,
            'currentMonth' => $month,
            'currentYear' => $year
        ]);
    }

    private function generateCalendarDays($month, $year)
    {
        $currentDate = Carbon::create($year, $month, 1);
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        
        // Get start of calendar (might include days from previous month)
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        // Get all events for the calendar period
        $events = Event::whereBetween('start_time', [
                $startOfCalendar->copy()->startOfDay(),
                $endOfCalendar->copy()->endOfDay()
            ])
            ->where('user_id', $this->userId())
            ->orderBy('start_time')
            ->get()
            ->groupBy(function($event) {
                return $event->start_time->format('Y-m-d');
            });
        
        $calendarDays = [];
        $currentDay = $startOfCalendar->copy();
        
        while ($currentDay <= $endOfCalendar) {
            $dateStr = $currentDay->format('Y-m-d');
            
            // Only show events for days in the current month or if it's the same date across months
            $dayEvents = collect();
            if ($currentDay->month == $month) {
                $dayEvents = $events->get($dateStr, collect());
            }
            
            $calendarDays[] = [
                'day' => $currentDay->format('j'),
                'dateStr' => $dateStr,
                'isToday' => $currentDay->isToday(),
                'isCurrentMonth' => $currentDay->month == $month,
                'events' => $dayEvents
            ];
            
            $currentDay->addDay();
        }
        
        return $calendarDays;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'location' => 'nullable|string|max:255',
                'type' => 'required|in:meeting,task,reminder,appointment,other',
                'color' => 'nullable|string|max:7',
                'all_day' => 'boolean'
            ]);

            $validated['user_id'] = $this->userId();
            $validated['status'] = 'scheduled';
            $validated['all_day'] = $validated['all_day'] ?? false;
            $validated['color'] = $validated['color'] ?: '#3B82F6';

            // Convert datetime-local format to proper datetime
            if (isset($validated['start_time'])) {
                $validated['start_time'] = Carbon::parse($validated['start_time']);
            }
            if (isset($validated['end_time'])) {
                $validated['end_time'] = Carbon::parse($validated['end_time']);
            }

            $event = Event::create($validated);

            // Format the response to match frontend expectations
            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_time' => $event->start_time->format('Y-m-d H:i:s'),
                'end_time' => $event->end_time->format('Y-m-d H:i:s'),
                'location' => $event->location,
                'type' => $event->type,
                'color' => $event->color,
                'all_day' => $event->all_day,
                'status' => $event->status,
                'date' => $event->start_time->format('Y-m-d')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully!',
                'event' => $eventData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating event', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error creating event', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event. Please try again.'
            ], 500);
        }
    }

    public function show(Event $event)
    {
        if ($event->user_id !== $this->userId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this event'
            ], 403);
        }

        $eventData = [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'start_time' => $event->start_time->format('Y-m-d H:i:s'),
            'end_time' => $event->end_time->format('Y-m-d H:i:s'),
            'location' => $event->location,
            'type' => $event->type,
            'color' => $event->color,
            'all_day' => $event->all_day,
            'status' => $event->status,
            'date' => $event->start_time->format('Y-m-d')
        ];

        return response()->json([
            'success' => true,
            'event' => $eventData
        ]);
    }

    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== $this->userId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this event'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'location' => 'nullable|string|max:255',
                'type' => 'required|in:meeting,task,reminder,appointment,other',
                'color' => 'nullable|string|max:7',
                'all_day' => 'boolean',
                'status' => 'nullable|in:scheduled,completed,cancelled'
            ]);

            $validated['all_day'] = $validated['all_day'] ?? false;
            $validated['status'] = $validated['status'] ?? 'scheduled';

            // Convert datetime-local format to proper datetime
            if (isset($validated['start_time'])) {
                $validated['start_time'] = Carbon::parse($validated['start_time']);
            }
            if (isset($validated['end_time'])) {
                $validated['end_time'] = Carbon::parse($validated['end_time']);
            }

            $event->update($validated);

            // Format the response to match frontend expectations
            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_time' => $event->start_time->format('Y-m-d H:i:s'),
                'end_time' => $event->end_time->format('Y-m-d H:i:s'),
                'location' => $event->location,
                'type' => $event->type,
                'color' => $event->color,
                'all_day' => $event->all_day,
                'status' => $event->status,
                'date' => $event->start_time->format('Y-m-d')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully!',
                'event' => $eventData
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating event', ['event_id' => $event->id, 'errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . collect($e->errors())->flatten()->first(),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating event', ['event_id' => $event->id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event. Please try again.'
            ], 500);
        }
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== $this->userId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this event'
            ], 403);
        }

        try {
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting event', ['event_id' => $event->id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event. Please try again.'
            ], 500);
        }
    }

    public function getEventsForDate(Request $request)
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));

            // Validate date format
            if (!Carbon::hasFormat($date, 'Y-m-d')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date format. Use Y-m-d format.'
                ], 400);
            }

            $events = Event::whereDate('start_time', $date)
                ->where('user_id', $this->userId())
                ->orderBy('start_time')
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'start_time' => $event->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $event->end_time->format('Y-m-d H:i:s'),
                        'location' => $event->location,
                        'type' => $event->type,
                        'color' => $event->color,
                        'all_day' => $event->all_day,
                        'status' => $event->status,
                        'date' => $event->start_time->format('Y-m-d')
                    ];
                });

            return response()->json([
                'success' => true,
                'events' => $events
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching events for date', ['date' => $request->get('date'), 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events. Please try again.'
            ], 500);
        }
    }

    public function getEventsForRange(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

            // Validate date format
            if (!Carbon::hasFormat($startDate, 'Y-m-d') || !Carbon::hasFormat($endDate, 'Y-m-d')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date format. Use Y-m-d format.'
                ], 400);
            }

            // Ensure start date is not after end date
            if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date cannot be after end date.'
                ], 400);
            }

            $events = Event::whereBetween('start_time', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ])
                ->where('user_id', $this->userId())
                ->orderBy('start_time')
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'start_time' => $event->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $event->end_time->format('Y-m-d H:i:s'),
                        'location' => $event->location,
                        'type' => $event->type,
                        'color' => $event->color,
                        'all_day' => $event->all_day,
                        'status' => $event->status,
                        'date' => $event->start_time->format('Y-m-d')
                    ];
                });

            return response()->json([
                'success' => true,
                'events' => $events,
                'range' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching events for range', [
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events. Please try again.'
            ], 500);
        }
    }

    public function getUpcomingEvents(Request $request)
    {
        try {
            $limit = $request->get('limit', 5);
            $limit = min(max(1, $limit), 50); // Limit between 1 and 50

            $events = Event::where('user_id', $this->userId())
                ->where('start_time', '>=', now())
                ->orderBy('start_time')
                ->limit($limit)
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'start_time' => $event->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $event->end_time->format('Y-m-d H:i:s'),
                        'location' => $event->location,
                        'type' => $event->type,
                        'color' => $event->color,
                        'all_day' => $event->all_day,
                        'status' => $event->status,
                        'date' => $event->start_time->format('Y-m-d')
                    ];
                });

            return response()->json([
                'success' => true,
                'events' => $events
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching upcoming events', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch upcoming events. Please try again.'
            ], 500);
        }
    }

    public function markAsCompleted(Event $event)
    {
        if ($event->user_id !== $this->userId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this event'
            ], 403);
        }

        try {
            $event->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Event marked as completed!',
                'event' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start_time' => $event->start_time->format('Y-m-d H:i:s'),
                    'end_time' => $event->end_time->format('Y-m-d H:i:s'),
                    'location' => $event->location,
                    'type' => $event->type,
                    'color' => $event->color,
                    'all_day' => $event->all_day,
                    'status' => $event->status,
                    'date' => $event->start_time->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking event as completed', ['event_id' => $event->id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event status. Please try again.'
            ], 500);
        }
    }
}