<div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
            <ul class="list-group">
                @foreach($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-start">
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
            <div class="">
                <td colspan="20">

                    <div class="req-td-norecords">
                        <img src="{{ asset('images/No notifications.webp') }}" alt="No Records"
                            class="req-img-norecords">

                        </h3>
                    </div>
                </td>
            </div>

            @endif
        </div>
    </div>
</div>
