<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Eventix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-sm text-gray-800">

<div class="max-w-7xl mx-auto mt-6 bg-white border rounded shadow-sm flex min-h-screen">

    @include('components.admin-sidebar')

    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<script>
    function logoutHandler(e) {
        e.preventDefault();
        document.getElementById('logout-form').submit();
    }
</script>

</body>
</html>