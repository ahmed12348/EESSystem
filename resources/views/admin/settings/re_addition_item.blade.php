@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Cart Expiration Settings</h2>
    
 

    <form action="{{ route('admin.cart.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="cart_expiry_time" class="form-label">Cart Expiry Time (in hours)</label>
            <input type="number" class="form-control" id="cart_expiry_time" name="cart_expiry_time" value="{{ old('cart_expiry_time', $settings->cart_expiry_time ?? 24) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Expired Cart Handling</label>
            <select class="form-select" name="cart_expiry_action" required>
                <option value="status_only" {{ old('cart_expiry_action', $settings->cart_expiry_action ?? '') == 'status_only' ? 'selected' : '' }}>Change Status Only</option>
                <option value="delete_cart" {{ old('cart_expiry_action', $settings->cart_expiry_action ?? '') == 'delete_cart' ? 'selected' : '' }}>Delete Cart and Items</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
