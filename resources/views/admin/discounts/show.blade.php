@extends('admin.layouts.app')

@section('title', 'Discount Details')

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Discount Details</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Discount Details</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-tags"></i> Discount Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Discount Value -->
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <strong>Discount Value:</strong>
                                    <p class="mb-0">{{ $discount->discount_value }}%</p>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <strong>Start Date:</strong>
                                    <p class="mb-0">{{ $discount->start_date }}</p>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <strong>End Date:</strong>
                                    <p class="mb-0">{{ $discount->end_date }}</p>
                                </div>
                            </div>
                        </div>

                        <hr>

              

                        <!-- Applied Products -->
          
                        <div class="p-3 border rounded bg-light mb-3">
                            <strong>Applied Products:</strong>
                            <div class="mt-2">
                                @foreach ($products as $product)
                                    <span class="badge bg-success p-2 m-1">{{ $product }}</span>
                                @endforeach
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection