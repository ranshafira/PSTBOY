<nav class="bg-white shadow-sm sticky top-0 z-10" x-data="{ open: false }">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <!-- Logo -->
    <div class="flex items-center space-x-3">
      <div class="bg-orange-500 text-white font-bold rounded-md w-8 h-8 flex items-center justify-center">
        P
      </div>
      <span class="font-semibold text-lg">Pelayanan PST</span>
    </div>

    <!-- Hamburger (Mobile) -->
    <button @click="open = !open" class="md:hidden focus:outline-none">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Menu Desktop -->
    <ul class="hidden md:flex space-x-6 text-sm font-medium text-gray-700">
      <li>
        <a href="{{ route('petugas.dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('presensi.index') }}"
           class="{{ request()->routeIs('presensi.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Presensi
        </a>
      </li>
      <li>
        <a href="{{ route('pelayanan.index') }}"
           class="{{ request()->routeIs('pelayanan.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Pelayanan
        </a>
      </li>
      <li>
        <a href="{{ route('riwayat.index') }}"
           class="{{ request()->routeIs('riwayat.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Riwayat
        </a>
      </li>
      <li>
        <a href="{{ route('profile.index') }}"
           class="{{ request()->routeIs('profile.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Profil
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="hover:text-orange-500 transition">
            Logout
          </button>
        </form>
      </li>
    </ul>
  </div>

  <!-- Menu Mobile -->
  <div class="md:hidden" x-show="open" x-transition>
    <ul class="px-4 pb-4 space-y-3 text-sm font-medium text-gray-700">
      <li>
        <a href="{{ route('petugas.dashboard') }}"
           class="block {{ request()->routeIs('dashboard') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
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
           class="{{ request()->routeIs('pelayanan.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Pelayanan
        </a>
      </li>
      <li>
        <a href="{{ route('riwayat.index') }}"
           class="block {{ request()->routeIs('riwayat.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Riwayat
        </a>
      </li>
      <li>
        <a href="{{ route('profile.index') }}"
           class="block {{ request()->routeIs('profile.*') ? 'text-orange-500 font-semibold' : 'hover:text-orange-500 transition' }}">
          Profil
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block w-full text-left hover:text-orange-500 transition">
            Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</nav>
