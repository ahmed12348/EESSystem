@extends('vendor.layouts.app')

@section('title', 'Edit Discount')

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Edit Discount</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Discount</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('vendor.discounts.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Discount</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vendor.discounts.update', $discount->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                        
                            <!-- Discount Value -->
                            <div class="mb-3">
                                <label for="discount_value" class="form-label">Discount Percentage (%)</label>
                                <input type="number" name="discount_value" class="form-control" value="{{ $discount->discount_value }}" min="1" max="100" required>
                                @error('discount_value')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                       

                            <!-- Start Date -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $discount->start_date }}" required>
                                    @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                            </div>
                        
                            <!-- End Date -->
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $discount->end_date }}" required>
                                @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="p-3 border rounded bg-light mb-3">
                                <strong>Applied Products:</strong>
                                <div class="mt-2">
                                    @foreach ($products as $product)
                                        <span class="badge bg-success p-2 m-1">{{ $product->name }}</span>
                                    @endforeach
                                </div>
                               
                            </div>
                  
                        
                            <button type="submit" class="btn btn-primary">Update Discount</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
