@extends('layouts.app2')

@section('content')
<form action="{{ route('drive.storeFolderUploads') }}" class="dropzone" id="folderDropzone" enctype="multipart/form-data">
    @csrf
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.js"></script>
<script>
    Dropzone.options.folderDropzone = {
        paramName: 'folder_files[]',
        maxFilesize: 10, // MB
        uploadMultiple: true,
        acceptedFiles: 'image/*,application/pdf,.psd',
        success: function(file, response) {
            console.log(response);
        }
    };
</script>
@endsection