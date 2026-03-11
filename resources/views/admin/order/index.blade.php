@extends('admin.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">My Orders</h5>
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
                                    <input type="search" name="search" class="from-control p-1 w-100" placeholder="Search by Order no."
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
                                                <th>View</th>
                                                <th>Order No</th>
                                                <th>Medicine</th>
                                                <th>Date Time</th>
                                                <th>Order Total</th>
                                                <th>Status</th>
                                                <th>Status Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $key => $order)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.orderView', $order->order_number)}}" class="" target="_blank">
                                                            <i class="fa-solid fa-file"></i> <span>View</span>
                                                        </a>
                                                    </td>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>
                                                    @foreach($order->items as $count => $item)
                                                        {{ $count+1 }}. {{ $item->medicine->name }}</br>
                                                    @endforeach
                                                    </td>
                                                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>{{ priceicon() }} {{ $order->total_amount }}</td>
                                                    <td>{{ ucfirst($order->status) }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                                                            @csrf
                                                            <select name="status" onchange="this.form.submit()" class="form-control">
                                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        
                                        </tbody>
                                    </table>
                                </div> 
                                @if(count($orders) == 0) 
                                    <div class="d-flex justify-content-center mt-4">
                                    <h3>{{ 'Data Not Found' }}</h3>
                                    </div>
                                @endif

                            </div>                            
                        </div>
                    </div>
                </div>
                {{ $orders->links('pagination::bootstrap-5') }}
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