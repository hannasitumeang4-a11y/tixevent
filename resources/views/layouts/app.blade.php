<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventix - Digital Event Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-digital { font-family: 'Space Grotesk', sans-serif; }
        /* Efek latar belakang digital abstrak */
        .digital-bg {
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.05) 0px, transparent 50%);
        }
    </style>
</head>
<body class="bg-[#0f111a] digital-bg text-slate-200 selection:bg-indigo-500 selection:text-white antialiased min-h-screen flex flex-col">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="fixed top-6 right-6 z-[100] bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3.5 rounded-2xl shadow-2xl shadow-emerald-950/20 font-semibold flex items-center gap-2 border border-emerald-400/20 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- TOP NAVBAR SHELL --}}
    <header class="sticky top-0 z-40 bg-[#0f111a]/80 backdrop-blur-xl border-b border-slate-800/60 transition-all duration-300">
        @include('components.navbar')
    </header>

    {{-- MAIN WRAPPER FOR SIDEBAR SYSTEM --}}
    {{-- UPGRADE: Padding pl-16 hanya aktif jika yang login BUKAN organizer/admin agar tampilan dashboard bersih & luas --}}
    <div class="flex flex-1 relative {{ Auth::check() && (Auth::user()->isOrganizer() || Auth::user()->isAdmin()) ? '' : 'pl-16 md:pl-20' }} transition-all duration-300">
        
        <main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full overflow-hidden">
            @yield('content')
        </main>

    </div>

</body>
</html>