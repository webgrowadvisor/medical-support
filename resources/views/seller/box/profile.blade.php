            <div class="main-content">   
                <div class="row">
                    
                    <div class="col-xxl-12 col-md-12">
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-md-12">
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
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>