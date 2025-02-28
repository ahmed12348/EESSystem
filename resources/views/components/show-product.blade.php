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
                    <!-- Product Image -->
                    @if(isset($image))
                        <div class="text-center mb-3">
                            <label class="form-label fw-bold">{{ $imageAlt ?? __('messages.image') }}</label>
                            <div>
                                <img src="{{ $image }}" class="img-fluid border rounded shadow" style="max-width: 150px;">
                            </div>
                        </div>
                    @endif

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
                </div>
            </div>
        </div>
    </div>
</div>
