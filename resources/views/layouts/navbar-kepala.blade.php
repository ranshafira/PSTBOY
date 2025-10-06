<nav class="bg-white shadow-sm sticky top-0 z-10" x-data="{ open: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <div class="flex items-center space-x-3">
                <img src="{{ asset('build/assets/images/logo-pst.svg') }}"
                    alt="Logo PST"
                    class="h-8 w-auto">
                <div class="flex flex-col leading-tight">
                    <span class="font-bold text-xl bg-gradient-to-r from-sky-600 via-green-600 to-orange-500 bg-clip-text text-transparent">
                        Pelayanan Statistik Terpadu
                    </span>
                    <span class="text-xs text-gray-500 italic">Badan Pusat Statistik Kabupaten Boyolali</span>
                </div>
            </div>

            {{-- Menu Desktop --}}
            <div class="hidden sm:flex items-center space-x-6 text-sm font-medium">
                <a href="{{ route('dashboard.kepala') }}"
                    class="{{ request()->routeIs('dashboard.kepala') ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500 transition' }}">
                    Dashboard
                </a>

                <a href="{{ route('riwayat.index') }}"
                    class="{{ request()->routeIs('riwayat.*') ? 'text-orange-500 font-semibold' : 'text-gray-700 hover:text-orange-500 transition' }}">
                    Riwayat
                </a>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ dropdown: false }">
                    <button @click="dropdown = !dropdown" class="flex items-center space-x-2 focus:outline-none">
                        {{-- Avatar bulat (inisial nama) --}}
                        <div class="w-6 h-6 rounded-full bg-red-400 text-white flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            {{ Auth::user()->nama_lengkap }}
                        </span>
                        {{-- Icon dropdown --}}
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown menu --}}
                    <div x-show="dropdown" @click.away="dropdown = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-md border border-gray-100 z-50"
                        x-transition>
                        <div class="px-4 py-2 border-b border-gray-100 text-sm text-gray-600">
                            <span class="font-semibold">{{ Auth::user()->nama_lengkap }}</span><br>
                            <span class="text-xs text-gray-400">
                                {{ Auth::user()->role->nama_role }}
                            </span>
                        </div>
                        <a href="{{ route('profile.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>