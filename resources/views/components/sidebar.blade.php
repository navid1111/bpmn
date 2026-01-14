<aside class="w-64 bg-gray-200 text-gray-800 flex-shrink-0 hidden md:block border-r border-gray-300">
    <div class="h-16 flex items-center justify-center border-b border-gray-300 bg-gray-200">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-red-600">রবি</a>
    </div>
    <nav class="mt-4">
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 {{ request()->routeIs('dashboard') ? 'bg-gray-300 border-r-4 border-gray-500' : 'hover:bg-gray-300' }} flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-gray-300 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Partner List
        </a>
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 {{ request()->routeIs('dashboard') ? 'bg-gray-300 border-r-4 border-gray-500' : 'hover:bg-gray-300' }} flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Proposal List
        </a>
        <a href="{{ route('bpmn.index') }}" class="block px-6 py-3 {{ request()->routeIs('bpmn.*') ? 'bg-gray-300 border-r-4 border-gray-500' : 'hover:bg-gray-300' }} flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            BPMN Editor
        </a>
    </nav>
    
    <div class="absolute bottom-0 w-64 p-4 border-t border-gray-300">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
