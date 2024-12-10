@extends('layouts.app2')

@section('content')
<h3>Item di Sampah</h3>
<ul>
    @foreach($items as $item)
        <li>{{ $item->name }} <button onclick="restoreItem({{ $item->id }})">Restore</button></li>
    @endforeach
</ul>
<script>
    function restoreItem(id) {
        $.post('{{ url("drive/restore-item") }}/'+id, {_token: '{{ csrf_token() }}'}, function(data) {
            location.reload();
        });
    }
</script>
@endsection