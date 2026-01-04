@extends('layouts.dashboard')

@section('sidebar')
    <x-layout.sidebar 
        :menuItems="config('menu.user')" 
        :bottomMenuItems="config('menu.bottom')"
        role="user"
    />
@endsection
