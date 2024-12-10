@extends('layouts.app2')

@section('content')
    <h1>Edit User Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.updateUserSettings', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="max_storage">Max Storage (in bytes):</label>
            <input type="number" name="max_storage" id="max_storage" class="form-control" value="{{ $user->max_storage }}" required>
        </div>

        <div class="form-group">
            <label for="allowed_file_types">Allowed File Types (comma separated):</label>
            <input type="text" name="allowed_file_types" id="allowed_file_types" class="form-control" value="{{ $user->allowed_file_types }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
@endsection
