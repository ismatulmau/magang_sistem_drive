@extends('layouts.app2')

@section('content')

<style>
    .custom-dropdown-width {
        min-width: 220px;
        /* Atur lebar minimum dropdown sesuai kebutuhan Anda */
        background-color: #ffffff;
        /* Warna background putih */
        border: 1px solid #dee2e6;
        /* Border tipis untuk membedakan dari background */
        padding: 8px;
        /* Memberikan sedikit padding di sekitar item dropdown */
    }

    .custom-dropdown-width {
        min-width: 200px;
        /* Lebar minimum dropdown */
        background-color: #ffffff;
        /* Background putih */
        border: 1px solid #dee2e6;
        /* Border tipis */
        padding: 8px;
        /* Padding di sekitar item dropdown */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Shadow untuk memberikan kesan kedalaman */
    }

    .dropdown-item {
        padding: 8px 12px;
        /* Mengatur padding untuk item */
        transition: background-color 0.2s ease;
        /* Animasi transisi saat hover */
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        /* Warna background saat di-hover */
    }
</style>
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('drive.index') }}" class="text-decoration-none">
                    <i class="fa fa-home mr-2"></i>Drive Saya
                </a>
            </li>
        </ol>
    </nav>

    <!-- Dropdown Menu untuk Buat Folder dan Upload File -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Tambah
                </button>
                <div class="dropdown-menu custom-dropdown-width">
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#newFolderModal">
                        <i class="fa fa-folder mr-2"></i> Buat Folder Baru
                    </a>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#uploadFileModal">
                        <i class="fa fa-upload mr-2"></i> Unggah File Baru
                    </a>
                </div>
            </div>
        </div>
        <!-- Filter -->
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary active" id="filter-folders">Folder</button>
            <button class="btn btn-outline-primary" id="filter-files">File</button>
        </div>
    </div>

    <!-- Tabel untuk Folder dan File -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-borderless">
        <thead class="thead-light">
            <tr>
                <th>Nama</th>
                <th>Pemilik</th>
                <th>Tanggal Terakhir Dirubah</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody id="folder-list">
            @foreach($folders as $folder)
                <tr>
                    <td>
                        <i class="fa fa-folder text-warning mr-2"></i>
                        <a href="{{ route('drive.folder', $folder->id) }}">{{ $folder->name }}</a>
                    </td>
                    <td>{{ $folder->user->nama }}</td>
                    <td>{{ $folder->updated_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $folder->parent ? $folder->parent->name : 'Drive Saya' }}</span>
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="folderActions{{ $folder->id }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right custom-dropdown-width"
                                    aria-labelledby="folderActions{{ $folder->id }}">

                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('drive.folder.download', $folder->id) }}"
                                        class="btn btn-primary"> <i class="fa fa-download mr-2"></i> Download Folder</a>

                                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                                        data-target="#renameFolderModal{{ $folder->id }}">
                                        <i class="fa fa-edit mr-2"></i> Ganti Nama
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"
                                        onclick="event.preventDefault(); document.getElementById('duplicate-folder-form-{{ $folder->id }}').submit();">
                                        <i class="fa fa-copy mr-2"></i> Buat Salinan
                                    </a>
                                    <form id="duplicate-folder-form-{{ $folder->id }}"
                                        action="{{ route('drive.folder.duplicate', $folder->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="fa fa-share mr-2"></i> Bagikan
                                    </a>
                                    <form action="{{ route('star.folder', $folder->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center"><i class="fa fa-star mr-2"></i>Tambahkan ke Bintang</button>
                                    </form>
                                    <a class="dropdown-item d-flex align-items-center text-danger" href="#"
                                        onclick="event.preventDefault(); document.getElementById('delete-folder-form-{{ $folder->id }}').submit();">
                                        <i class="fa fa-trash mr-2"></i> Hapus
                                    </a>
                                    <form id="delete-folder-form-{{ $folder->id }}"
                                        action="{{ route('drive.folder.destroy', $folder->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tbody id="file-list" style="display: none;">
            @foreach($files as $file)
                <tr>
                    <td>
                        <i class="fa fa-file mr-2"></i>
                        <a href="{{ asset($file->path) }}" download>{{ $file->name }}</a>
                    </td>
                    <td>{{ $file->user->nama }}</td>
                    <td>{{ $file->updated_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $file->folder ? $file->folder->name : 'Drive Saya' }}</span>
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="fileActions{{ $file->id }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right custom-dropdown-width"
                                    aria-labelledby="fileActions{{ $file->id }}">
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('drive.file.download', $file->id) }}">
                                        <i class="fa fa-download mr-2"></i> Download File
                                    </a>

                                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                                        data-target="#renameFileModal{{ $file->id }}"> <i class="fa fa-edit mr-2"></i> Ganti
                                        Nama</a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"
                                        onclick="event.preventDefault(); document.getElementById('duplicate-file-form-{{ $file->id }}').submit();">
                                        <i class="fa fa-copy mr-2"></i> Buat
                                        Salinan</a>
                                    <form id="duplicate-file-form-{{ $file->id }}"
                                        action="{{ route('drive.file.duplicate', $file->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="fa fa-share mr-2"></i> Bagikan
                                    </a>
                                    <form action="{{ route('star.file', $file->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center"><i class="fa fa-star mr-2"></i> Tambahkan ke Bintang</button>
                                    </form>
                                    <a class="dropdown-item d-flex align-items-center text-danger" href="#"
                                        onclick="event.preventDefault(); document.getElementById('delete-file-form-{{ $file->id }}').submit();">
                                        <i class="fa fa-trash mr-2"></i> Hapus
                                    </a>
                                    <form id="delete-file-form-{{ $file->id }}"
                                        action="{{ route('drive.file.destroy', $file->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Ganti Nama Folder -->
