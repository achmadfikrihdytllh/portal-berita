@extends('admin.layouts.app')

@section('title', 'Tambah Edisi E-koran')

@section('content')
    <form action="{{ route('admin.epapers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.epapers._form')
    </form>
@endsection