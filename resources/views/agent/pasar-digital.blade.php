@extends('layouts.app')

@section('title', 'Pasar Digital - Agent')

@section('content')
@include('agent._header')

<div class="container my-5">
    <div class="text-center py-5">
        <img src="{{ asset('images/empty_marketplace.svg') }}" alt="Pasar Digital Kosong" style="max-width: 320px;" onerror="this.style.display='none'">
        <h3 class="mt-4">Pasar Digital (Agent)</h3>
        <p class="text-muted">Halaman Pasar Digital untuk partner agent sedang dalam pengembangan. Sekarang masih kosong.</p>
        <a href="{{ route('agent.dashboard') }}" class="btn btn-primary mt-3">Kembali ke Dashboard Agent</a>
    </div>
</div>
@endsection
