@extends('layouts.app2')

@section('content')
<h3>Kapasitas Penyimpanan</h3>
<p>{{ $usedStorage }}MB dari {{ $totalStorage }}MB digunakan.</p>
@endsection