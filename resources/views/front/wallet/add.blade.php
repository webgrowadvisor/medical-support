@extends('front.layout.main')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">💰 Wallet</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Create</li>
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
                            
                            <a href="javascript:void(0);" class="btn btn-primary ">
                                <span>💰 Wallet</span>
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

                            <div class="card-body general-info">
                                
                                <h4>My Wallet</h4>

                                <h2 class="text-success">
                                    ₹ {{ number_format($wallet->balance, 2) }}
                                </h2>

                                <form action="{{ route('user.wallet.add') }}" method="POST">
                                    @csrf
                                    <div class="input-group mt-3">
                                        <input type="number" name="amount" class="form-control" placeholder="Enter amount">
                                        <button class="btn btn-primary">Add Money</button>
                                    </div>
                                    @error('amount') 
                                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </form>

                                <a href="{{ route('user.wallet.transactions') }}" class="btn btn-link mt-3">
                                    View Transactions
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
        <!-- [ Footer ] start -->
      
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

        <script>
        $('#content').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    // image upload via AJAX
                }
            }
        });
        </script>

<script>
    
    $(document).on('click', '.add-medicine', function () {

        let html = `
        <div class="row mb-4 align-items-center medicine-row">
            <div class="col-lg-4"></div>

            <div class="col-lg-8">
                <div class="input-group">
                    <div class="input-group-text">
                        <i class="feather-calendar"></i>
                    </div>

                    <input type="text"
                        class="form-control"
                        name="medicines[]"
                        placeholder="Paracetamol 500mg - 2 times">

                    <button type="button" class="btn btn-danger remove-medicine">
                        -
                    </button>
                </div>
            </div>
        </div>`;

        $('#medicine-wrapper').append(html);
    });

    $(document).on('click', '.remove-medicine', function () {
        $(this).closest('.medicine-row').remove();
    });


    $(document).ready(function () {        

        $('#interested_product').on('change', function () {
            var productId = $(this).val();

            if (productId) {
                $.ajax({
                    url: '/admin/get-product-details/' + productId,
                    type: 'GET',
                    success: function (response) {
                        $('#product').text(response.produc_life);
                        $('#quantity').text(response.quantity);
                        $('#product_price').text(response.price);
                        $('#product_description').text(response.description);
                        $('#product_details').show();
                        if(response.upsale == 'Yes'){
                            $('#product_upsale').show();
                        }else{
                            $('#product_upsale').hide();
                        }
                    }
                });
            } else {
                $('#product_details').hide();
            }
        });
    });
</script>

@endsection