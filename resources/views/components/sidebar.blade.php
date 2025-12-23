<aside class="w-16 bg-white border-r border-gray-200 flex flex-col items-center py-4 space-y-6">
    <!-- Logo/Brand -->
    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
        T
    </div>

    <nav class="flex-1 flex flex-col items-center space-y-4">
        @if(auth()->user()->hasAnyRole(['Admin', 'Dispatcher']))
            <!-- Loads -->
            <a href="{{ route('loads.index') }}" 
               class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('loads.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"
               title="Loads">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </a>

            <!-- Map -->
            <a href="{{ route('dispatch.map') }}" 
               class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('dispatch.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"
               title="Dispatch Map">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </a>
        @endif

        @if(auth()->user()->hasRole('Driver'))
            <!-- Driver Dashboard -->
            <a href="{{ route('driver.dashboard') }}" 
               class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors {{ request()->routeIs('driver.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}"
               title="Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
        @endif
    </nav>
</aside>
