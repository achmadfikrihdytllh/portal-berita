@extends('admin.layouts.app')

@section('title', 'Edit Galeri Foto')

@section('content')
    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.galleries._form')
    </form>
@endsection