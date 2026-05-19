<div class="border-b bg-white sticky top-0 z-50 shadow-sm">

<div class="container mx-auto px-6 py-4 flex justify-between items-center">


<div class="flex items-center gap-10">

<a href="{{route('home')}}">

<img
src="{{asset('assets/img/events/logo-tixevent.png')}}"
class="h-8">

</a>


<div class="hidden md:flex gap-3">


<a
href="{{route('home')}}"
class="px-4 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600">

Beranda

</a>


<a
href="{{route('home')}}#event-list"
class="px-4 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600">

Event

</a>


@auth

<a
href="{{route('history')}}"
class="px-4 py-2 rounded-xl hover:bg-indigo-50 hover:text-indigo-600">

Riwayat

</a>

@endauth

</div>

</div>



<div class="flex items-center gap-4">

<form
action="{{route('home')}}"
method="GET"
class="flex">

<input
type="text"
name="search"

value="{{request('search')}}"

placeholder="Cari event, lokasi..."

class="border
rounded-l-xl
px-4
py-2
w-64
outline-none">


<button
class="bg-indigo-600
text-white
px-4
rounded-r-xl">

🔍

</button>

</form>
@auth

<a
href="{{route('cart')}}"
class="relative bg-gray-100 p-3 rounded-xl">

🛒

@if(session('cart'))

<span
class="absolute
-top-2
-right-2
bg-red-500
text-white
text-xs
w-5
h-5
rounded-full
flex
items-center
justify-center">

{{ count(session('cart')) }}

</span>

@endif

</a>

@endauth

@guest

<a href="{{route('login')}}">

Login

</a>


<a
href="{{route('register')}}"
class="bg-indigo-600 text-white px-5 py-2 rounded-xl">

Register

</a>

@endguest



@auth

<a
href="{{route('profile')}}"
class="flex items-center gap-3
bg-gray-100 px-4 py-2 rounded-full">


<div
class="w-9 h-9 rounded-full
bg-indigo-600 text-white
flex items-center justify-center">

{{ strtoupper(substr(auth()->user()->name,0,1)) }}

</div>


<div>

<div class="text-sm font-bold">

{{auth()->user()->name}}

</div>

<div class="text-xs text-gray-500">

Lihat Profil

</div>

</div>

</a>


<form
action="{{route('logout')}}"
method="POST">

@csrf

<button
class="bg-red-500 text-white px-4 py-2 rounded-xl">

Logout

</button>

</form>

@endauth


</div>

</div>

</div>