@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @include('admin.categories._form')
    </form>
@endsection
