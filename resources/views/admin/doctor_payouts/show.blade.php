@extends('admin.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Doctor Payouts</h5>
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
                                                <th>Doctor</th>
                                                <th>Mobile No.</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @forelse($payouts ?? [] as $key => $payout)
                                            <tr>
                                                <td>
                                                {{ ($payouts->currentPage() - 1) * $payouts->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $payout->doctor->name }}</td>
                                                <td>{{ $payout->doctor->mobile }}</td>
                                                <td>{{ priceicon() }}{{ $payout->amount }}</td>
                                                <td>
                                                    <span class="badge bg-{{ 
                                                        $payout->status == 'approved' ? 'success' :
                                                        ($payout->status == 'rejected' ? 'danger' : 'warning')
                                                    }}">
                                                        {{ ucfirst($payout->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($payout->status == 'pending')
                                                    <div class="d-flex gap-2">
                                                        <form method="POST" action="{{ route('admin.doctor.payout.approve', $payout->id) }}" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm">Approve</button>
                                                        </form>
                                                        
                                                        <form method="POST" action="{{ route('admin.doctor.payout.reject', $payout->id) }}" class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-danger btn-sm">Reject</button>
                                                        </form>
                                                    </div>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No appointments found</td>
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
                {{ $payouts->links('pagination::bootstrap-5') }}
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