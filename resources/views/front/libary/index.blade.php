@extends('front.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Library</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Library</li>
                    </ul>
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

                            <a href="{{ route('user.library') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>All Books</span>
                            </a>
                            <a href="{{ route('user.library', ['type' => 'paid']) }}" class="btn btn-danger">
                                <i class="feather-plus me-2"></i>
                                <span>Paid Books</span>
                            </a>
                            <a href="{{ route('user.library', ['type' => 'free']) }}" class="btn btn-success">
                                <i class="feather-plus me-2"></i>
                                <span>Free Books</span>
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
                    
                    <!-- [Invoices Awaiting Payment] start -->
                    @foreach($medicines as $medicine)
                    <div class="col-xxl-4 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div class="d-flex gap-4 align-items-center">
                                        <div>
                                            <div>{!! variantImage($medicine->cover_image, 100, 100) !!}</div>
                                            <h5 data-bs-toggle="modal" data-bs-id="{{$medicine->id}}" href="#taskinfo" >{{$medicine->title}}</h5>
                                            <p class="fs-13 fw-semibold">{!! \Illuminate\Support\Str::words(nl2br($medicine->short_description), 50, '...') !!}</p>
                                            @if($medicine->type == 'free')
                                                <a href="{{ asset('storage/'.$medicine->file_url) }}" download>
                                                    <button class="btn btn-sm btn-info w-100">Free Download</button>
                                                </a>
                                            @endif
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
            $('.informationbox').load("{{url('/patient/library/load')}}/"+Task);
            $('.modal-title').text('Library');
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