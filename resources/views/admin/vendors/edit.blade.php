@extends('admin.layouts.app')

@section('title', 'Edit Vendor')

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Vendors</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Vendor</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <!-- Back Button -->
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
        <!-- End Breadcrumb -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <!-- User Edit Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Vendor</h6>
                <hr/>
                <div class="card">
                    <div class="card-body">
                      

                        <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
        
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                    value="{{ old('business_name', $vendor->vendor->business_name ?? '') }}" required>
                                @error('business_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Business Number -->
                            <div class="mb-3">
                                <label for="business_number" class="form-label">Business Number</label>
                                <input type="text" class="form-control" id="business_number" name="business_number" 
                                    value="{{ old('business_number', $vendor->vendor?->business_number) }}" required>
                                @error('business_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
        
                    
        
                            <div class="mb-3">
                                <label for="zone" class="form-label">Zone</label>
                                <select class="form-select" id="zone" name="zone" required>
                                    <option value="Zone 1" {{ old('zone', $vendor->zone) == 'Zone 1' ? 'selected' : '' }}>Zone 1</option>
                                    <option value="Zone 2" {{ old('zone', $vendor->zone) == 'Zone 2' ? 'selected' : '' }}>Zone 2</option>
                                </select>
                                @error('zone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update Vendor</button>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>




    </div>
@endsection
