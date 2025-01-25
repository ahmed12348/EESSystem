@extends('admin.layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb Navigation -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Users</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">View User</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <!-- Back Button -->
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="row">
        <!-- Section 1: User Overview Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <!-- User Profile Picture -->
                    @if($user->profile_picture)
                    
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="150" height="150" class="img-thumbnail">
                    @else
                        <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Default Profile" class="rounded-circle mb-3" width="100">
                    @endif
                   
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-primary">{{ $user->roles->first()->name ?? 'No Role' }}</span>

                    <hr>

                    <h6>Total Orders: <strong>{{ $orders->count() }}</strong></h6>
                    <h6>Joined: <strong>{{ $user->created_at->format('d M Y') }}</strong></h6>
                </div>
            </div>
        </div>

        <!-- Section 2: Filter Search -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Filter Orders</h5>
                    <form method="GET" action="{{ route('admin.users.show', $user->id) }}">
                        <div class="row">
                            <!-- Filter by Name -->
                            <div class="col-md-5">
                                <input type="text" name="search_name" class="form-control" placeholder="Search by Name" value="{{ request('search_name') }}">
                            </div>

                            <!-- Filter by Category -->
                            <div class="col-md-5">
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section 3: Orders Table -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">User Orders</h5>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->product_name }}</td>
                                    <td>{{ $order->category->name ?? 'N/A' }}</td>
                                    <td>${{ number_format($order->amount, 2) }}</td>
                                    <td><span class="badge bg-success">{{ $order->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
