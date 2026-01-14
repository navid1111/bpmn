<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
    <div class="flex items-center text-gray-500">
        <button class="mr-4 md:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <span class="text-sm">Home / Dashboard</span>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar" class="w-8 h-8 rounded-full">
        
        <!-- Dropdown Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200"
                 style="display: none;">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profile Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
