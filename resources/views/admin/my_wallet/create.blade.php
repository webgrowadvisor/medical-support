@extends('admin.layout.main')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Wallet Activity</h5>
                    </div>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <form action="{{ route('admin.add.increment') }}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">

                            <div class="card-body general-info">
                                
                                <div class="row mb-4 align-items-center">
                                    <div class="col-lg-4">
                                        <label for="fullnameInput" class="fw-semibold">Wallet Type: <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="feather-user"></i></div>
                                            <select name="type" id="type" class="form-control" data-select2-selector="status">
                                              <option value="user" {{ old('type') === 'user' ? 'selected' : '' }}>User</option>
                                              <option value="doctor" {{ old('type') === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                            </select>
                                        </div>
                                        @error('type')
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>                                

                                <div class="row mb-4 align-items-center">
                                    <div class="col-lg-4">
                                        <label for="" class="fw-semibold">Find : <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="fa-brands fa-searchengin"></i></div>
                                            <input type="text" class="form-control" name="mobile" id="find" 
                                            value="{{ old('mobile') }}" placeholder="Mobile Number">
                                        </div>
                                        <span id="wallet-info"></span>
                                        @error('mobile') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-center">
                                    <div class="col-lg-4">
                                        <label for="fullnameInput" class="fw-semibold">Credit Amount: <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="fa-solid fa-user"></i></div>
                                            <input type="text" class="form-control" name="amount" 
                                            value="{{ old('amount') }}" placeholder="Credit Amount" >
                                        </div>
                                        @error('amount') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-center">
                                    <div class="col-lg-4">
                                        <label for="description" class="fw-semibold">Credit Reason: <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="fa-solid fa-file"></i></div>
                                            <textarea name="reason" class="form-control" placeholder="Credit Reason">{{ old('reason') }}</textarea>
                                        </div>
                                        @error('reason') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="row mb-4 align-items-center">
                                    <div class="col-lg-4">
                                        <label for="fullnameInput" class="fw-semibold">Service Type : <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="feather-compass"></i></div>
                                            <select name="service_type" class="form-control">
                                                <option value="online">Online</option>
                                                <option value="offline">Offline</option>
                                            </select>
                                        </div>
                                        @error('service_type') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                                

                                <div class="row mb-4 align-items-right">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="submit-fix btn btn-primary" >Credit Amount</button>
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
        <!-- [ Footer ] start -->

      
@endsection

@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

        <script>
        $('#content').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    // image upload via AJAX
                }
            }
        });
        </script>

        <script>
        $('#find, #type').on('blur change', function () {

            let mobile = $('#find').val();
            let type   = $('#type').val();

            if (mobile.length !== 10) return;

            $.ajax({
                url: "{{ route('wallet.find') }}",
                type: "POST",
                data: {
                    _token: XCSRF_Token,
                    mobile: mobile,
                    type: type
                },
                success: function (res) {
                    if (res.status) {

                        $('#wallet-info').html(`
                            <div class="alert alert-success">
                                <strong>Name:</strong> ${res.data.name}<br>
                                <strong>Mobile:</strong> ${res.data.mobile}<br>
                                <strong>Balance:</strong> ${res.data.balance}
                                <input type="hidden" name="wallet_id" value="${res.data.wallet_id}">
                            </div>
                        `);

                    } else {
                        $('#wallet-info').html(`
                            <div class="alert alert-danger">${res.message}</div>
                        `);
                    }
                }
            });
        });
        </script>

@endsection