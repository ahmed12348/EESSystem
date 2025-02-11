@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cart Settings</div>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Set Cart Expiration Time</h5>
{{-- 
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif --}}

                        <form method="POST" action="{{ route('admin.settings.cart.update') }}">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <label for="cart_expiration_hours" class="form-label">Cart Expiration Time (Hours)</label>
                                <input type="number" name="cart_expiration_hours" class="form-control" 
                                       value="{{ old('cart_expiration_hours', $expirationHours) }}" min="1">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Expiration Time</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
