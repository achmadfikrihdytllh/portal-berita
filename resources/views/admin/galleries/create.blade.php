@extends('admin.layouts.app')

@section('title', 'Tambah Galeri Foto')

@section('content')
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.galleries._form')
    </form>
@endsection