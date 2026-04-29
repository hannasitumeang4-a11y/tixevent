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

        <a href="{{ route('login') }}" class="text-sm">Login</a>

        <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded text-sm">
            Register
        </a>
    </div>

</div>