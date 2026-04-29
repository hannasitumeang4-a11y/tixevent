<div class="border-b px-6 py-4 flex items-center justify-between">
    
    <div class="font-bold text-lg">
        Eventix
    </div>

    <div class="flex items-center gap-4">
        <input 
            type="text"
            placeholder="Cari event..."
            class="border rounded px-4 py-2 w-80"
        >

        {{-- TAMBAHAN AUTH CHECK --}}
        @guest
            <a href="{{ route('login') }}" class="text-sm">Login</a>

            <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded text-sm">
                Register
            </a>
        @endguest

        @auth
            <span class="text-sm">
                Halo, {{ auth()->user()->name }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="bg-red-500 text-white px-4 py-2 rounded text-sm">
                    Logout
                </button>
            </form>
        @endauth
        {{-- END TAMBAHAN --}}
    </div>

</div>