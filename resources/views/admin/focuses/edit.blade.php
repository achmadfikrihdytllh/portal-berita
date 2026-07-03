@extends('admin.layouts.app')

@section('title', 'Edit Fokus')

@section('content')
    <form action="{{ route('admin.focuses.update', $focus) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.focuses._form')
    </form>
@endsection