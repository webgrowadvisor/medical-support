@extends('admin.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                    </ul>
                </div>
                
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">

                    <!-- [Invoices Awaiting Payment] start -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            🤒
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $data['total_users'] }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Patients</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="fs-12 fw-medium text-muted text-truncate-1-line">Total Patients</a>
                                        <div class="w-100 text-end">
                                            <span class="fs-12 text-dark">{{ $data['total_users'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2 ht-3">
                                        @php
                                            $total = $data['total_users'] ?? 0;
                                            $cancel = $data['active_users'] ?? 0;

                                            $percentage = $total > 0 ? round(($cancel / $total) * $total) : 0;
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {!! $percentage !!}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Invoices Awaiting Payment] end -->

                    <!-- [Converted Leads] start -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            👩🏻‍⚕️
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $data['total_sellers'] }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Doctors</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="fs-12 fw-medium text-muted text-truncate-1-line">Total Doctors </a>
                                        <div class="w-100 text-end">
                                            <span class="fs-12 text-dark">{{ $data['total_sellers'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2 ht-3">
                                        @php
                                            $total = $data['total_sellers'] ?? 0;
                                            $cancel = $data['active_sellers'] ?? 0;

                                            $percentage = $total > 0 ? round(($cancel / $total) * $total) : 0;
                                        @endphp
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{$percentage}}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Converted Leads] end -->
                    
                    <!-- [Conversion Rate] start -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            🗓️📋
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $data['total_appoiniment'] }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Appointment</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="fs-12 fw-medium text-muted text-truncate-1-line"> Total Appointment </a>
                                        <div class="w-100 text-end">
                                            <span class="fs-12 text-dark">{{ $data['total_appoiniment'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2 ht-3">
                                        @php
                                            $total = $data['total_appoiniment'] ?? 0;
                                            $cancel = $data['cancel_appoiniment'] ?? 0;

                                            $percentage = $total > 0 ? round(($cancel / $total) * $total) : 0;
                                        @endphp
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{$percentage}}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Conversion Rate] end -->

                    <!-- [Conversion Rate] start -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            💰
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $data['total_payout'] }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Payout</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="fs-12 fw-medium text-muted text-truncate-1-line"> Total Payout </a>
                                        <div class="w-100 text-end">
                                            <span class="fs-12 text-dark">{{ $data['total_payout'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2 ht-3">
                                        @php
                                            $total = $data['total_payout'] ?? 0;
                                            $cancel = $data['cancel_payout'] ?? 0;

                                            $percentage = $total > 0 ? round(($cancel / $total) * $total) : 0;
                                        @endphp
                                        <div class="progress-bar bg-dark" role="progressbar" style="width: {{$percentage}}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Conversion Rate] end -->

                    <!-- [Conversion Rate] start -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            😷
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $data['total_prescription'] }}</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Prescription</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="fs-12 fw-medium text-muted text-truncate-1-line"> Total Prescription </a>
                                        <div class="w-100 text-end">
                                            <span class="fs-12 text-dark">{{ $data['total_prescription'] }}</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2 ht-3">
                                        @php
                                            $total = $data['total_prescription'] ?? 0;
                                            $cancel = $data['cancel_prescription'] ?? 0;

                                            $percentage = $total > 0 ? round(($cancel / $total) * $total) : 0;
                                        @endphp
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{$percentage}}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Conversion Rate] end -->

                    <!-- [Conversion Rate] start -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200">
                                            {{-- <i class="feather-activity"></i> --}}✉
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">00</span></div>
                                            <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Enquiry</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="fs-12 fw-medium text-muted text-truncate-1-line"> Total Enquiry </a>
                                        <div class="w-100 text-end">
                                            <span class="fs-12 text-dark">0</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2 ht-3">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Conversion Rate] end -->
                    
                    
                    <!-- [Latest Leads] start -->
                    <div class="col-xxl-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Latest Appointment</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">
                                        <div data-bs-toggle="tooltip" title="Delete">
                                            <a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger"
                                                data-bs-toggle="remove"> </a>
                                        </div>
                                        <div data-bs-toggle="tooltip" title="Refresh">
                                            <a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning"
                                                data-bs-toggle="refresh"> </a>
                                        </div>
                                        <div data-bs-toggle="tooltip" title="Maximize/Minimize">
                                            <a href="javascript:void(0);" class="avatar-text avatar-xs bg-success"
                                                data-bs-toggle="expand"> </a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="avatar-text avatar-sm"
                                            data-bs-toggle="dropdown" data-bs-offset="25, 25">
                                            <div data-bs-toggle="tooltip" title="Options">
                                                <i class="feather-more-vertical"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body custom-card-action p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr class="border-b">
                                                <th scope="row">Pasent</th>
                                                <th>Doctor</th>
                                                <th>Notes</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Appointment</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         @foreach($data['appointments'] as $key => $appointment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="avatar-image">
                                                            {{ ($data['appointments']->currentPage() - 1) * $data['appointments']->perPage() + $key + 1 }}
                                                        </div>
                                                        <a href="javascript:void(0);">
                                                            <span class="d-block">{{ $appointment->user->name }} </span>
                                                            <span class="fs-12 d-block fw-normal text-muted">
                                                                ({{ $appointment->user->mobile }})
                                                            </span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <a href="javascript:void(0);">
                                                            <span class="d-block">{{ $appointment->doctor->name }} </span>
                                                            <span class="fs-12 d-block fw-normal text-muted">
                                                                ({{ $appointment->doctor->mobile }})
                                                            </span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>{{ $appointment->notes ?? 'N/A' }}</td>
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
                                                        ⏰<span class="badge bg-danger">Gone</span>
                                                    @else
                                                        ⏰<span class="badge bg-success">Upcoming</span>
                                                    @endif
                                                </td>
                                                                                                
                                                <td>
                                                    <span class="badge bg-soft-success text-success"> 🤝 {{ ucfirst($appointment->status) }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="javascript:void(0);"><i class="feather-more-vertical"></i></a>
                                                </td>
                                            </tr>
                                         @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                {{ $data['appointments']->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    <!-- [Latest Leads] end -->
                    
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>


@endsection