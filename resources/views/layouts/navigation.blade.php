<nav class="bg-white shadow-sm sticky top-0 z-10" x-data="{ open: false }">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">

    <!-- Logo -->
    <div class="flex items-center space-x-3">
      <img src="{{ asset('build/assets/images/logo-pst.svg') }}" 
          alt="Logo PST" 
          class="h-10 w-auto">

      <div class="flex flex-col leading-tight">
         <span class="font-bold text-xl bg-gradient-to-r from-sky-600 via-green-600 to-orange-500 bg-clip-text text-transparent">
            Pelayanan Statistik Terpadu
          </span>
        <span class="text-xs text-gray-500 italic">Badan Pusat Statistik Kabupaten Boyolali</span>
      </div>
    </div>

    <!-- Hamburger (Mobile) -->
    <button @click="open = !open" class="md:hidden focus:outline-none">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Menu Desktop -->
    <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-700">
      <a href="{{ route('petugas.dashboard') }}"
         class="{{ request()->routeIs('petugas.dashboard') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
        Dashboard
      </a>
      <a href="{{ route('presensi.index') }}"
         class="{{ request()->routeIs('presensi.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
        Presensi
      </a>
      <a href="{{ route('pelayanan.index') }}"
         class="{{ request()->routeIs('pelayanan.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
        Pelayanan
      </a>
      <a href="{{ route('riwayat.index') }}"
         class="{{ request()->routeIs('riwayat.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
        Riwayat
      </a>

      <!-- User Dropdown -->
      <div class="relative" x-data="{ dropdown: false }">
        <button @click="dropdown = !dropdown" class="flex items-center space-x-2 focus:outline-none">
          <!-- Avatar -->
          <div class="w-6 h-6 rounded-full bg-orange-400 text-white flex items-center justify-center font-semibold">
            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
          </div>
          <span class="text-sm font-medium text-gray-700">
            {{ Auth::user()->nama_lengkap }}
          </span>
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div x-show="dropdown" @click.away="dropdown = false"
             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-md border border-gray-100 z-50"
             x-transition>
          <div class="px-4 py-2 border-b border-gray-100 text-sm text-gray-600">
            <span class="font-semibold">{{ Auth::user()->nama_lengkap }}</span><br>
            <span class="text-xs text-gray-400">
              {{ Auth::user()->role_id == '1' ? 'Administrator' : 'Petugas PST' }}
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

  <!-- Menu Mobile -->
  <div class="md:hidden" x-show="open" x-transition>
    <ul class="px-4 pb-4 space-y-3 text-sm font-medium text-gray-700">
      <li>
        <a href="{{ route('petugas.dashboard') }}"
           class="block {{ request()->routeIs('petugas.dashboard') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('presensi.index') }}"
           class="block {{ request()->routeIs('presensi.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Presensi
        </a>
      </li>
      <li>
        <a href="{{ route('pelayanan.index') }}"
           class="block {{ request()->routeIs('pelayanan.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Pelayanan
        </a>
      </li>
      <li>
        <a href="{{ route('riwayat.index') }}"
           class="block {{ request()->routeIs('riwayat.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Riwayat
        </a>
      </li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block w-full text-left text-red-600 hover:text-orange-500 transition">
            Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</nav>
