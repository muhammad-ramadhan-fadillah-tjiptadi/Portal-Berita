@extends('templates.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b></div>
    @endif
    @if (Session::get('logout'))
        <div class="alert alert-success">{{ Session::get('logout') }}</div>
    @endif
@endsection
