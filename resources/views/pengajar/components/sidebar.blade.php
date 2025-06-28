<!-- Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col" 
     x-data="{ 
        open: false,
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        toggleSidebar() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar-collapsed', this.collapsed);
        }
     }">
  <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 transition-all duration-300"
       :class="{'px-6 pb-4': !collapsed, 'px-2 pb-4': collapsed, 'w-64': !collapsed, 'w-16': collapsed}">
    <div class="flex h-16 shrink-0 items-center" 
      :class="{'justify-center': collapsed, 'justify-start': !collapsed}">
      <x-heroicon-o-cube class="h-6 w-6 text-white" />
      <span class="ml-3 text-white text-lg font-semibold transition-all duration-300" :class="{'hidden': collapsed}">Learnity</span>
    </div>
    
    <!-- Toggle button -->
    <button @click="toggleSidebar" class="flex items-center justify-center rounded-md text-gray-400 hover:text-white self-end cursor-pointer">
      <x-heroicon-o-chevron-double-left x-show="!collapsed" class="h-5 w-5 transition-transform duration-300" />
      <x-heroicon-o-chevron-double-right x-show="collapsed" class="h-5 w-5 transition-transform duration-300" />
    </button>
    
    <!-- Include the sidebar content component and pass the collapsed state -->
    <x-pengajar.sidebar-content :collapsed="false" />
  </div>
</div>

<!-- Mobile sidebar -->
<div class="lg:hidden" x-data="{ open: false }">
  <!-- Mobile sidebar backdrop -->
  <div x-show="open" 
       x-transition:enter="transition-opacity ease-linear duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition-opacity ease-linear duration-300"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="relative z-50 lg:hidden">
    <div class="fixed inset-0 bg-gray-900/80" @click="open = false"></div>
    
    <div class="fixed inset-0 flex">
      <div x-show="open"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="relative mr-16 flex w-full max-w-xs flex-1">
        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
          <button type="button" class="-m-2.5 p-2.5" @click="open = false">
            <span class="sr-only">Close sidebar</span>
            <x-heroicon-o-x-mark class="h-6 w-6 text-white" />
          </button>
        </div>
        
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4">
          <div class="flex h-16 shrink-0 items-center">
            <x-heroicon-o-cube class="h-8 w-8 text-white" />
            <span class="ml-3 text-white text-lg font-semibold transition-all duration-300" :class="{'hidden': collapsed}">Learnity</span>
          </div>
          <!-- Include the sidebar content component for mobile -->
          <x-pengajar.sidebar-content :collapsed="false" :mobile="true" />
        </div>
      </div>
    </div>
  </div>
  
  <!-- Mobile menu button -->
  <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-gray-900 px-4 py-4 shadow-sm sm:px-6 lg:hidden">
    <button type="button" class="-m-2.5 p-2.5 text-gray-400 lg:hidden" @click="open = true">
      <span class="sr-only">Open sidebar</span>
      <x-heroicon-o-bars-3 class="h-6 w-6" />
    </button>
  </div>
</div>