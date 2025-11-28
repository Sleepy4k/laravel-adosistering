@extends('layouts.dashboard')

@section('sidebar')
    <x-sidebar 
        :menuItems="config('menu.admin')" 
        :bottomMenuItems="config('menu.bottom')"
        role="admin"
    />
@endsection
