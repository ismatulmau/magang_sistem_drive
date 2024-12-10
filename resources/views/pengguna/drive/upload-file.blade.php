@extends('layouts.app2')

@section('content')
<form action="{{ route('drive.storeFile') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">Pilih File:</label>
    <input type="file" id="file" name="file" required>
    <button type="submit">Upload File</button>
</form>
@endsection