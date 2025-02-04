@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cart Expiration</div>
            <div class="ms-auto">
                <a href="{{ route('admin.carts.index') }}" class="btn btn-secondary">Back to Carts</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Cart Expiration</h6>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.carts.update', $cart->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expiration Time</label>
                                <input type="datetime-local" name="expires_at" class="form-control" id="expires_at" value="{{ old('expires_at', $cart->expires_at ? \Carbon\Carbon::parse($cart->expires_at)->format('Y-m-d\TH:i') : '') }}" required>
                                @error('expires_at')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Expiration</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
