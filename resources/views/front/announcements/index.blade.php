@extends('front.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Announcements</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Announcements</li>
                    </ul>
                </div>
                
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    
                    <!-- [Invoices Awaiting Payment] start -->
                    @foreach($announcements as $announcement)
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div class="avatar-text avatar-lg bg-gray-200" data-bs-toggle="tooltip" data-bs-original-title="Click and View">
                                            <a data-bs-toggle="modal" data-bs-id="{{$announcement->id}}" href="#taskinfo"><em class="icon ni ni-comments"></em>
                                                <i class="fa-solid fa-bullhorn"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <div class="fs-4 fw-bold text-dark"><span class="counter">{{ $announcement->title}}</span></div>
                                            <h3 class="fs-13 fw-semibold">{!! \Illuminate\Support\Str::words(nl2br($announcement->description), 30, '...') !!}</h3>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="">
                                        <i class="feather-more-vertical"></i>
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!-- [Invoices Awaiting Payment] end -->
                    
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>


@endsection


@section('script')

<script>        

    $(document).ready(function() { 
        
        $('[data-bs-id]').on('click',function(){
            let Task = $(this).attr('data-bs-id');

            $('#taskinfo .modal-dialog').addClass('modal-lg');
            $('.informationbox').html('<div class="text-center">  <div class="spinner-border" role="status">    <span class="visually-hidden">Loading...</span>  </div></div>');
            $('.informationbox').load("{{url('/user/announcement/load')}}/"+Task);
            $('.modal-title').text('Announcement');
        });

        // $('.update-status').change(function() {
        //     var leadId = $(this).attr('data-lead-id');
        //     var status = $(this).val();
            
        //     $.ajax({
        //         url: '/admin/category/' + leadId + '/update-status',
        //         type: 'POST',
        //         data: {
        //             _token: XCSRF_Token,
        //             status: status
        //         },
        //         success: function(response) {
        //             toastr.success(response.success_msg);
        //         },
        //         error: function(xhr) {
        //             console.log(xhr);
        //             alert('Error updating status. Please try again.');
        //         }
        //     });
        // });
    });
</script>

@endsection