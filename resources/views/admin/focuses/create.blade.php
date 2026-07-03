@extends('admin.layouts.app')

@section('title', 'Tambah Fokus')

@section('content')
    <form action="{{ route('admin.focuses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.focuses._form')
    </form>
@endsection