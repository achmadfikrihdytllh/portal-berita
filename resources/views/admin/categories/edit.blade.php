@extends('admin.layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @method('PUT')
        @include('admin.categories._form')
    </form>
@endsection
