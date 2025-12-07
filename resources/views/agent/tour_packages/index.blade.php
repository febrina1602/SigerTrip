@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
	<div class="container">
		{{-- Header Section --}}
		<div class="row align-items-center mb-4">
			<div class="col-md-8">
				<h1 class="h2 fw-bold mb-2" style="color: #333;">
					@if($isAgent)
						Paket Perjalanan Saya
					@else
						Paket Perjalanan
					@endif
				</h1>
				<p class="text-muted mb-0">
					@if($isAgent)
						Kelola dan pantau semua paket wisata Anda
					@else
						Jelajahi berbagai paket wisata menarik dari agen lokal terpercaya
					@endif
				</p>
			</div>
			@if($isAgent)
				<div class="col-md-4 text-end">
					<a href="{{ route('tour-packages.create') }}" class="btn btn-primary btn-lg rounded-3 px-4">
						<i class="fas fa-plus me-2"></i> Paket Baru
					</a>
				</div>
			@endif
		</div>

		{{-- Alert Messages --}}
		@if(session('success'))
			<div class="alert alert-success border-0 rounded-3 alert-dismissible fade show mb-4" role="alert">
				<i class="fas fa-check-circle me-2"></i> {{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if(session('error'))
			<div class="alert alert-danger border-0 rounded-3 alert-dismissible fade show mb-4" role="alert">
				<i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		{{-- Empty State --}}
		@if(empty($packages) || $packages->count() === 0)
			<div class="card border-0 rounded-4 shadow-sm">
				<div class="card-body text-center py-5">
					<i class="fas fa-box-open text-muted" style="font-size: 3rem; margin-bottom: 1rem;"></i>
					<p class="mb-0 text-muted h5">
						@if($isAgent)
							Belum ada paket perjalanan
						@else
							Belum ada paket perjalanan tersedia
						@endif
					</p>
					@if($isAgent)
						<p class="text-muted small mt-2">Mulai buat paket wisata Anda sekarang!</p>
					@endif
				</div>
			</div>
		@else
			{{-- Cards Grid (Minimalist Style - Vertical Layout) --}}
			<div class="row g-4">
				@foreach($packages as $package)
					<div class="col-lg-4 col-md-6">
						<div class="card border-0 rounded-3 shadow-sm h-100 overflow-hidden" style="transition: all 0.3s ease;">
							
							{{-- Image Section (Top) --}}
							<div style="height: 180px; background: #f0f0f0; overflow: hidden;">
								@if(!empty($package->cover_image_url))
									<img src="{{ $package->cover_image_url }}" alt="{{ $package->name }}" class="w-100 h-100" style="object-fit: cover;">
								@else
									<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
										<i class="fas fa-image text-muted" style="font-size: 2.5rem;"></i>
									</div>
								@endif
							</div>

							{{-- Card Body (Bottom) --}}
							<div class="card-body p-3 d-flex flex-column justify-content-between" style="flex-grow: 1;">
								<h5 class="card-title fw-bold mb-3" style="color: #333; font-size: 1rem;">{{ $package->name }}</h5>
								
								{{-- Action Buttons --}}
								@if($isAgent)
									{{-- Agent Buttons (Edit & Delete) --}}
									<div class="d-flex gap-2">
										<a href="{{ route('tour-packages.edit', $package->id) }}" class="btn btn-sm rounded-2" style="background: #c84c3d; color: white; font-weight: 600; flex: 1;">
											<i class="fas fa-edit me-1"></i> Edit
										</a>
										<button type="button" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus" 
												onclick="if(confirm('Apakah Anda yakin ingin menghapus paket ini?')) { document.getElementById('delete-form-{{ $package->id }}').submit(); }">
											<i class="fas fa-trash"></i>
										</button>
										<form id="delete-form-{{ $package->id }}" action="{{ route('tour-packages.destroy', $package->id) }}" method="POST" style="display:none;">
											@csrf
											@method('DELETE')
										</form>
									</div>
								@else
									{{-- User Detail Button --}}
									<a href="/tour-packages/{{ $package->id }}" class="btn btn-sm rounded-2" style="background: #c84c3d; color: white; font-weight: 600; display: inline-block; padding: 0.4rem 1.2rem;">
										Rincian
									</a>
								@endif
							</div>
						</div>
					</div>
				@endforeach
			</div>

			{{-- Pagination --}}
			@if($packages->hasPages())
				<div class="d-flex justify-content-center mt-5">
					{{ $packages->links() }}
				</div>
			@endif
		@endif
	</div>
</div>

<style>
	.card {
		transition: all 0.3s ease;
	}
	
	.card:hover {
		box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
	}
</style>
@endsection