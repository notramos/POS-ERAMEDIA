{{-- resources/views/partials/navbar.blade.php --}}
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Logo / Brand --}}
            <div class="flex items-center">
                <a href="" class="flex-shrink-0 flex items-center">
                    <span class="ml-2 text-xl font-bold text-gray-800">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center space-x-8">
                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center text-sm rounded-full text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="ml-2">{{ Auth::user()->name }}</span>
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    @php
                        $role = Auth::user()->role->name; // contoh: 'owner' atau 'kasir'
                    @endphp
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profil
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>
                        <a href="{{ route('laporan.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Daftar Transaksi
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile menu button --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Menu --}}
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95" class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200">
                <a href="{{ route('kasir.index') }}"
                    class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Beranda

                    {{-- Mobile Authentication --}}

                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex items-center px-3 py-2">
                            <img class="h-8 w-8 rounded-full"
                                src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}"
                                alt="{{ Auth::user()->name }}">
                            <span class="ml-3 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <a href=""
                            class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                            Profil
                        </a>
                        <a href=""
                            class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('auth.logout') }}" class="mt-1">
                            @csrf
                            <button type="submit"
                                class="text-gray-600 hover:text-gray-900 block w-full text-left px-3 py-2 rounded-md text-base font-medium">
                                Keluar
                            </button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</nav>

{{-- Alpine.js initialization script --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbar', () => ({
            mobileMenuOpen: false
        }))
    })
</script>
