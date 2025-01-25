@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-primary">Notifications</h1>

    @if($notifications->count() > 0)
        <div class="list-group" role="list">
            @foreach($notifications as $notification)
                <div
                    class="list-group-item list-group-item-action {{ $notification->read ? 'bg-light' : 'bg-white border-primary' }}"
                    role="listitem"
                    aria-labelledby="notification-title-{{ $notification->id }}"
                    aria-describedby="notification-body-{{ $notification->id }}">

                    <div class="d-flex justify-content-between align-items-start">
                        <h2 id="notification-title-{{ $notification->id }}"
                            class="h5 {{ !$notification->read ? 'text-primary fw-bold' : '' }}">
                            {{ $notification->title }}
                        </h2>

                        <div class="text-muted small">
                            <time datetime="{{ $notification->created_at->toIso8601String() }}">
                                {{ $notification->created_at->diffForHumans() }}
                            </time>
                        </div>
                    </div>

                    <p id="notification-body-{{ $notification->id }}" class="mb-1">
                        {{ $notification->body }}
                    </p>

                    <div class="d-inline mt-2 d-flex gap-2 justify-content-end align-items-center">
                        @if(!$notification->read)
                            <form action="{{ route('notifications') }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="id" value="{{ $notification->id }}">
                                <input type="hidden" name="read" value="1">
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="btn btn-link p-0 text-primary text-decoration-none"
                                    aria-label="Mark notification '{{ $notification->title }}' as read">
                                    <span title="Mark as view" class="fa fa-eye"></span>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('notifications') }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="id" value="{{ $notification->id }}">
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-link p-0 text-primary text-decoration-none"
                                aria-label="Mark notification '{{ $notification->title }}' as read">
                                <span title="Delete" class="fa fa-trash"></span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <nav aria-label="Notification pagination" class="mt-4">
            {{ $notifications->links() }}
        </nav>
    @else
        <h2 class="h4">No notifications</h2>
    @endif
@endsection
