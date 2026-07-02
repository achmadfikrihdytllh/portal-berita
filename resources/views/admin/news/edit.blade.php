@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('content')
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.news._form')
    </form>

    <form action="{{ route('admin.news.destroy', $news) }}" method="POST"
          onsubmit="return confirm('Hapus berita ini secara permanen?')" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:underline">Hapus berita ini</button>
    </form>
@endsection
