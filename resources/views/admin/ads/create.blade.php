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
                        <li class="breadcrumb-item active" aria-current="page">Add New Ad</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <!-- Ad Creation Form -->
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Create New Ad</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Ad Title -->
                            <div class="mb-1">
                                <label for="title" class="form-label">Ad Title</label>
                                <input class="form-control" type="text" id="title" name="title"
                                    placeholder="Enter ad title" required>
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ad Description -->
                            <div class="mb-1">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="Enter ad description" rows="4"></textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                         <!-- Vendor ID Dropdown -->
                        <div class="mb-1">
                        <label for="vendor_id" class="form-label">Select Vendor</label>
                            <select class="form-select select2" id="vendor_id" name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                            {{-- <!-- Ad Type -->
                            <div class="mb-1">
                                <label for="type" class="form-label">Ad Type</label>
                                <select class="form-select select2" id="type" name="type">
                                    <option value="product">Product</option>
                                    <option value="category">Category</option>
                                    <option value="zone">Zone</option>
                                </select>
                                @error('type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reference Selection (Dynamic) -->
                            <div class="mb-1" id="reference-container" style="display: none;">
                                <label for="reference_id" class="form-label">Select Reference</label>
                                <select class="form-select select2" id="reference_id" name="reference_id">
                                    <!-- Dynamic references will be populated here -->
                                </select>
                                @error('reference_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            {{-- <div class="mb-1" id="zone-container" style="display: none;">
                                <label for="zone" class="form-label">Ad Zone</label>
                                <select class="form-select" id="zone" name="zone">
                                    <option value="zone1">Zone 1</option>
                                    <option value="zone2">Zone 2</option>
                                </select>
                                @error('zone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Ad Image</label>
                                <input class="form-control" type="file" id="image" name="image">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Create Ad</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
$(document).ready(function () {
    $('.select2').select2();

    $('#type').change(function () {
        let type = $(this).val();
        let referenceContainer = $('#reference-container');
        let referenceSelect = $('#reference_id');
        let zoneContainer = $('#zone-container');
        let zoneField = $('#zone'); // Zone input field

        console.log("Selected type:", type);

        if (type === 'product' || type === 'category') {
            referenceContainer.show();
            zoneContainer.hide();
            zoneField.prop('disabled', true); // Disable zone field for product/category
            referenceSelect.html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('admin.ads.getReferences') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { type: type },
                success: function (data) {
                    console.log("AJAX success:", data);

                    let options = '<option value="">Select Reference</option>';
                    $.each(data, function (key, value) {
                        // Here you decide what to show as the reference options
                        options += `<option value="${value.id}">${value.name}</option>`;
                    });

                    referenceSelect.html(options);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", xhr.responseText);
                }
            });
        } else if (type === 'custom' || type === 'zone') {
            referenceContainer.hide();
            zoneContainer.show();
            zoneField.prop('disabled', false); // Enable zone field for custom/zone
        } else {
            referenceContainer.hide();
            zoneContainer.hide();
            zoneField.prop('disabled', true);
        }
    });
});


        </script>
    @endpush
@endsection
