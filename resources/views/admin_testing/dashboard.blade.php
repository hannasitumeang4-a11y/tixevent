@extends('layouts.app')

@section('content')

<div style="padding:20px;">
    <h2>Dashboard Admin</h2>

    <div style="
        margin-top:20px;
        padding:20px;
        width:250px;
        border:1px solid #ddd;
        border-radius:10px;
        box-shadow:0 2px 5px rgba(0,0,0,0.1);
    ">
        <h4>Total Event</h4>
        <h2>{{ $totalEvents }}</h2>
    </div>
</div>

@endsection