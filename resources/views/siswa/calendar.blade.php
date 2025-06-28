<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="space-y-6" x-data="{
        showEventModal: false,
        editingEvent: null,
        selectedDate: null,
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        eventForm: {
            title: '',
            description: '',
            start_time: '',
            end_time: '',
            location: '',
            type: 'other',
            color: '#3B82F6',
            all_day: false
        },
        events: [],
        loading: false,
        error: null,
    
        get monthName() {
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            return months[this.currentMonth];
        },
    
        async fetchEvents() {
            this.loading = true;
            this.error = null;
            try {
                const response = await fetch(`/calendar/events/range?start_date=${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-01&end_date=${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${new Date(this.currentYear, this.currentMonth + 1, 0).getDate()}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.events = data.events.map(event => ({
                        ...event,
                        date: event.start_time.split('T')[0] || event.start_time.split(' ')[0]
                    }));
                } else {
                    throw new Error(data.message || 'Failed to fetch events');
                }
            } catch (error) {
                console.error('Error fetching events:', error);
                this.error = 'Failed to load events: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
    
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.fetchEvents();
        },
    
        previousMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.fetchEvents();
        },
    
        goToToday() {
            const today = new Date();
            this.currentMonth = today.getMonth();
            this.currentYear = today.getFullYear();
            this.fetchEvents();
        },
    
        openCreateModal(date = null) {
            console.log('Opening create modal for date:', date);
            this.editingEvent = null;
            this.selectedDate = date;
            this.resetForm();
            if (date) {
                this.eventForm.start_time = date + 'T09:00';
                this.eventForm.end_time = date + 'T10:00';
            }
            this.showEventModal = true;
        },
    
        openEditModal(event) {
            console.log('Opening edit modal for event:', event);
            this.editingEvent = event;
            this.eventForm = {
                ...event,
                start_time: event.start_time.replace(' ', 'T').substring(0, 16),
                end_time: event.end_time.replace(' ', 'T').substring(0, 16)
            };
            this.showEventModal = true;
        },
    
        resetForm() {
            this.eventForm = {
                title: '',
                description: '',
                start_time: '',
                end_time: '',
                location: '',
                type: 'other',
                color: '#3B82F6',
                all_day: false
            };
        },
    
        closeModal() {
            this.showEventModal = false;
            this.resetForm();
            this.error = null;
        },
    
        async saveEvent() {
            console.log('Saving event:', this.eventForm);
            this.loading = true;
            this.error = null;
    
            try {
                const url = this.editingEvent ? `/calendar/events/${this.editingEvent.id}` : '/calendar/events';
                const method = this.editingEvent ? 'PUT' : 'POST';
    
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.eventForm)
                });
    
                const data = await response.json();
    
                if (data.success) {
                    this.closeModal();
                    await this.fetchEvents();
                    this.showNotification(data.message, 'success');
                } else {
                    throw new Error(data.message || 'Failed to save event');
                }
            } catch (error) {
                console.error('Error saving event:', error);
                this.error = 'Failed to save event: ' + error.message;
            } finally {
                this.loading = false;
            }
        },
    
        async deleteEvent(eventId) {
            if (!confirm('Are you sure you want to delete this event?')) return;
    
            this.loading = true;
            try {
                const response = await fetch(`/calendar/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });
    
                const data = await response.json();
    
                if (data.success) {
                    await this.fetchEvents();
                    this.showNotification(data.message, 'success');
                } else {
                    throw new Error(data.message || 'Failed to delete event');
                }
            } catch (error) {
                console.error('Error deleting event:', error);
                this.showNotification('Failed to delete event: ' + error.message, 'error');
            } finally {
                this.loading = false;
            }
        },
    
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 transition-all duration-300 ${
                                                        type === 'success' ? 'bg-green-500 text-white' :
                                                        type === 'error' ? 'bg-red-500 text-white' :
                                                        'bg-blue-500 text-white'
                                                    }`;
            notification.textContent = message;
    
            document.body.appendChild(notification);
    
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },
    
        getEventsForDate(date) {
            return this.events.filter(event => event.date === date);
        },
    
        get upcomingEvents() {
            const today = new Date().toISOString().split('T')[0];
            return this.events
                .filter(event => event.date >= today)
                .sort((a, b) => new Date(a.start_time) - new Date(b.start_time))
                .slice(0, 5);
        },
    
        generateCalendarDays() {
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
    
            // Fix: Get today's date in local timezone
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    
            const days = [];
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                // Fix: Format date consistently
                const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                days.push({
                    date: date,
                    dateStr: dateStr,
                    day: date.getDate(),
                    isCurrentMonth: date.getMonth() === this.currentMonth,
                    isToday: dateStr === todayStr,
                    events: this.getEventsForDate(dateStr)
                });
            }
            return days;
        },
    
        formatEventTime(dateTimeString) {
            const date = new Date(dateTimeString);
            return date.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        },
    
        formatEventDate(dateTimeString) {
            const date = new Date(dateTimeString);
            return date.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric'
            });
        }
    }" x-init="console.log('Alpine.js initialized');
    fetchEvents();">

        <!-- Loading Overlay -->
        <div x-show="loading" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="text-gray-900 dark:text-white">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Error Display -->
        <div x-show="error" x-transition
            class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4">
            <span x-text="error"></span>
            <button @click="error = null"
                class="float-right text-red-700 dark:text-red-300 hover:text-red-900 dark:hover:text-red-100">&times;</button>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Calendar</h2>
                    <div class="flex space-x-2">
                        <button @click="goToToday()"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Today
                        </button>
                        <button @click="openCreateModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add Event
                        </button>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Manage your schedule and upcoming events.
                </p>

                <!-- Calendar header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                            x-text="`${monthName} ${currentYear}`">
                        </h3>
                        <div class="flex space-x-2">
                            <button @click="previousMonth()"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button @click="nextMonth()"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calendar grid -->
                <div class="grid grid-cols-7 gap-1 text-center text-sm mb-8" :key="refreshKey">
                    <!-- Calendar header -->
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Sun</div>
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Mon</div>
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Tue</div>
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Wed</div>
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Thu</div>
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Fri</div>
                    <div class="font-semibold text-gray-900 dark:text-white py-2">Sat</div>

                    <!-- Calendar days -->
                    <template x-for="day in generateCalendarDays()" :key="day.dateStr">
                        <div class="relative min-h-[80px] p-1 border border-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                            :class="{
                                'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-700': day.isToday,
                                'text-gray-400 bg-gray-50 dark:bg-gray-700 dark:text-gray-500': !day.isCurrentMonth,
                                'text-gray-700 dark:text-gray-300': day.isCurrentMonth
                            }"
                            @click="openCreateModal(day.dateStr)">

                            <div class="text-right mb-1">
                                <span class="text-xs"
                                    :class="{ 'bg-blue-500 text-white px-1.5 py-0.5 rounded-full': day.isToday }"
                                    x-text="day.day"></span>
                            </div>

                            <!-- Events for this day -->
                            <div class="space-y-1">
                                <template x-for="event in day.events.slice(0, 3)" :key="event.id">
                                    <div class="text-xs p-1.5 rounded-md truncate cursor-pointer hover:opacity-80 transition-opacity"
                                        :style="`background-color: ${event.color}20; color: ${event.color}; border-left: 3px solid ${event.color}`"
                                        @click.stop="openEventDetail(event)"
                                        :title="`${event.title} - ${formatEventTime(event.start_time)}`">
                                        <div class="flex items-center space-x-1">
                                            <div class="w-2 h-2 rounded-full flex-shrink-0"
                                                :style="`background-color: ${event.color}`"></div>
                                            <span x-text="event.title" class="truncate"></span>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="day.events.length > 3">
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400 p-1 text-center bg-gray-100 dark:bg-gray-700 rounded">
                                        +<span x-text="day.events.length - 3"></span> lainnya
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Upcoming events -->
                <div>
                    <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Upcoming Events</h3>
                    <div class="space-y-3">
                        <template x-for="event in upcomingEvents" :key="event.id">
                            <div
                                class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="w-3 h-3 rounded-full flex-shrink-0"
                                    :style="`background-color: ${event.color}`"></div>
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate" x-text="event.title">
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span x-text="formatEventDate(event.start_time)"></span>
                                        at
                                        <span x-text="formatEventTime(event.start_time)"></span>
                                        <template x-if="event.location">
                                            <span>• <span x-text="event.location"></span></span>
                                        </template>
                                    </p>
                                </div>
                                <div class="flex space-x-2 flex-shrink-0">
                                    <button @click="openEditModal(event)"
                                        class="text-gray-400 dark:text-gray-500 hover:text-blue-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button @click="deleteEvent(event.id)"
                                        class="text-gray-400 dark:text-gray-500 hover:text-red-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template x-if="upcomingEvents.length === 0">
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">No upcoming events</p>
                                <button @click="openCreateModal()"
                                    class="mt-2 text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                    Create your first event
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Modal -->
        <div x-show="showEventModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="closeModal()" style="display: none;">

            <div class="fixed inset-0 bg-white/30 dark:bg-black/30 backdrop-blur-sm backdrop-saturate-150 transition-opacity"
                @click="closeModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showEventModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full" @click.stop>

                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white"
                            x-text="editingEvent ? 'Edit Event' : 'Create Event'"></h3>
                    </div>

                    <form @submit.prevent="saveEvent()" class="p-6 space-y-4">
                        <!-- Error display in modal -->
                        <div x-show="error" x-transition
                            class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-3 py-2 rounded text-sm">
                            <span x-text="error"></span>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                            <input type="text" x-model="eventForm.title" required
                                class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea x-model="eventForm.description" rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start
                                    Time</label>
                                <input type="datetime-local" x-model="eventForm.start_time" required
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End
                                    Time</label>
                                <input type="datetime-local" x-model="eventForm.end_time" required
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                            <input type="text" x-model="eventForm.location"
                                class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                                <select x-model="eventForm.type"
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="meeting">Meeting</option>
                                    <option value="task">Task</option>
                                    <option value="reminder">Reminder</option>
                                    <option value="appointment">Appointment</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                                <input type="color" x-model="eventForm.color"
                                    class="w-full h-10 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" x-model="eventForm.all_day" id="all_day"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="all_day" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">All day
                                event</label>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" @click="closeModal()"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" :disabled="loading"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50">
                                <span x-show="!loading"
                                    x-text="editingEvent ? 'Update Event' : 'Create Event'"></span>
                                <span x-show="loading">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
