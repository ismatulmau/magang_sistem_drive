@extends('layouts.app2')

@section('content')
<div class="container">
    <h3 class="mb-4">File Terbaru</h3>
    <table class="table table-borderless">
        <thead class="thead-light">
            <tr>
                <th>Nama File</th>
                <th>Tanggal Terakhir Diperbarui</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody id="folder-list">
            @foreach($files as $file)
                <tr>
                    <td> <i class="fa fa-file text-secondary mr-2"></i> {{ $file->name }}</td>
                    <td>{{ $file->updated_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $file->folder ? $file->folder->name : 'Drive Saya' }}</span>
                            <!-- Dropdown Titik Tiga -->
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="fileActions{{ $file->id }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right custom-dropdown-width"
                                    aria-labelledby="fileActions{{ $file->id }}">
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('drive.file.showLocation', $file->id) }}">
                                        <i class="fa fa-location-arrow mr-2"></i> Tampilkan Lokasi
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ asset($file->path) }}" download>
                                        <i class="fa fa-download mr-2"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .custom-dropdown-width {
        min-width: 150px;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        padding: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        padding: 8px 12px;
        transition: background-color 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
