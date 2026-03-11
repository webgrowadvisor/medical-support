@extends('admin.layout.main')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
.form-control {
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid #e2e6ea;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

.select2-container--default .select2-selection--multiple {
    border-radius: 12px !important;
    min-height: 48px;
    padding: 6px;
    border: 1px solid #e2e6ea;
}

.select2-selection__choice {
    background: linear-gradient(135deg, #4f46e5, #6366f1) !important;
    border: none !important;
    color: #fff !important;
    border-radius: 20px !important;
    padding: 6px 12px !important;
    font-size: 13px;
}
</style>
@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Medication Order Questionnaire</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Create</li>
                    </ul>
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
            <form action="{{ route('user.medicine.buy') }}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">

                            <div class="card-body general-info">

                                <div class="row mb-4 align-items-center">
                                    <div class="input-group">
                                        Please enter your information
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="fullnameInput" class="fw-semibold">Name: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" 
                                            value="{{ old('name', $user->name ?? '') }}" id="fullnameInput" placeholder="Full Name" readonly>
                                    </div>
                                    <div class="col-lg-6">
                                            <label for="" class="fw-semibold">Date of Birth: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="dob" 
                                            value="{{ old('dob', $user->dob ?? '') }}" id="" placeholder="Date of Birth" readonly>
                                        @error('name') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                            <label for="" class="fw-semibold">Phone : <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="mobile" 
                                            value="{{ old('mobile', $user->mobile ?? '') }}" id="" placeholder="Phone">
                                        @error('mobile') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                            <label for="" class="fw-semibold">Email : <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="dob" 
                                            value="{{ old('email', $user->email ?? '') }}" id="" placeholder="email">
                                        @error('email') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-center">
                                    <div class="input-group">
                                        Please update your shipping information
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="address" class="fw-semibold">Shipping Address: <span class="text-danger">*</span></label>
                                        <div class="input-group">                                            
                                            <div class="input-group-text"><i class="fa-solid fa-file"></i></div>
                                                <textarea name="shipping_address" id="" class="form-control" placeholder="Shipping Address ....">{{ old('shipping_address', $plan->shipping_address ?? '') }}</textarea>
                                        </div>
                                        @error('shipping_address') 
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

                                <div class="row mb-4 align-items-start">
                                    <div class="col-lg-4">
                                        <label class="fw-semibold fs-6">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                            Medicines
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>

                                    <div class="col-lg-12">
                                        <select name="medicines[]" 
                                                class="form-control select2"
                                                multiple required>

                                            @foreach($medicines as $medicine)
                                                <option value="{{ $medicine->id }}">
                                                    {{ $medicine->name }} — ₹ {{ $medicine->price }}
                                                </option>
                                            @endforeach

                                        </select>

                                        <small class="text-muted">
                                            Select one or more medicines
                                        </small>

                                        @error('medicines') 
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-right">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-primary w-100" >Order Place</button>
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Medicines",
        allowClear: true,
        width: '100%'
    });
});
</script>

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

@endsection