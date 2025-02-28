@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('messages.cart_settings') }}</div>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('messages.set_cart_expiration') }}</h5>

                        <form method="POST" action="{{ route('admin.settings.cart.update') }}">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <label for="cart_expiration_hours" class="form-label">{{ __('messages.cart_expiration_time') }}</label>
                                <input type="number" name="cart_expiration_hours" class="form-control" 
                                       value="{{ old('cart_expiration_hours', $expirationHours) }}" min="1">
                            </div>
                            
                            @can('settings-edit')
                                <button type="submit" class="btn btn-primary">{{ __('messages.update_expiration_time') }}</button>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection  
