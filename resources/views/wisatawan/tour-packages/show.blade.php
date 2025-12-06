@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: #f8f9fa; padding-top: 4.5rem;">
	<div class="container">
		{{-- Back Button --}}
		<a href="{{ route('tour-packages.index') }}" class="btn btn-outline-secondary rounded-3 mb-4">
			<i class="fas fa-arrow-left me-2"></i> Kembali
		</a>

			{{-- Header Image (full width) --}}
				@if(!empty($package->cover_image_url))
					<div class="mb-4">
						<img src="{{ $package->cover_image_url }}" alt="{{ $package->name }}" class="img-fluid rounded-4 w-100" style="height: 420px; object-fit: cover;">
					</div>
			@else
				<div class="bg-light rounded-4 mb-4 d-flex align-items-center justify-content-center" style="height: 420px;">
					<i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
				</div>
			@endif

			<div class="row">
				{{-- Main Content --}}
				<div class="col-lg-8">
					{{-- Package Info --}}
					<div class="card border-0 rounded-4 shadow-sm">
					<div class="card-body">
						<h1 class="h2 fw-bold mb-4">{{ $package->name }}</h1>
						
						{{-- Duration & Details --}}
						<div class="row mb-4">
							@if($package->duration)
								<div class="col-md-6 mb-3">
									<div class="p-3 bg-light rounded-2">
										<p class="mb-1 text-muted small"><i class="fas fa-clock me-2" style="color: #FF9739;"></i>Durasi</p>
										<h6 class="mb-0">{{ $package->duration }}</h6>
									</div>
								</div>
							@endif

							@if($package->duration_days)
								<div class="col-md-6 mb-3">
									<div class="p-3 bg-light rounded-2">
										<p class="mb-1 text-muted small"><i class="fas fa-sun me-2" style="color: #FF9739;"></i>Hari</p>
										<h6 class="mb-0">{{ $package->duration_days }} Hari</h6>
									</div>
								</div>
							@endif

							@if($package->duration_nights)
								<div class="col-md-6 mb-3">
									<div class="p-3 bg-light rounded-2">
										<p class="mb-1 text-muted small"><i class="fas fa-moon me-2" style="color: #FF9739;"></i>Malam</p>
										<h6 class="mb-0">{{ $package->duration_nights }} Malam</h6>
									</div>
								</div>
							@endif

							@if($package->minimum_participants)
								<div class="col-md-6 mb-3">
									<div class="p-3 bg-light rounded-2">
										<p class="mb-1 text-muted small"><i class="fas fa-users me-2" style="color: #FF9739;"></i>Peserta Minimum</p>
										<h6 class="mb-0">{{ $package->minimum_participants }} Orang</h6>
									</div>
								</div>
							@endif
						</div>

						{{-- Price Section --}}
						<div class="alert alert-light border-2 rounded-3 mb-4" style="border-color: #FF9739;">
							<p class="mb-2 text-muted small">Harga Per Orang</p>
							<h3 class="mb-0 fw-bold" style="color: #FF9739;">
								Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
							</h3>
						</div>

						{{-- Description --}}
						@if(!empty($package->description))
							<div class="mb-4">
								<h5 class="fw-bold mb-3">Deskripsi</h5>
								<p class="text-muted" style="line-height: 1.8;">{{ $package->description }}</p>
							</div>
						@endif

						{{-- Destinations --}}
						@if(!empty($package->destinations_visited))
							<div class="mb-4">
								<h5 class="fw-bold mb-3"><i class="fas fa-map-pin me-2" style="color: #FF9739;"></i>Destinasi yang Dikunjungi:</h5>
								@php
									$destinations = is_array($package->destinations_visited) ? $package->destinations_visited : json_decode($package->destinations_visited, true) ?? [];
								@endphp
								<ul class="list-unstyled">
									@foreach($destinations as $destination)
										<li class="mb-2">
											<i class="fas fa-check-circle me-2" style="color: #FF9739;"></i>
											{{ $destination }}
										</li>
									@endforeach
								</ul>
							</div>
						@endif

						{{-- Facilities --}}
						@if(!empty($package->facilities))
							<div class="mb-4">
								<h5 class="fw-bold mb-3"><i class="fas fa-star me-2" style="color: #FF9739;"></i>Fasilitas:</h5>
								@php
									$facilities = is_array($package->facilities) ? $package->facilities : json_decode($package->facilities, true) ?? [];
								@endphp
								<ul class="list-unstyled">
									@foreach($facilities as $facility)
										<li class="mb-2">
											<i class="fas fa-check-circle me-2" style="color: #FF9739;"></i>
											{{ $facility }}
										</li>
									@endforeach
								</ul>
							</div>
						@endif

						{{-- Availability --}}
						@if(!empty($package->availability_period))
							<div>
								<h5 class="fw-bold mb-2"><i class="fas fa-calendar me-2" style="color: #FF9739;"></i>Periode Ketersediaan</h5>
								<p class="text-muted">{{ $package->availability_period }}</p>
							</div>
						@endif
					</div>
				</div>
			</div>

			{{-- Sidebar --}}
			<div class="col-lg-4">
				{{-- Agent Card --}}
				@if($package->agent)
					@if(isset($isAgent) && $isAgent)
						<div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="position: sticky; top: 20px;">
							<div class="bg-light p-4 text-center">
								@if($package->agent->banner_image_url)
									<img src="{{ $package->agent->banner_image_url }}" alt="{{ $package->agent->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #FF9739;">
								@else
									<div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary" style="width: 80px; height: 80px; border: 3px solid #FF9739;">
										<i class="fas fa-user text-white" style="font-size: 2rem;"></i>
									</div>
								@endif
								
								<h5 class="fw-bold mt-4 mb-1">{{ $package->agent->name }}</h5>
								@if($package->agent->rating)
									<div class="mb-4">
										<i class="fas fa-star" style="color: #FFB800;"></i>
										<span class="fw-bold ms-1">{{ number_format($package->agent->rating, 1) }}/5</span>
									</div>
								@endif
								
								{{-- Harga Mulai Dari Section --}}
								<div class="alert alert-light rounded-3 mb-4">
									<p class="mb-1 text-muted small">Harga Mulai Dari</p>
									<h4 class="mb-0 fw-bold" style="color: #FF9739;">
										Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
									</h4>
									<small class="text-muted">per orang</small>
								</div>
								
								{{-- WhatsApp Contact Button --}}
								@if($package->agent->contact_phone)
									@php
										$phone = preg_replace('/[^0-9]/', '', $package->agent->contact_phone);
										if (substr($phone, 0, 1) === '0') {
											$phone = '62' . substr($phone, 1);
										}
										$waLink = "https://wa.me/{$phone}?text=Halo,%20saya%20tertarik%20dengan%20paket%20wisata%20{$package->name}%20seharga%20Rp%20" . number_format($package->price_per_person, 0, ',', '.') . "%20per%20orang.%20Bisa%20diinformasikan%20lebih%20lengkap?";
									@endphp
									<a href="{{ $waLink }}" target="_blank" class="btn btn-success w-100 rounded-3 fw-bold py-3 mb-2" style="background-color: #25D366;">
										<i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
									</a>
								@endif
								
								<a href="/agents/{{ $package->agent->id }}" class="btn btn-outline-dark w-100 rounded-3 fw-bold py-2">
									Lihat Profil Lengkap
								</a>
							</div>
						</div>
					@else
						<div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="position: sticky; top: 20px;">
							<div class="bg-light p-4 text-center">
								{{-- For non-agent users: show price and contact only (hide agent identity) --}}
								<div class="alert alert-light rounded-3 mb-4">
									<p class="mb-1 text-muted small">Harga Mulai Dari</p>
									<h4 class="mb-0 fw-bold" style="color: #FF9739;">
										Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
									</h4>
									<small class="text-muted">per orang</small>
								</div>
								@if($package->agent->contact_phone)
									@php
										$phone = preg_replace('/[^0-9]/', '', $package->agent->contact_phone);
										if (substr($phone, 0, 1) === '0') {
											$phone = '62' . substr($phone, 1);
										}
										$waLink = "https://wa.me/{$phone}?text=Halo,%20saya%20tertarik%20dengan%20paket%20wisata%20{$package->name}%20seharga%20Rp%20" . number_format($package->price_per_person, 0, ',', '.') . "%20per%20orang.%20Bisa%20diinformasikan%20lebih%20lengkap?";
									@endphp
									<a href="{{ $waLink }}" target="_blank" class="btn btn-success w-100 rounded-3 fw-bold py-3 mb-2" style="background-color: #25D366;">
										<i class="fab fa-whatsapp me-2"></i> Hubungi Kami
									</a>
								@endif
							</div>
						</div>
					@endif
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
