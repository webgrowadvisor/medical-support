@extends('seller.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Doctor Availability</h5>
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

                            <a href="{{ route('doctor.add.availability') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Add Availability</span>
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
                                                <th>Day</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Interval</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                         @forelse($availabilities ?? [] as $key => $availability)
                                            <tr>
                                                <td>
                                                {{ ($availabilities->currentPage() - 1) * $availabilities->perPage() + $key + 1 }}
                                                </td>
                                                {{-- <td>{{ \Carbon\Carbon::parse($availability->available_date)->format('d M Y') }}</td> --}}
                                                <td>{{ $availability->available_date }}</td>
                                                <td>{{ $availability->start_time }}</td>
                                                <td>{{ $availability->end_time }}</td>
                                                <td>{{ $availability->interval }} min</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input availability-status"
                                                            type="checkbox"
                                                            data-id="{{ $availability->id }}"
                                                            {{ $availability->status == 'active' ? 'checked' : '' }}>
                                                    </div>
                                                </td>

                                                <td>
                                                    <a class="" href="{{ route('doctor.edit.availability', $availability->id) }}">
                                                        <i class="feather feather-edit-3 me-3"></i>
                                                         <span>Edit</span>
                                                    </a>
                                                                
                                                    <a class="" role="button" href="#">
                                                        <form method="POST" action="{{ route('doctor.destroy.availability', $availability->id) }}">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                                                        </form>
                                                    </a>
                                                </td>

                                            </tr>
                                         @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No availability added</td>
                                            </tr>
                                         @endforelse
                                         
                                        </tbody>
                                    </table>
                                </div>                                                              
                            </div>                            
                        </div>
                    </div>
                </div>
                {{ $availabilities->links('pagination::bootstrap-5') }}
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

        // $('.availability-status').change(function() {
        //     var leadId = $(this).attr('data-id');
        //     var status = $(this).val();
            
        //     $.ajax({
        //         // url: '/admin/category/' + leadId + '/update-status',
        //         url: '/doctor/availability/'+ leadId +'/status',
        //         type: 'POST',
        //         data: {
        //             _token: XCSRF_Token,
        //             status: status
        //         },
        //         success: function(response) {
        //             if (!response.success) {
        //                 toastr.success('Status update failed');
        //                 this.checked = !this.checked;
        //             }else{
        //                 toastr.error('Something went wrong');
        //                 this.checked = !this.checked;
        //             }
        //         },
        //         error: function(xhr) {
        //             console.log(xhr);
        //             alert('Error updating status. Please try again.');
        //         }
        //     });
        // });
    });

    $('.availability-status').change(function () {

        let checkbox = $(this);
        let availabilityId = checkbox.data('id');
        let status = checkbox.is(':checked') ? 'active' : 'inactive';

        $.ajax({
            url: '/doctor/availability/' + availabilityId + '/status',
            type: 'POST',
            data: {
                _token: XCSRF_Token,
                status: status
            },
            success: function (response) {
                if (response.success) {
                    toastr.success('Status updated successfully');
                } else {
                    toastr.error('Status update failed');
                    checkbox.prop('checked', !checkbox.is(':checked'));
                }
            },
            error: function () {
                toastr.error('Something went wrong');
                checkbox.prop('checked', !checkbox.is(':checked'));
            }
        });
    });
</script>

@endsection