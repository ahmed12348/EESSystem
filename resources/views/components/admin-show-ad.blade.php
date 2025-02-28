<div class="container">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ $title ?? __('messages.details') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ?? __('messages.details') }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ $backRoute }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">{{ $title ?? __('messages.details') }}</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <!-- Ad Image -->
                    <div class="mb-3 text-center">
                        <label class="form-label">{{ __('messages.image') }}</label>
                        <div>
                            <img src="{{ $image }}" alt="{{ $imageAlt }}" class="img-fluid rounded shadow border p-2" style="max-width: 120px;">
                        </div>
                    </div>

                    <table class="table table-striped">
                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <th>{{ __($key) }}</th>
                                    <td>{{ $value ?? __('messages.na') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Approve & Reject Buttons -->
                    @if($statusField && $statusValue)
                    <div class="mt-3">
                        @if($statusValue === 'pending')
                        <form action="{{ route($rejectRoute, $id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm me-2">
                                {{ __('messages.reject') }}
                            </button>
                        </form>
                        
                        <form action="{{ route($approveRoute, $id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                {{ __('messages.approve') }}
                            </button>
                        </form>
                        
                        @elseif($statusValue === 'approved')
                        <form action="{{ route($rejectRoute, $id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">
                                {{ __('messages.reject') }}
                            </button>
                        </form>
                        
                        @elseif($statusValue === 'rejected')
                        <form action="{{ route($approveRoute, $id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                {{ __('messages.approve') }}
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
