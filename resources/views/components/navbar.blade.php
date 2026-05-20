<div class="border-b border-slate-200 bg-white sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        
        <div class="flex items-center gap-10">
            <a href="{{route('home')}}" class="transition hover:opacity-90">
                <img src="{{asset('assets/img/events/logo-tixevent.png')}}" class="h-8">
            </a>

            <div class="hidden md:flex gap-1">
                @auth
                    @if(auth()->user()->role === 'organizer')
                        <a href="{{route('organizer.dashboard')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('organizer.dashboard') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Dashboard Promotor
                        </a>
                        <a href="{{route('organizer.events.create')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('organizer.events.create') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            + Buat Event
                        </a>

                    @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                        <a href="{{route('admin.dashboard')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Dashboard Admin
                        </a>
                        <a href="{{route('admin.usermanage')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('admin.usermanage') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Kelola User
                        </a>
                        <a href="{{route('admin.eventmanage')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('admin.eventmanage') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Kelola Event
                        </a>

                    @else
                        <a href="{{route('home')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('home') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Beranda
                        </a>
                        <a href="{{route('home')}}#event-list" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200">
                            Event
                        </a>
                        <a href="{{route('history')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('history') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Riwayat
                        </a>
                        <a href="{{route('about')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('about') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            About
                        </a>
                        <a href="{{route('review')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('review') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                            Review
                        </a>
                    @endif
                @else
                    <a href="{{route('home')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('home') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                        Beranda
                    </a>
                    <a href="{{route('home')}}#event-list" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200">
                        Event
                    </a>
                    <a href="{{route('about')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('about') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                        About
                    </a>
                    <a href="{{route('review')}}" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-indigo-600 font-semibold transition duration-200 {{ request()->routeIs('review') ? 'bg-indigo-50/60 text-indigo-600' : '' }}">
                        Review
                    </a>
                @endauth
            </div>
        </div>

        <div class="flex items-center gap-4">
            @if(!auth()->check() || (auth()->user()->role !== 'organizer' && auth()->user()->role !== 'admin' && auth()->user()->role !== 'superadmin'))
                <form action="{{route('home')}}" method="GET" class="flex relative group">
                    <input type="text" name="search" value="{{request('search')}}" placeholder="Cari event, lokasi..."
                           class="bg-slate-50 border border-slate-300 text-slate-800 placeholder-slate-400 rounded-l-xl px-4 py-2 w-60 outline-none focus:border-indigo-500 focus:bg-white transition duration-200 text-sm font-medium">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-r-xl transition duration-200">
                        🔍
                    </button>
                </form>

                @auth
                <a href="{{route('cart')}}" class="relative bg-slate-50 hover:bg-slate-100 border border-slate-300 p-2.5 rounded-xl transition duration-200 text-slate-700">
                    🛒
                    @if(session('cart'))
                    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-md">
                        {{ count(session('cart')) }}
                    </span>
                    @endif
                </a>
                @endauth
            @endif

            @guest
                <a href="{{route('login')}}" class="text-slate-600 hover:text-indigo-600 font-bold text-sm px-3 py-2 transition">
                    Login
                </a>
                <a href="{{route('register')}}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md transition duration-200">
                    Register
                </a>
            @endguest

            @auth
                <a href="{{route('profile')}}" class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 border border-slate-300 px-4 py-1.5 rounded-full transition duration-200 group">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                    <div class="text-left hidden sm:block">
                        <div class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition duration-200">
                            {{auth()->user()->name}}
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium">
                            @if(auth()->user()->role === 'organizer')
                                Promotor Panel
                            @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                Administrator
                            @else
                                Lihat Profil
                            @endif
                        </div>
                    </div>
                </a>

                <form action="{{route('logout')}}" method="POST" class="inline m-0">
                    @csrf
                    <button class="bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 px-4 py-2 rounded-xl text-xs font-bold transition duration-200">
                        Logout
                    </button>
                </form>
            @endauth

        </div>
    </div>
</div>