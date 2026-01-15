@extends('admin.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Audit Logging </h5>
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
                                                <th>Date</th>
                                                <th>Actor</th>
                                                <th>Event</th>
                                                <th>IP</th>
                                                <th>Meta</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($logs ?? [] as $key => $log)
                                            <tr>
                                                <td>
                                                {{ ($logs->currentPage() - 1) * $logs->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                                <td>{{ ucfirst($log->actor_type) }} ({{ $log->actor_id }})</td>
                                                <td>
                                                    <span class="badge bg-info">{{ strtoupper($log->event) }}</span>
                                                </td>
                                                <td>{{ $log->ip_address }}</td>
                                                <td>
                                                    @if($log->meta)
                                                        {{ json_encode($log->meta, JSON_PRETTY_PRINT) }}
                                                    @endif
                                                </td>
                                                @php
                                                    // $appointmentDateTime = Carbon\Carbon::createFromFormat(
                                                    //     'Y-m-d H:i:s',
                                                    //     $appointment->appointment_date.' '.$appointment->appointment_time,
                                                    //     'Asia/Kolkata'
                                                    // );
                                                    // $isGone = $appointmentDateTime->lessThan(Carbon\Carbon::now('Asia/Kolkata'));
                                                @endphp
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No appointments found</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>                                                              
                            </div>                            
                        </div>
                    </div>
                </div>
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
            <!-- [ Main Content ] end -->            
        </div>     
        
@endsection



@section('script')

<script>        

    $(document).ready(function() { 
        
        $('[data-bs-id]').on('click',function(){
            let Task = $(this).attr('data-bs-id');
            $('.informationbox').html('<div class="text-center">  <div class="spinner-border" role="status">    <span class="visually-hidden">Loading...</span>  </div></div>');
            $('.informationbox').load("{{url('leads-comment-send')}}/"+Task);
            $('.modal-title').text('Comments');
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