<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                        {{ __('Products') }}
                    </x-nav-link>
                </div>

                {{-- <ul>
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul> --}}

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        $language = request()->segment(1) == 'ar' ? 'en' : 'ar';
                    @endphp
                    {{-- @dump($route) --}}
                    <x-nav-link href="{{ LaravelLocalization::getLocalizedURL($language) }}">
                        {{ strtoupper($language) }}
                    </x-nav-link>
                </div>
{{-- 
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        $route = Route::CurrentRouteName();
                        $language = request()->segment(1) == 'ar' ? 'en' : 'ar';
                    @endphp
                    <x-nav-link href="{{ route($route, ['locale' => $language]) }}">
                        {{ strtoupper($language) }}
                    </x-nav-link>
                </div> --}}
            </div>

        </div>
    </div>
</nav>