@foreach($folders as $folder)
    <div class="modal fade" id="renameFolderModal{{ $folder->id }}" tabindex="-1" role="dialog"
        aria-labelledby="renameFolderModalLabel{{ $folder->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="renameFolderModalLabel{{ $folder->id }}">Ganti Nama Folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('drive.folder.rename', $folder->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="folder-name">Nama Folder Baru</label>
                            <input type="text" class="form-control" id="folder-name" name="name" value="{{ $folder->name }}"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ganti Nama</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Ganti Nama File -->
@foreach($files as $file)
    <div class="modal fade" id="renameFileModal{{ $file->id }}" tabindex="-1" role="dialog"
        aria-labelledby="renameFileModalLabel{{ $file->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="renameFileModalLabel{{ $file->id }}">Ganti Nama File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('drive.file.rename', $file->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file-name">Nama File Baru</label>
                            <input type="text" class="form-control" id="file-name" name="name" value="{{ $file->name }}"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ganti Nama</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Buat Folder Baru -->
<div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newFolderModalLabel">Buat Folder Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('drive.folder.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="folder-name">Nama Folder</label>
                        <input type="text" class="form-control" id="folder-name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Unggah File Baru di Drive Saya -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFileModalLabel">Unggah File Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('drive.file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file-upload">Pilih File</label>
                        <input type="file" class="form-control-file" id="file-upload" name="file" required>
                    </div>
                    <!-- Tidak perlu input folder_id karena akan disimpan di root directory -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk mengganti tampilan antara File dan Folder
    document.getElementById('filter-files').addEventListener('click', function () {
        document.getElementById('file-list').style.display = '';
        document.getElementById('folder-list').style.display = 'none';
        this.classList.add('active');
        document.getElementById('filter-folders').classList.remove('active');
    });

    document.getElementById('filter-folders').addEventListener('click', function () {
        document.getElementById('file-list').style.display = 'none';
        document.getElementById('folder-list').style.display = '';
        this.classList.add('active');
        document.getElementById('filter-files').classList.remove('active');
    });
</script>
@endsection