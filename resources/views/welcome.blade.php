@extends('layouts.mahasiswa')

@section('page-title', 'Dashboard Mahasiswa')

@section('content')
    <h4>Selamat datang, {{ Auth::user()->name }}!</h4>
    <p>Ini adalah halaman dashboard mahasiswa.</p>
@endsection
