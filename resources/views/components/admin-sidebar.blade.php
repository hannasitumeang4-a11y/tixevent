<aside class="w-56 bg-gray-900 text-white p-4">

    <h2 class="text-lg font-bold mb-6">Eventix Admin</h2>

    <nav class="space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-700">Dashboard</a>
        <a href="{{ route('admin.eventmanage') }}" class="block px-3 py-2 rounded hover:bg-gray-700">Event</a>
        <a href="{{ route('admin.usermanage') }}" class="block px-3 py-2 rounded hover:bg-gray-700">User</a>
        <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Review</a>
        <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">Laporan</a>

        <!-- LOGOUT -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-gray-700">
                Logout
            </button>
        </form>
    </nav>

</aside>