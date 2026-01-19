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
                                                <th>Comment</th>
                                                <th>Reviews</th>
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
                                                    {{ $review->appointment->appointment_time ?? 'N/A' }}
                                                </td>
                                                <td>{{ $review->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <strong>{{ $review->user->name ?? '-' }}</strong><br/>
                                                    <span>Rating: {{ $review->rating }}⭐</span>
                                                    <p>{{ $review->review }}</p>
                                                </td>   
                                                
                                                <td>
                                                    <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-original-title="Review Rection">
                                                        <a data-bs-toggle="modal" data-review-id="{{$review->id}}" href="#taskinfo">
                                                            <em class="icon ni ni-comments"></em>Send Review Rection
                                                        </a>
                                                    </span>
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
        
        $('[data-review-id]').on('click', function(){
            let Task = $(this).attr('data-review-id');
            $('.informationbox').html('<div class="text-center">  <div class="spinner-border" role="status">    <span class="visually-hidden">Loading...</span>  </div></div>');
            $('.informationbox').load("{{url('doctor/review/load')}}/"+Task);
            $('.modal-title').text('Review Rection');
        });

        $('.update-status').change(function() {
            var leadId = $(this).attr('data-lead-id');
            var status = $(this).val();
            
            $.ajax({
                url: '/admin/category/' + leadId + '/update-status',
                type: 'POST',
                data: {
                    _token: XCSRF_Token,
                    status: status
                },
                success: function(response) {
                    toastr.success(response.success_msg);
                },
                error: function(xhr) {
                    console.log(xhr);
                    alert('Error updating status. Please try again.');
                }
            });
        });
    });
</script>

@endsection