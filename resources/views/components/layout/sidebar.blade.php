@props([
    'menuItems' => [],
    'bottomMenuItems' => [],
    'role' => 'user',
    'activeRoute' => null
])

<aside 
    x-data="{ open: sidebarOpen }"
    x-init="$watch('sidebarOpen', value => open = value); $watch('open', value => sidebarOpen = value)"
    :class="open ? 'w-64' : 'w-20'" 
    class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 fixed h-full z-10 overflow-hidden"
>
    <div class="py-8 pb-6">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('assets/icons/logo.svg') }}" alt="Logo" class="h-16 w-auto shrink-0">
        </div>
        
        <div class="w-[80%] mx-auto h-px bg-gray-200 mb-4"></div>
        
        <div :class="open ? 'px-4 flex justify-end' : 'flex justify-center'">
            <button 
                @click="open = !open"
                class="p-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors"
                :class="{ 'rotate-180': !open }"
            >
                <img src="{{ asset('assets/icons/chevron-left.svg') }}" alt="Toggle" class="w-5 h-5 shrink-0 transition-transform duration-300">
            </button>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        @foreach($menuItems as $item)
            @php
                // Check if current route matches the active pattern
                $isActive = false;
                
                // Support for array of routes
                if (is_array($item['active'])) {
                    foreach ($item['active'] as $activeRoute) {
                        if (str_contains($activeRoute, '*')) {
                            $pattern = str_replace('*', '', $activeRoute);
                            if (request()->routeIs($pattern . '*')) {
                                $isActive = true;
                                break;
                            }
                        } else {
                            if (request()->routeIs($activeRoute)) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                } else {
                    // Single route (string)
                    if (str_contains($item['active'], '*')) {
                        $pattern = str_replace('*', '', $item['active']);
                        $isActive = request()->routeIs($pattern . '*');
                    } else {
                        $isActive = request()->routeIs($item['active']);
                    }
                }
            @endphp
            <a 
                href="{{ is_string($item['route']) && str_starts_with($item['route'], '#') ? $item['route'] : route($item['route']) }}" 
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-colors
                    {{ $isActive ? 'bg-[#6BC145] text-white' : 'text-[#4F4F4F] hover:bg-gray-50' }}"
            >
                <img 
                    src="{{ asset('assets/icons/' . $item['icon']) }}" 
                    alt="{{ $item['label'] }}" 
                    class="w-5 h-5 shrink-0 {{ $isActive ? 'brightness-0 invert' : '' }}"
                >
                <span x-show="open" x-transition class="whitespace-nowrap text-[15px] font-normal">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="px-4 pb-6 space-y-2">
        @foreach($bottomMenuItems as $item)
            @php
                // Check if current route matches the active pattern
                $isActive = false;
                
                // Support for array of routes
                if (is_array($item['active'])) {
                    foreach ($item['active'] as $activeRoute) {
                        if (str_contains($activeRoute, '*')) {
                            $pattern = str_replace('*', '', $activeRoute);
                            if (request()->routeIs($pattern . '*')) {
                                $isActive = true;
                                break;
                            }
                        } else {
                            if (request()->routeIs($activeRoute)) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                } else {
                    // Single route (string)
                    if (str_contains($item['active'], '*')) {
                        $pattern = str_replace('*', '', $item['active']);
                        $isActive = request()->routeIs($pattern . '*');
                    } else {
                        $isActive = request()->routeIs($item['active']);
                    }
                }
            @endphp
            
            @if(isset($item['logout']) && $item['logout'])
                {{-- Logout Button with Form (DELETE method) --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl transition-colors
                            text-[#4F4F4F] hover:bg-red-50 hover:text-red-600"
                    >
                        <img 
                            src="{{ asset('assets/icons/' . $item['icon']) }}" 
                            alt="{{ $item['label'] }}" 
                            class="w-5 h-5 shrink-0"
                        >
                        <span x-show="open" x-transition class="whitespace-nowrap text-[15px] font-normal">{{ $item['label'] }}</span>
                    </button>
                </form>
            @else
                <a 
                    href="{{ is_string($item['route']) && str_starts_with($item['route'], '#') ? $item['route'] : route($item['route']) }}" 
                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition-colors
                        {{ isset($item['danger']) && $item['danger'] ? 'text-[#4F4F4F] hover:bg-red-50 hover:text-red-600' : 'text-[#4F4F4F] hover:bg-gray-50' }}
                        {{ $isActive && !isset($item['danger']) ? 'bg-[#6BC145] text-white' : '' }}"
                >
                    <img 
                        src="{{ asset('assets/icons/' . $item['icon']) }}" 
                        alt="{{ $item['label'] }}" 
                        class="w-5 h-5 shrink-0 {{ $isActive && !isset($item['danger']) ? 'brightness-0 invert' : '' }}"
                    >
                    <span x-show="open" x-transition class="whitespace-nowrap text-[15px] font-normal">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

</aside>
