@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Advertisements</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.ads.index') }}">Advertisements</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Ad</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Ad</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Ad Title -->
                            <div class="mb-1">
                                <label for="title" class="form-label">Ad Title</label>
                                <input class="form-control" type="text" id="title" name="title" value="{{ old('title', $ad->title) }}" placeholder="Enter ad title" required>
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ad Description -->
                            <div class="mb-1">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Enter ad description" rows="4">{{ old('description', $ad->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ad Type -->
                            <div class="mb-1">
                                <label for="type" class="form-label">Ad Type</label>
                                <select class="form-select select2" id="type" name="type">
                                    <option value="product" {{ old('type', $ad->type) == 'product' ? 'selected' : '' }}>Product</option>
                                    <option value="category" {{ old('type', $ad->type) == 'category' ? 'selected' : '' }}>Category</option>
                                    <option value="custom" {{ old('type', $ad->type) == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                                @error('type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reference Selection (Dynamic) -->
                            <div class="mb-1" id="reference-container" style="{{ $ad->type == 'product' || $ad->type == 'category' ? '' : 'display: none;' }}">
                                <label for="reference_id" class="form-label">Select Reference</label>
                                <select class="form-select select2" id="reference_id" name="reference_id">
                                    <option value="">Select Reference</option>
                                    @foreach($ad->type == 'product' ? $products : $categories as $reference)
                                        <option value="{{ $reference->id }}" {{ old('reference_id', $ad->reference_id) == $reference->id ? 'selected' : '' }}>
                                            {{ $reference->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reference_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ad Zone (Visible if type is 'custom') -->
                            <div class="mb-1" id="zone-container" style="{{ $ad->type == 'zone' ? '' : 'display: none;' }}">
                                <label for="zone" class="form-label">Ad Zone</label>
                                <select class="form-select" id="zone" name="zone">
                                    <option value="zone1" {{ old('zone', $ad->zone) == 'zone1' ? 'selected' : '' }}>Zone 1</option>
                                    <option value="zone2" {{ old('zone', $ad->zone) == 'zone2' ? 'selected' : '' }}>Zone 2</option>
                                </select>
                                @error('zone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload (if needed) -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Ad Image</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @if($ad->image)
                                    <img src="{{ asset('storage/' . $ad->image) }}" alt="Ad Image" class="mt-2" width="150">
                                @endif
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Update Ad</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.select2').select2();

                // Show or hide the reference selection based on the type
                $('#type').change(function () {
                    let type = $(this).val();
                    let referenceContainer = $('#reference-container');
                    let zoneContainer = $('#zone-container');
                    let referenceSelect = $('#reference_id');

                    if (type === 'product' || type === 'category') {
                        referenceContainer.show();
                        zoneContainer.hide();
                    } else if (type === 'zone') {
                        referenceContainer.hide();
                        zoneContainer.show();
                    } else {
                        referenceContainer.hide();
                        zoneContainer.hide();
                    }
                });
            });
        </script>
    @endpush
@endsection
