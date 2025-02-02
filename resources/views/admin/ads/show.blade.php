@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Breadcrumb Section -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
        <div class="breadcrumb-title pe-3">Advertisements</div>
        <div class="ms-auto">
            <a href="{{ route('admin.ads.index') }}" class="btn btn-outline-primary">Back to Ads</a>
        </div>
    </div>

    <!-- Ad Details Section -->
    <div class="row justify-content-center">
        <!-- Ad Overview Card -->
        <div class="col-xl-8">
            <div class="card shadow-lg border-light">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Ad Overview</h5>
                </div>
                <div class="card-body">

                    <!-- Ad Title -->
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bx bx-tag-alt text-primary fs-4 me-3"></i>
                        <div>
                            <label for="title" class="form-label font-weight-bold">Ad Title</label>
                            <p class="lead text-dark">{{ $ad->title }}</p>
                        </div>
                    </div>

                    <!-- Ad Description -->
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bx bx-file text-secondary fs-4 me-3"></i>
                        <div>
                            <label for="description" class="form-label font-weight-bold">Description</label>
                            <p class="text-muted">{{ $ad->description }}</p>
                        </div>
                    </div>

                    <!-- Ad Type -->
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bx bx-info-circle text-success fs-4 me-3"></i>
                        <div>
                            <label for="type" class="form-label font-weight-bold">Ad Type</label>
                            <p class="badge bg-info text-white">{{ ucfirst($ad->type) }}</p>
                        </div>
                    </div>

                    <!-- Reference or Zone Section -->
                    @if($ad->type == 'product' || $ad->type == 'category')
                        <div class="mb-4 d-flex align-items-center">
                            <i class="bx bx-link-alt text-warning fs-4 me-3"></i>
                            <div>
                                <label for="reference" class="form-label font-weight-bold">Reference</label>
                                <p class="lead text-dark">
                                    @if($ad->reference)
                                        {{ $ad->reference->name }}
                                    @else
                                        <span class="text-danger">No reference available</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @elseif($ad->type == 'zone')
                        <div class="mb-4 d-flex align-items-center">
                            <i class="bx bx-location-plus text-info fs-4 me-3"></i>
                            <div>
                                <label for="zone" class="form-label font-weight-bold">Zone</label>
                                <p class="badge bg-secondary text-white">{{ ucfirst($ad->zone) }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Image Display -->
                    @if($ad->image)
                        <div class="mb-4 text-center">
                            <label for="image" class="form-label font-weight-bold">Ad Image</label><br>
                            @if ($ad->image)
                            <img src="{{ asset('storage/' . $ad->image) }}" alt="Ad Image" class="img-fluid rounded shadow-sm" width="350">
                        @else 
                            <img src="{{ asset('assets/images/default-product.png') }}" 
                                 class="img-fluid rounded shadow-sm" 
                                 width="350" >
                        @endif 
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
