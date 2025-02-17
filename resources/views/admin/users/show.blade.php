@extends('admin.layouts.app')

@section('title', __('messages.view_user'))

@section('content')
<div class="container">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('messages.users') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.view_user') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="row">
        <!-- User Details -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="150" class="img-thumbnail">
                    @else
                        <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Default Profile" width="100">
                    @endif
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-primary">{{ $user->roles->first()->name ?? __('messages.no_role') }}</span>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">{{ __('messages.orders') }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.order_id') }}</th>
                                <th>{{ __('messages.product_name') }}</th>
                                <th>{{ __('messages.categories') }}</th>
                                <th>{{ __('messages.amount') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ $order->category->name ?? __('messages.na') }}</td>
                                    <td>${{ number_format($order->amount, 2) }}</td>
                                    <td><span class="badge bg-success">{{ $order->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('messages.na') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
