@extends('layouts.app')

@section('title', 'Paket Perjalanan - ' . ($agent->name ?? 'Agen Tour') . ' - SigerTrip')

@section('content')
<div class="bg-white min-vh-100">
    @include('components.layout.header')

    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.index') }}" class="text-decoration-none">Pemandu Wisata</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.show', $agent->id) }}" class="text-decoration-none">{{ $agent->name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Paket Perjalanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="bg-light py-5">
        <div class="container">
            <div class="mb-4 text-start">
                <h1 class="h3 fw-bold text-dark mb-2">Paket Perjalanan dari {{ $agent->name }}</h1>
                <p class="text-muted">Pilih paket perjalanan yang sesuai dengan kebutuhan Anda</p>
            </div>
            
            @if($tourPackages->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($tourPackages as $package)
                <div class="col">
                    <div class="card shadow-sm border-light h-100">