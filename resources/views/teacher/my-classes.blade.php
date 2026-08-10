@extends('layouts.main')
@section('page', 'my-classes')

@push('styles')
<style>
.agenda-item {
      background-color: var(--bg-card);
      border: 1px solid var(--border-color);
      border-left: 5px solid var(--primary);
      padding: 1.25rem;
      border-radius: var(--radius-md);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 1.25rem;
      transition: all 0.2s;
      box-shadow: var(--shadow-sm);
    }

    #agendaList {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 1.25rem;
    }

    .agenda-item:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .agenda-item.completed {
      border-left-color: var(--success);
    }

    .agenda-item.reschedule-requested {
      border-left-color: var(--warning);
    }

    .agenda-item.cancelled {
      border-left-color: var(--danger);
    }

    .agenda-item .btn {
      flex: 1;
      text-align: center;
      justify-content: center;
    }

    .agenda-item .d-flex.gap-2 {
      width: 100%;
    }

    .agenda-item>div:last-child {
      display: flex;
      width: 100%;
      justify-content: flex-start;
      align-items: center;
    }

    .agenda-item .badge {
      width: 100%;
      text-align: center;
      justify-content: center;
      padding: 0.5rem;
      font-size: 11px;
      font-weight: 700;
      border-radius: var(--radius-sm);
    }

    .agenda-date-badge {
      background-color: var(--border-light);
      padding: 0.4rem 0.85rem;
      border-radius: var(--radius-sm);
      text-align: center;
      min-width: 70px;
    }
</style>
@endpush

@section('content')
<div class="card slide-up mb-4">
    <div class="card-body">
        <h4 class="font-semibold text-primary mb-3">All Classes</h4>
        
        <div id="agendaList">
            @forelse($classes as $booking)
                @php
                    $customClass = "";
                    $badgeClass = "badge-primary";
                    $statusLabel = ucfirst($booking->status);

                    if ($booking->status === "completed") {
                        $customClass = "completed";
                        $badgeClass = "badge-success";
                    } elseif ($booking->status === "reschedule_requested") {
                        $customClass = "reschedule-requested";
                        $badgeClass = "badge-warning";
                        $statusLabel = "Reschedule Requested";
                    } elseif ($booking->status === "cancelled") {
                        $customClass = "cancelled";
                        $badgeClass = "badge-danger";
                    }
                @endphp
                <div class="agenda-item {{ $customClass }}">
                    <div class="d-flex align-center gap-3">
                        <div class="agenda-date-badge">
                            <div class="font-bold text-primary" style="font-size: 1.2rem;">{{ $booking->starts_at->format('d') }}</div>
                            <div class="text-muted font-bold" style="font-size: 0.7rem;">{{ strtoupper($booking->starts_at->format('M')) }}</div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-primary">{{ $booking->student->user->name ?? 'Unknown Student' }}</h4>
                            <div class="text-muted" style="font-size: 0.8rem;">
                                <strong>{{ $booking->starts_at->format('h:i A') }}</strong> ({{ $booking->duration_minutes }} mins)<br>
                                {{ $booking->instrument }}
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($booking->status === 'scheduled')
                            <div class="d-flex gap-2">
                                <a href="{{ $booking->google_meet_link ?? 'https://meet.google.com' }}" target="_blank" class="btn btn-primary btn-sm" style="text-decoration:none;">Start Class</a>
                            </div>
                        @else
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted w-100 p-4" style="grid-column: 1 / -1;">No classes found.</div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    </div>
</div>

@if($demos->count() > 0)
<div class="card slide-up mb-4 mt-4">
    <div class="card-body">
        <h4 class="font-semibold text-warning mb-3">Demo Classes</h4>
        
        <div id="demoList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem;">
            @foreach($demos as $demo)
                @php
                    $customClass = "";
                    $badgeClass = "badge-primary";
                    $statusLabel = ucfirst($demo->status);

                    if ($demo->status === "completed" || $demo->status === "converted") {
                        $customClass = "completed";
                        $badgeClass = "badge-success";
                    } elseif ($demo->status === "cancelled" || $demo->status === "no-show") {
                        $customClass = "cancelled";
                        $badgeClass = "badge-danger";
                    }
                @endphp
                <div class="agenda-item {{ $customClass }}" style="border-left-color: var(--warning);">
                    <div class="d-flex align-center gap-3">
                        <div class="agenda-date-badge">
                            <div class="font-bold text-primary" style="font-size: 1.2rem;">{{ $demo->scheduled_at->format('d') }}</div>
                            <div class="text-muted font-bold" style="font-size: 0.7rem;">{{ strtoupper($demo->scheduled_at->format('M')) }}</div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-primary">{{ $demo->student_name }} (Demo)</h4>
                            <div class="text-muted" style="font-size: 0.8rem;">
                                <strong>{{ $demo->scheduled_at->format('h:i A') }}</strong> ({{ $demo->duration_minutes }} mins)<br>
                                {{ $demo->instrument }}
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($demo->status === 'scheduled')
                            <div class="d-flex gap-2">
                                <a href="{{ $demo->google_meet_link ?? 'https://meet.google.com' }}" target="_blank" class="btn btn-primary btn-sm" style="text-decoration:none;">Start Demo</a>
                            </div>
                        @else
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $demos->links() }}
        </div>
    </div>
</div>
@endif
@endsection
