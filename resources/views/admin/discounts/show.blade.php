@extends('admin.layouts.app')

@section('title', 'Discount Details')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Discount Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Discounts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Discount #{{ $discount->id }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Discount Information</h5>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Discount ID:</strong> #{{ $discount->id }}</p>
                    <p><strong>Discount Percentage:</strong> {{ $discount->discount_value }}%</p>
                    <p><strong>Start Date:</strong> {{ $discount->start_date }}</p>
                    <p><strong>End Date:</strong> {{ $discount->end_date }}</p>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Products with This Discount</h5>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Original Price</th>
                            <th>Discount</th>
                            <th>Final Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{dd($discount->product)}}
                        @foreach ($discount as $pro)
                            <tr>
                                <td>#{{ $pro->id }}</td>
                                <td>{{ $pro->name }}</td>
                                <td>${{ number_format($pro->price, 2) }}</td>
                                <td>{{ $discount->discount_value }}%</td>
                                <td>
                                    ${{ number_format($pro->price - ($pro->price * ($discount->discount_value / 100)), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary mt-3">Back to Discounts</a>
        </div>
    </div>
@endsection
