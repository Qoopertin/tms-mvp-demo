<nav class="flex items-center text-sm text-gray-600">
    @php
        $breadcrumbs = [];
        
        if(request()->routeIs('loads.index')) {
            $breadcrumbs = [['Loads', null]];
        } elseif(request()->routeIs('loads.show')) {
            $breadcrumbs = [
                ['Loads', route('loads.index')],
                [request()->route('load')->reference_no, null]
            ];
        } elseif(request()->routeIs('loads.create')) {
            $breadcrumbs = [
                ['Loads', route('loads.index')],
                ['Create', null]
            ];
        } elseif(request()->routeIs('dispatch.map')) {
            $breadcrumbs = [['Dispatch', null], ['Map', null]];
        } elseif(request()->routeIs('driver.dashboard')) {
            $breadcrumbs = [['Driver Dashboard', null]];
        }
    @endphp

    @foreach($breadcrumbs as $index => $crumb)
        @if($index > 0)
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
        @endif

        @if($crumb[1])
            <a href="{{ $crumb[1] }}" class="hover:text-gray-900">{{ $crumb[0] }}</a>
        @else
            <span class="font-medium text-gray-900">{{ $crumb[0] }}</span>
        @endif
    @endforeach
</nav>
