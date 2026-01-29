@extends('seller.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Review</h5>
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
                        
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">

                            <div class="dropdown">
                                <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                                    <i class="feather-filter"></i>
                                </a>                                
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ url()->current() }}" class="dropdown-item">
                                        <span class="wd-7 ht-7 bg-danger rounded-circle d-inline-block me-3"></span>
                                        <span>Reset</span>
                                    </a>                                                                    
                                </div>
                            </div>  

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
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="leadList">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Appointment</th>
                                                <th>User</th>
                                                <th>Rating & Feedback</th>
                                                <th>Reviews Rection</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         @foreach($reviews as $key => $review)
                                            <tr class="single-item">
                                                <td>
                                                {{ ($reviews->currentPage() - 1) * $reviews->perPage() + $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $review->appointment->notes ?? 'N/A' }}<br/>
                                                    {{ $review->appointment->appointment_date ?? 'N/A' }} 
                                                    {{ $review->appointment->appointment_time ?? 'N/A' }}- 
                                                    {{ $review->appointment->appointment_end ?? 'N/A' }}

                                                    </br>
                                                    <select id="profile" class="form-control" data-bs-id="{{ $review->user->id }}" style="width: 170px; padding: 4px;" >
                                                        <option value=""> More </option>
                                                        <option value="View Patient Dashboard" >View Patient Dashboard</option>
                                                        <option value="View Patient Available Medications" >View Patient Available Medications</option>
                                                        <option value="View Patient Passed Visits" >View Patient Passed Visits</option>
                                                        <option value="View Patient Files" >View Patient Files</option>
                                                        <option value="View Patient Profile" >View Patient Profile</option>
                                                    </select>
                                                </td>
                                                <td>{{ $review->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span>Rating: {{ $review->rating }}⭐</span>
                                                    <p>{{ $review->review }}</p>
                                                </td>   
                                                
                                                <td>
                                                    @if ($review->review_rection)
                                                        {{ $review->review_rection }}
                                                    @else
                                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-original-title="Review Rection">
                                                            <a data-bs-toggle="modal" data-review-id="{{$review->id}}" href="#taskinfo">
                                                                <em class="icon ni ni-comments"></em>Send Review Rection
                                                            </a>
                                                        </span>                                                        
                                                    @endif                                                    
                                                </td>
                                            </tr>
                                         @endforeach
                                        </tbody>
                                    </table>
                                </div> 
                                @if(count($reviews) == 0) 
                                    <div class="d-flex justify-content-center mt-4">
                                    <h3>{{ 'Data Not Found' }}</h3>
                                    </div>
                                @endif                               
                            </div>                            
                        </div>
                    </div>
                </div>
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
            <!-- [ Main Content ] end -->            
        </div>     
        
@endsection



@section('script')

<script>

    $(document).ready(function() { 
        
        $('[data-bs-id]').on('change',function(){
            let Task = $(this).attr('data-bs-id');
            let val = $(this).val();

            $('#taskinfo').modal('show');

            $('.informationbox').html('<div class="text-center">  <div class="spinner-border" role="status">    <span class="visually-hidden">Loading...</span>  </div></div>');

            if(val === 'View Patient Dashboard'){                
                $('.informationbox').load("{{url('/doctor/dashboard/load')}}/"+Task);
            }else if(val === 'View Patient Available Medications'){
                $('#taskinfo .modal-dialog').addClass('modal-lg');
                $('.informationbox').load("{{url('/doctor/medications/load')}}/"+Task);
            }else if(val === 'View Patient Passed Visits'){
                $('#taskinfo .modal-dialog').addClass('modal-lg');
                $('.informationbox').load("{{url('/doctor/passed/load')}}/"+Task);
            }
            else if(val === 'View Patient Files'){
                $('#taskinfo .modal-dialog').addClass('modal-lg');
                $('.informationbox').load("{{url('/doctor/patientfiles/load')}}/"+Task);
            }
            else if(val === 'View Patient Profile'){
                $('.informationbox').load("{{url('/doctor/profile/load')}}/"+Task);
            }

            $('.modal-title').text(val);
        });

        
    });
</script>

@endsection