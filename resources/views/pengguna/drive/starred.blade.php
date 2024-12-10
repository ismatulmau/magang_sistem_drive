@extends('layouts.app2')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Item yang Dibintangi</h2>

    <table class="table table-hover table-borderless shadow-sm rounded">
        <thead class="thead-light">
            <tr>
                <th>Nama File/Folder</th>
                <th>Tipe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($starredFiles as $file)
                <tr>
                    <td><i class="fa fa-file text-secondary mr-2"></i> {{ $file->name }}</td>
                    <td>File</td>
                </tr>
            @endforeach

            @foreach($starredFolders as $folder)
                <tr>
                    <td>  <i class="fa fa-folder text-warning mr-2"></i> {{ $folder->name }}</td>
                    <td>Folder</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
