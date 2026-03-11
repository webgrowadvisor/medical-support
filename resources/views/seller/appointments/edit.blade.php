@extends('seller.layout.main')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit Appointment #{{$appointments->id}}</h5>
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
            <form action="{{ route('doctor.appointments.update', $appointments->id) }}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">

                            <div class="card-body general-info">
                                
                                <div class="row mb-4">
                                    <div class="col-lg-6">
                                        <label for="notes" class="fw-semibold">Reasons For Visit: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <textarea name="notes" id="content" class="form-control" placeholder="Reasons For Visit">{{ old('notes', $appointments->notes ?? '') }}</textarea>
                                        </div>
                                        @error('notes') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                        
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12 mb-3">
                                            <label for="fullnameInput" class="fw-semibold">Patient: </label>
                                            <div class="input-group">
                                                <i class="fa-solid fa-user"></i><span>&nbsp;</span>
                                                {{ $appointments->user->name }} - {{ $appointments->user->email }}
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="fullnameInput" class="fw-semibold">Type: <span class="text-danger"></span></label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="fa-solid fa-cart-shopping"></i></div>
                                                <select name="type" class="form-control" required>
                                                    <option value="">Select type</option>
                                                    <option {{$appointments->type == 'Initial Visit' ? 'selected' : ''}} value="Initial Visit">Initial Visit</option>
                                                    <option {{$appointments->type == 'Follow-Up Visit' ? 'selected' : ''}} value="Follow-Up Visit" >Follow-Up Visit</option>
                                                </select>
                                            </div>
                                            @error('type') 
                                                <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <label for="fullnameInput" class="fw-semibold">Visit Datetime: </label>
                                            <div class="input-group">
                                                <i class="fa-solid fa-calendar"></i> <span>&nbsp;</span>
                                                {{ \Carbon\Carbon::parse($appointments->appointment_date)->format('d-m-Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($appointments->appointment_time)->format('h:i a') }}
                                                to
                                                {{ \Carbon\Carbon::parse($appointments->appointment_end)->format('h:i a') }}
                                                (CST)
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <label for="fullnameInput" class="fw-semibold">Patient Phone: </label>
                                            <div class="input-group">
                                                <i class="fa-solid fa-phone"></i><span>&nbsp;</span>
                                                {{ $appointments->user->mobile }}
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-12 mt-3">
                                            <label for="fullnameInput" class="fw-semibold">Status: 
                                                <span class="text-danger"></span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <select name="status" class="form-control" >
                                                    <option value="pending" {{$appointments->status == 'pending' ? 'selected' : ''}}>
                                                        Pending
                                                    </option>
                                                    <option value="approved" {{$appointments->status == 'approved' ? 'selected' : ''}}>
                                                        Approved
                                                    </option>
                                                    <option value="cancelled" {{$appointments->status == 'cancelled' ? 'selected' : ''}}>
                                                        Cancelled
                                                    </option>
                                                    <option value="completed" {{$appointments->status == 'completed' ? 'selected' : ''}}>
                                                        Completed
                                                    </option>
                                                    <option value="patient no show" {{$appointments->status == 'patient no show' ? 'selected' : ''}}>
                                                        Patient no show
                                                    </option>
                                                </select>
                                            </div>
                                            @error('status') 
                                                <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if(!empty($appointments->other))
                                        <div class="col-lg-12 mt-3 text-align-center">
                                            <div class="input-group">
                                                <a href="{{ asset('storage/'.$appointments->other) }}" class="btn btn-info w-50" target="_blank"> <i class="fa-solid fa-file"></i><span>&nbsp;</span> View file</a>
                                            </div>
                                        </div>
                                        @endif

                                    </div>

                                </div>                                


                                <div class="row mb-4 align-items-center">
                                    <div class="col-lg-6">
                                        <label for="provider_subjective" class="fw-semibold">Provider Notes for Patient: <span class="text-danger"></span></label>
                                        <div class="input-group">
                                            <textarea name="provider_subjective" id="content_1" class="form-control" placeholder="Provider Notes Subjective">{{ old('provider_subjective', $appointments->provider_subjective ?? '') }}</textarea>
                                        </div>
                                        @error('provider_subjective') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="provider_objective" class="fw-semibold">Provider Notes for Admin: <span class="text-danger"></span></label>
                                        <div class="input-group">
                                            <textarea name="provider_objective" id="content_2" class="form-control" placeholder="Provider Notes Objective">{{ old('provider_objective', $appointments->provider_objective ?? '') }}</textarea>
                                        </div>
                                        @error('provider_objective') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="row mb-4 align-items-center">
                                    <div class="col-lg-6">
                                        <label for="provider_assessment" class="fw-semibold">Provider Notes Assessment: <span class="text-danger"></span></label>
                                        <div class="input-group">
                                            <textarea name="provider_assessment" id="content_3" class="form-control" placeholder="Provider Notes Assessment">{{ old('provider_assessment', $appointments->provider_assessment ?? '') }}</textarea>
                                        </div>
                                        @error('provider_assessment') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror                                        
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="provider_plan" class="fw-semibold">Provider Notes Plan: <span class="text-danger"></span></label>
                                        <div class="input-group">
                                            <textarea name="provider_plan" id="content_4" class="form-control" placeholder="Provider Notes Plan">{{ old('provider_plan', $appointments->provider_plan ?? '') }}</textarea>
                                        </div>
                                        @error('provider_plan') 
                                            <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="row mb-4 align-items-right">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="submit-fix btn btn-primary" >Update Appointment </button>
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

        $('#content_1').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    // image upload via AJAX
                }
            }
        });

        $('#content_2').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    // image upload via AJAX
                }
            }
        });

        $('#content_3').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    // image upload via AJAX
                }
            }
        });

        $('#content_4').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    // image upload via AJAX
                }
            }
        });
        </script>

@endsection