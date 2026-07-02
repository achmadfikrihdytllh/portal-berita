@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.news._form')
    </form>
@endsection
