@extends('voluntter.layouts.default.baseof')
@section('main')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('voluntter.layouts.partials.navbar_voluntter')
    @include('voluntter.components.edit_kategori')
@endsection
