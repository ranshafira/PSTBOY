<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            {{-- Logo --}}
            <div class="flex items-center space-x-3">
                <div class="bg-orange-500 text-white font-bold rounded-md w-8 h-8 flex items-center justify-center">
                    P
                </div>
                <span class="font-semibold text-lg text-gray-700">Pelayanan PST</span>
            </div>

            {{-- Menu --}}
            <div class="hidden sm:block sm:ml-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    <a href="{{ route('admin.petugas.index') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Petugas PST</a>
                    <a href="#" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Laporan</a>
                    <a href="{{ route('profile.index') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Profil</a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>
