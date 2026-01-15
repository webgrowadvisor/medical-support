@extends('seller.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Wallet transactions</h5>
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
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Balance</th>
                                                <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($transactions ?? [] as $key => $tx)
                                            <tr>
                                                <td>
                                                {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $tx->type == 'credit' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($tx->type) }}
                                                    </span>
                                                </td>
                                                <td>{{ priceicon() }} {{ $tx->amount }}</td>
                                                <td>{{ priceicon() }} {{ $tx->balance }}</td>
                                                <td>{{ $tx->reason }}</td>
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
                {{ $transactions->links('pagination::bootstrap-5') }}
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
    });

    $('.availability-status').change(function () {

        let checkbox = $(this);
        let availabilityId = checkbox.data('id');
        let status = checkbox.is(':checked') ? 'active' : 'inactive';

        $.ajax({
            url: '/user/pres/' + availabilityId + '/status',
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