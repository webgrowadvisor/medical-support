@extends('seller.layout.main')

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
                                                <th>Notes</th>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Prescriptions</th>
                                                <th>Appointment</th>
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
                                                <td>
                                                    {{ $appointment->user->name }}
                                                    </br>
                                                    <select id="profile" class="form-control w-2" data-bs-id="{{ $appointment->user->id }}" style="width: 150px; padding: 4px;" >
                                                        <option value=""> More </option>
                                                        <option value="View Patient Dashboard" >View Patient Dashboard</option>
                                                        <option value="View Patient Available Medications" >View Patient Available Medications</option>
                                                        <option value="View Patient Passed Visits" >View Patient Passed Visits</option>
                                                        <option value="View Patient Files" >View Patient Files</option>
                                                        <option value="View Patient Profile" >View Patient Profile</option>
                                                    </select>
                                                </td>
                                                <td>{{ $appointment->notes ?? 'N/A' }}</td>
                                                <td>{{ $appointment->appointment_date }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_end)->format('h:i A') }}
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
                                                    @if ($appointment->status === 'approved' && !$isGone)
                                                        <a href="#" data-bs-toggle="tooltip" data-bs-original-title="Join Meeting" class="btn btn-primary">
                                                            <span>Join Meeting</span>
                                                        </a>
                                                    @elseif($appointment->status === 'completed')
                                                        <a href="{{ route('doctor.prescription.create', $appointment->id) }}" class="btn btn-primary">
                                                            ✍🏻 <span>Prescription</span>
                                                        </a>
                                                    @else
                                                        <a disable data-bs-toggle="tooltip" data-bs-original-title="Disable Join Meeting Buttion" class="btn btn-primary">
                                                            <span>Join Meeting</span>
                                                        </a>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if($isGone)
                                                        ⏰<span class="badge bg-danger">Time Gone</span>
                                                    @else
                                                        ⏰<span class="badge bg-success">Upcoming</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                
                                                <td> 
                                                    @if($appointment->status === 'pending' && !$isGone)
                                                    <form method="POST" action="{{ url('doctor/appointments/'.$appointment->id.'/status') }}">
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
                                                        </select>
                                                    </form>
                                                    @else
                                                    <span class="badge bg-dark">
                                                        🤝 {{ ucfirst($appointment->status) }}
                                                    </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No appointments found</td>
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