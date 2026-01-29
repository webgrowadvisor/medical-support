                                <div class="table-responsive">
                                    <table class="table table-hover" id="leadList">
                                        <thead>
                                            <tr>
                                                
                                                <th>S.No.</th>
                                                <th>Doctor Name</th>
                                                <th>Notes</th>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($appointments as $key => $appointment)
                                            <tr>
                                                <td>
                                                    {{ ($appointments->currentPage() - 1) * $appointments->perPage() + $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $appointment->doctor->name ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $appointment->notes ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                                </td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_end)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    @if($appointment->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($appointment->status == 'completed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @elseif($appointment->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    No Appointments Found
                                                </td>
                                            </tr>
                                        @endforelse
                                                
                                        </tbody>
                                    </table>
                                </div>