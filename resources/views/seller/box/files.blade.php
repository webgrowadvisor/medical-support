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
                                                <a href="{{ url('doctor/files/download/'.$file->id) }}">
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