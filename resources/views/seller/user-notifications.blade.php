@if($notifications->count())
    @foreach($notifications as $notification)
        <div class="notifications-item {{ $notification->is_read ? '' : 'bg-light' }}">
            {{-- <img src="{{ asset('assets/images/avatar/2.png') }}" class="rounded me-3 border" /> --}}
            <div class="rounded-circle border d-flex align-items-center justify-content-center me-3 bg-white" style="width:40px; height:40px;">
                {{ strtoupper(substr($notification->title, 0, 1)) }}
            </div>

            <div class="notifications-desc">
                <a href="javascript:void(0);" 
                   class="font-body text-wrap">
                    <span class="fw-semibold text-dark d-block">
                        {{ $notification->title }}
                    </span>
                    <span class="d-block">
                        {{ $notification->note }}
                    </span>
                </a>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="notifications-date text-muted">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>

                    <div class="d-flex gap-2">
                        @if(!$notification->is_read)
                            <a href="javascript:void(0);"
                                onclick="readsingle({{ $notification->id }})" 
                               class="mark-read"
                               data-id="{{ $notification->id }}"
                               title="Mark as Read">
                                <i class="feather-x fs-12"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="text-center text-muted p-3">
        No notifications
    </div>
@endif
