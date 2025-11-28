@extends('layouts.dashboard')

@section('sidebar')
    <x-sidebar 
        :menuItems="config('menu.user')" 
        :bottomMenuItems="config('menu.bottom')"
        role="user"
    />
@endsection
