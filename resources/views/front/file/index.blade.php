@extends('front.layout.main')

@section('css')

@endsection

@section('content')

        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">My Files</h5>
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
                            <a href="{{ route('user.file.add') }}" class="btn btn-primary">
                                <i class="feather-user-plus me-2"></i>
                                <span>Add File</span>
                            </a>
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
                                                <th>File Name</th>
                                                <th>File Type</th>
                                                <th>Created At</th>
                                                <th>Added By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         @foreach($files as $key=> $file)
                                          <tr>
                                            <td>
                                                    {{ ($files->currentPage()-1) * $files->perPage() + $key + 1 }}
                                            </td>
                                            <td>
                                                <a href="{{ url('user/files/download/'.$file->id) }}">
                                                {{ $file->file_name }}
                                                </a>
                                            </td>
                                            <td>{{ $file->file_type }}</td>
                                            <td>{{ $file->created_at->format('d-m-Y h:i A') }}</td>
                                            <td>{{ ucfirst($file->added_by) }}</td>
                                          </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div> 
                                @if(count($files) == 0) 
                                    <div class="d-flex justify-content-center mt-4">
                                    <h3>{{ 'Data Not Found' }}</h3>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{ $files->links('pagination::bootstrap-5') }}
            </div>
            <!-- [ Main Content ] end -->            
        </div>     
        
@endsection



@section('script')

<script>        

   
</script>

@endsection