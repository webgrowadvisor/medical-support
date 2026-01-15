@extends('admin.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Doctor Appointments</h5>
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

                            <div>
                                <form method="GET" action="" >
                                    <input type="search" name="search" class="from-control p-1 w-100" placeholder="Search Data"
                                    onkeyup="this.form.submit()"
                                    value="{{ request('search') }}" >
                                </form>
                            </div>

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
                                                <th>Patient Name</th>
                                                <th>Doctor Name</th>
                                                <th>Notes</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Appointments</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($appointments ?? [] as $key => $appointment)
                                            <tr>
                                                <td>
                                                {{ ($appointments->currentPage() - 1) * $appointments->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $appointment->user->name }} </br> {{ $appointment->user->mobile }}</td>
                                                <td>{{ $appointment->doctor->name }} </br> {{ $appointment->doctor->mobile }}</td>
                                                 <td>
                                                    {{ $appointment->notes ?? 'N/A' }}
                                                </td>
                                                <td>{{ $appointment->appointment_date }}</td>
                                                <td>{{ $appointment->appointment_time }}</td>
                                                @php
                                                    $appointmentDateTime = Carbon\Carbon::createFromFormat(
                                                        'Y-m-d H:i:s',
                                                        $appointment->appointment_date.' '.$appointment->appointment_time,
                                                        'Asia/Kolkata'
                                                    );
                                                    $isGone = $appointmentDateTime->lessThan(Carbon\Carbon::now('Asia/Kolkata'));
                                                @endphp
                                                <td>
                                                    @if($isGone)
                                                        <span class="badge bg-danger">Time Gone</span>
                                                    @else
                                                        <span class="badge bg-success">Upcoming</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ url('admin/appointments/'.$appointment->id.'/status') }}">
                                                        @csrf
                                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                                            <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>
                                                                Pending
                                                            </option>
                                                            <option value="approved" {{ $appointment->status == 'approved' ? 'selected' : '' }}>
                                                                Approved
                                                            </option>
                                                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>
                                                                Cancelled
                                                            </option>
                                                            <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>
                                                                Completed
                                                            </option>
                                                        </select>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No appointments found</td>
                                            </tr>
                                        @endforelse
                                         
                                                {{-- <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                                                <i class="feather feather-more-horizontal"></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('seller.category.edit', $category->id) }}">
                                                                        <i class="feather feather-edit-3 me-3"></i>
                                                                        <span>Edit</span>
                                                                    </a>
                                                                </li>   
                                                                <li>
                                                                    <a class="dropdown-item" role="button" href="#">
                                                                    <form method="POST" action="{{ route('seller.category.destroy', $category->id) }}">
                                                                        @csrf @method('DELETE')
                                                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                                                                    </form>
                                                                    </a>
                                                                </li>                                                              
                                                            </ul>
                                                        </div> 
                                                    </div>
                                                </td> --}}                                            
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