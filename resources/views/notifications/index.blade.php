@extends('layouts.main')

@section('title', 'Notifications')

@section('content')
    <style>
        .notification-list-item:hover {
            background-color: rgba(13, 148, 136, 0.12) !important;
            box-shadow: inset 4px 0 0 var(--primary);
        }
    </style>

    <div class="content-header">
        <div class="content-title">
            <h2>All Notifications</h2>
            <p class="content-subtitle">View and manage your academy notifications</p>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body" style="padding: 0;">
            <div style="display: flex; flex-direction: column;">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $createdAt = \Carbon\Carbon::parse($notification->created_at);
                        $notificationUrl = $data['url'] ?? null;

                        // Convert timezone if student
                        if (auth()->user()->hasRole('student') && auth()->user()->student) {
                            $tz = auth()->user()->student->timezone ?: 'Asia/Kolkata';
                            $createdAt->setTimezone($tz);
                        }
                    @endphp
                    @if ($notificationUrl)
                        <a href="{{ $notificationUrl }}" class="notification-list-item" style="display: flex; gap: 12px; padding: 12px 1.5rem; border-bottom: 1px solid var(--border-color); align-items: flex-start; background-color: {{ $notification->read_at ? 'transparent' : 'rgba(13, 148, 136, 0.04)' }}; transition: background 0.2s, box-shadow 0.2s; text-decoration: none; color: inherit;">
                    @else
                    <div
                        style="display: flex; gap: 12px; padding: 12px 1.5rem; border-bottom: 1px solid var(--border-color); align-items: flex-start; background-color: {{ $notification->read_at ? 'transparent' : 'rgba(13, 148, 136, 0.04)' }}; transition: background 0.2s;">
                    @endif
                        <div style="font-size: 1.25rem; margin-top: 2px;">
                            {{ $data['icon'] ?? '🔔' }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <h4 style="margin: 0 0 2px 0; color: var(--text-main); font-size: 0.95rem; font-weight: 600;">
                                {{ $data['title'] ?? 'Notification' }}</h4>
                            <p style="margin: 0; color: var(--text-muted); font-size: 0.85rem; line-height: 1.4;">
                                {{ $data['message'] ?? '' }}</p>
                        </div>
                        <div
                            style="font-size: 0.75rem; color: #94a3b8; text-align: right; min-width: 120px; font-weight: 500;">
                            <div>{{ $createdAt->format('M d, Y h:i A') }}</div>
                            <div style="color: var(--primary); margin-top: 2px;">{{ $createdAt->diffForHumans() }}</div>
                        </div>
                    @if ($notificationUrl)
                        </a>
                    @else
                        </div>
                    @endif
                @empty
                    <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">📭</div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 0.25rem;">No Notifications</h3>
                        <p style="font-size: 0.9rem;">You don't have any notifications yet.</p>
                    </div>
                @endforelse
            </div>

            @if ($notifications->hasPages())
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); background: #fafbfc;">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mark all as read when the page is loaded
                fetch('{{ route('notifications.markAsRead') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({})
                }).catch(error => console.error('Error marking notifications as read:', error));
            });
        </script>
    @endpush
@endsection
