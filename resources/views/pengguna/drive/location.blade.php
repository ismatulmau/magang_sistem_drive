@extends('layouts.app2')

@section('content')
<div class="container">
    <h3>Lokasi File: {{ $file->name }}</h3>
    <p>Lokasi file: {{ $file->path }}</p>
    <!-- Tambahkan informasi atau tampilan lokasi file di sini -->
    <a href="{{ asset($file->path) }}" class="btn btn-primary">Unduh File</a>
</div>
@endsection
