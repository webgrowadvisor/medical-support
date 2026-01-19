@extends('front.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Appointment List</h5>
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
                                <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside"> <i class="feather-filter"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{ url()->current() }}" class="dropdown-item">
                                        <span class="wd-7 ht-7 bg-danger rounded-circle d-inline-block me-3"></span>
                                        <span>Reset</span>
                                    </a>                                                                    
                                </div>
                            </div>  

                            {{-- <a href="{{ route('user.appointments') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>User Appointments</span>
                            </a> --}}

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
                                                <th>Doctor Name</th>
                                                <th>Doctor Specialization</th>
                                                <th>Notes</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Appointment</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($appointments as $key => $appointment)
                                            <tr>
                                                <td>
                                                    {{ ($appointments->currentPage() - 1) * $appointments->perPage() + $key + 1 }}
                                                </td>

                                                <td>
                                                    {{ $appointment->doctor->name ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $appointment->doctor->specialization ?? 'N/A' }}
                                                </td>

                                                <td>
                                                    {{ $appointment->notes ?? 'N/A' }}
                                                </td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                                </td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </td>

                                                @php
                                                    $appointmentDateTime = Carbon\Carbon::createFromFormat(
                                                        'Y-m-d H:i:s',
                                                        $appointment->appointment_date.' '.$appointment->appointment_time,
                                                        'Asia/Kolkata'
                                                    );
                                                    $isGone = $appointmentDateTime->lessThan(Carbon\Carbon::now('Asia/Kolkata'));
                                                @endphp
                                                <td>
                                                    @if($appointment->status == 'completed')
                                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-original-title="Send Review">
                                                            <a data-bs-toggle="modal" data-review-id="{{$appointment->id}}" href="#taskinfo"><em class="icon ni ni-comments"></em>
                                                                Send Review
                                                            </a>
                                                        </span>
                                                    @else
                                                        @if($isGone)
                                                            <span class="badge bg-danger">Time Gone</span>
                                                        @else
                                                            <span class="badge bg-success">Upcoming</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($appointment->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($appointment->status == 'completed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @elseif($appointment->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    No Appointments Found
                                                </td>
                                            </tr>
                                        @endforelse
                                                
                                        </tbody>
                                    </table>
                                </div>                                                              
                            </div>                            
                        </div>
                    </div>
                </div>
                {{ $appointments->links('pagination::bootstrap-5') }}
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
            $('.informationbox').load("{{url('user/review/load')}}/"+Task);
            $('.modal-title').text('Review Send');
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