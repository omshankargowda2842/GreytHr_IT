<div>
    <div class="card border-0">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
            <ul class="list-group">
                @foreach($notifications as $notification)
                <li class="list-group-item notification-item d-flex justify-content-between align-items-start">
                    <div>
                        <a href="{{ $notification->redirect_url }}" class="text-decoration-none fw-bold"
                            wire:click="markAsRead({{ $notification->id }})">
                            {{ $notification->title }}
                        </a>
                        <p class="mb-0 text-muted">{{ $notification->message }}</p>
                    </div>
                    <small class="text-muted"
                        style="margin-left: 9px;white-space: nowrap;">{{ $notification->created_at->diffForHumans() }}</small>
                </li>
                @endforeach
            </ul>
            @else
            <div>
                <div class="req-td-norecords">
                    <img src="{{ asset('images/No notifications.webp') }}" alt="No Records"
                        class="req-img-norecords">
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
