@extends('admin.layouts.app')

@section('title', 'Edit Edisi E-koran')

@section('content')
    <form action="{{ route('admin.epapers.update', $epaper) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.epapers._form')
    </form>
@endsection