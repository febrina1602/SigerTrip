@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #fff9e6 0%, #fff5d9 100%);">
	<div class="container">
		{{-- Header Section --}}
		<div class="row align-items-center mb-5">
			<div class="col-md-6">
				<h1 class="h2 fw-bold mb-2" style="color: #333;">Agen Tour Lokal</h1>
				<p class="text-muted mb-0">Temukan pemandu wisata profesional dan terpercaya untuk petualangan Anda</p>
			</div>
			<div class="col-md-6">
				<form class="d-flex gap-2" action="/pemandu-wisata" method="GET">
					<input type="text" name="q" class="form-control rounded-3" placeholder="Cari pemandu wisata..." value="{{ request('q') }}">
					<button type="submit" class="btn btn-outline-dark rounded-3">
						<i class="fas fa-search"></i>
					</button>
				</form>
			</div>
		</div>

		{{-- Empty State --}}
		@if(empty($localTourAgents) || $localTourAgents->count() === 0)
			<div class="text-center py-5">
				<i class="fas fa-users text-muted" style="font-size: 3rem; margin-bottom: 1rem;"></i>
				<p class="text-muted h5">Belum ada pemandu wisata ditemukan</p>
			</div>
		@else
			{{-- Cards Grid --}}
			<div class="row g-4">
				@foreach($localTourAgents as $agent)
					<div class="col-lg-4 col-md-6">
						<div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden transition" style="transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
							{{-- Image Section --}}
							<div class="position-relative" style="height: 200px; background: #f0f0f0; overflow: hidden;">
								@if($agent->banner_image_url)
									<img src="{{ $agent->banner_image_url }}" alt="{{ $agent->name }}" class="w-100 h-100 object-fit-cover">
								@else
									<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
										<i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
									</div>
								@endif
								
								{{-- Rating Badge --}}
								@if($agent->rating)
									<div class="position-absolute top-3 end-3 bg-warning rounded-2 px-3 py-2" style="background-color: #f59e0b !important;">
										<div class="d-flex align-items-center gap-1">
											<i class="fas fa-star" style="color: white; font-size: 0.8rem;"></i>
											<span class="fw-bold text-white" style="font-size: 0.9rem;">{{ number_format($agent->rating, 1) }}</span>
										</div>
									</div>
								@endif

								{{-- Verified Badge --}}
								@if($agent->is_verified)
									<div class="position-absolute bottom-3 end-3 bg-success rounded-2 px-2 py-1">
										<span class="text-white fw-bold" style="font-size: 0.8rem;">
											<i class="fas fa-check-circle me-1"></i>Terverifikasi
										</span>
									</div>
								@endif
							</div>

							{{-- Card Body --}}
							<div class="card-body">
								<h5 class="card-title fw-bold mb-1" style="color: #333;">{{ $agent->name }}</h5>
								
								{{-- Location --}}
								<div class="d-flex align-items-center gap-2 mb-3">
									<i class="fas fa-map-marker-alt text-danger" style="font-size: 0.9rem;"></i>
									<small class="text-muted">{{ $agent->address ?? $agent->location ?? 'Lokasi tidak tersedia' }}</small>
								</div>

								{{-- Description --}}
								<p class="card-text text-muted small mb-3" style="line-height: 1.5;">
									{{ \Illuminate\Support\Str::limit($agent->description ?? 'Agen tour lokal terpercaya', 80) }}
								</p>

								{{-- Contact Info --}}
								@if($agent->contact_phone)
									<div class="d-flex align-items-center gap-2 mb-3">
										<i class="fas fa-phone-alt text-primary" style="font-size: 0.9rem;"></i>
										<small class="text-muted">{{ $agent->contact_phone }}</small>
									</div>
								@endif

								{{-- CTA Button --}}
								<a href="/agents/{{ $agent->id }}" class="btn btn-primary w-100 rounded-3 fw-bold mt-3">
									Lihat Profil <i class="fas fa-arrow-right ms-2"></i>
								</a>
							</div>
						</div>
					</div>
				@endforeach
			</div>

			{{-- Pagination --}}
			@if($localTourAgents->hasPages())
				<div class="d-flex justify-content-center mt-5">
					{{ $localTourAgents->links() }}
				</div>
			@endif
		@endif
	</div>
</div>

<style>
	.object-fit-cover {
		object-fit: cover;
	}
	
	.transition {
		transition: all 0.3s ease;
	}
	
	.card:hover {
		box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
	}
</style>
@endsection
