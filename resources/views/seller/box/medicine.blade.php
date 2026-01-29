<div class="table-responsive">
                                    <table class="table table-hover" id="leadList">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Medicines</th>
                                                <th>Notes</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($prescriptions ?? [] as $key => $availability)
                                            <tr>
                                                <td>
                                                {{ ($prescriptions->currentPage() - 1) * $prescriptions->perPage() + $key + 1 }}
                                                </td>
                                                 <td>
                                                    @foreach ($availability->medicines as $key => $medicines)
                                                        @if(!empty($medicines))
                                                           {{ $key + 1 }} _ {{ $medicines }}<br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{!! $availability->notes !!}</td>                                                
                                                <td>{{ \Carbon\Carbon::parse($availability->prescription_date)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No availability added</td>
                                            </tr>
                                        @endforelse                               
                                        </tbody>
                                    </table>
                    </div>  