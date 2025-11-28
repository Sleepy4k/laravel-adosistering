@extends('layouts.dashboard')

@section('sidebar')
    <x-sidebar 
        :menuItems="config('menu.superadmin')" 
        :bottomMenuItems="config('menu.bottom')"
        role="superadmin"
    />
@endsection
