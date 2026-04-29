<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-sm text-gray-800">

<div class="max-w-7xl mx-auto mt-6 bg-white border rounded shadow-sm">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- NAVBAR --}}
    @include('components.navbar')

    <main class="p-6">
        @yield('content')
    </main>

</div>

</body>
</html>