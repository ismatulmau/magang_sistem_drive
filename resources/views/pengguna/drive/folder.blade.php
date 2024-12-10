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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('drive.index') }}"> <i class="fa fa-home mr-2"></i> Drive
                    Saya</a></li>
            @if ($folder->parent)
                @foreach ($folder->parent->ancestorsAndSelf as $ancestor)
                    <li class="breadcrumb-item">
                        <a href="{{ route('drive.folder', $ancestor->id) }}">{{ $ancestor->name }}</a>
                    </li>
                @endforeach
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $folder->name }}</li>
        </ol>
    </nav>

    <!-- Tombol Dropdown -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Tambah
                </button>
                <div class="dropdown-menu custom-dropdown-width">
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#createFolderModal"> <i class="fa fa-folder mr-2"></i> Buat Folder
                        Baru</a>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#uploadFileModal"> <i class="fa fa-upload mr-2"></i> Unggah File
                        Baru</a>
                </div>
            </div>
        </div>
        <!-- Filter -->
        <div class="btn-group" role="group">
            <button class="btn btn-outline-primary active" id="filter-folders">Folder</button>
            <button class="btn btn-outline-primary" id="filter-files">File</button>
        </div>
    </div>


    <!-- Modal Buat Folder -->
    <div class="modal fade" id="createFolderModal" tabindex="-1" role="dialog" aria-labelledby="createFolderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFolderModalLabel">Buat Folder Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('drive.createFolder') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="folder-name">Nama Folder:</label>
                            <input type="text" name="name" class="form-control" id="folder-name" required>
                        </div>
                        <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Unggah File Baru di Dalam Folder -->
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
                        <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Unggah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @foreach($subFolders as $subFolder)
        <!-- Modal Rename Folder -->
        <div class="modal fade" id="renameFolderModal{{ $subFolder->id }}" tabindex="-1" role="dialog"
            aria-labelledby="renameFolderModalLabel{{ $subFolder->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renameFolderModalLabel{{ $subFolder->id }}">Ganti Nama Folder</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('drive.folder.rename', $subFolder->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="folder-name">Nama Folder Baru:</label>
                                <input type="text" name="name" class="form-control" id="folder-name"
                                    value="{{ $subFolder->name }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    <!-- Tampilkan Sub-Folder dan File dalam Tabel -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-hover table-borderless shadow-sm rounded">
        <thead class="thead-light">
            <tr>
                <th>Nama</th>
                <th>Pemilik</th>
                <th>Tanggal Terakhir Dirubah</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody id="folder-list">
            @foreach($subFolders as $subFolder)
                <tr>
                    <td>
                        <i class="fa fa-folder text-warning mr-2"></i>
                        <a href="{{ route('drive.folder', $subFolder->id) }}">{{ $subFolder->name }}</a>
                        @if($subFolder->starred) <!-- Cek apakah folder sudah ditambahkan ke Bintang -->
                <i class="fa fa-star text-warning ml-2"></i> <!-- Tampilkan ikon bintang -->
            @endif
                    </td>
                    <td>{{ $subFolder->user->nama }}</td>
                    <td>{{ $subFolder->updated_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $subFolder->parent ? $subFolder->parent->name : 'Root' }}</span>
                            <!-- Dropdown Titik Tiga -->
                            <div class="dropdown">
                                <button class="btn btn-link p-0" type="button" id="folderActions{{ $subFolder->id }}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right custom-dropdown-width"
                                    aria-labelledby="folderActions{{ $subFolder->id }}">
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('drive.folder.download', $subFolder->id) }}"
                                        class="btn btn-primary"> <i class="fa fa-download mr-2"></i> Download Folder</a>
                                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                                        data-target="#renameFolderModal{{ $subFolder->id }}">
                                        <i class="fa fa-edit mr-2"></i> Ganti Nama
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"
                                        onclick="event.preventDefault(); document.getElementById('duplicate-folder-form-{{ $subFolder->id }}').submit();">
                                        <i class="fa fa-copy mr-2"></i> Buat Salinan
                                    </a>
                                    <form id="duplicate-folder-form-{{ $subFolder->id }}"
                                        action="{{ route('drive.folder.duplicate', $subFolder->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="fa fa-share mr-2"></i> Bagikan
                                    </a>
                                    <form action="{{ route('star.folder', $subFolder->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center"><i class="fa fa-star mr-2"></i> Tambahkan ke Bintang</button>
                                    </form>
                                    <a class="dropdown-item d-flex align-items-center" href="#"
                                        onclick="if(confirm('Anda yakin ingin menghapus folder ini?')) { event.preventDefault(); document.getElementById('delete-folder-form-{{ $subFolder->id }}').submit(); }">
                                        <i class="fa fa-trash mr-2"></i> Hapus
                                    </a>
                                    <form id="delete-folder-form-{{ $subFolder->id }}"
                                        action="{{ route('drive.folder.destroy', $subFolder->id) }}" method="POST"
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
                        <i class="fa fa-file text-secondary mr-2"></i>
                        <a href="{{ asset($file->path) }}" download>{{ $file->name }}</a>
                        @if($file->starred) <!-- Cek apakah file sudah ditambahkan ke Bintang -->
                <i class="fa fa-star text-warning ml-2"></i> <!-- Tampilkan ikon bintang -->
            @endif
                    </td>
                    <td>{{ $file->user->nama }}</td>
                    <td>{{ $file->updated_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $file->folder ? $file->folder->name : 'Root' }}</span>
                            <!-- Dropdown Titik Tiga -->
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
                                        data-target="#renameFileModal{{ $file->id }}">
                                        <i class="fa fa-edit mr-2"></i> Ganti Nama
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"
                                        onclick="event.preventDefault(); document.getElementById('duplicate-file-form-{{ $file->id }}').submit();">
                                        <i class="fa fa-copy mr-2"></i> Buat Salinan
                                    </a>
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
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="fa fa-trash mr-2"></i> Hapus
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