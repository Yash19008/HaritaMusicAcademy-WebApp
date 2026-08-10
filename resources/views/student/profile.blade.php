@extends('layouts.main')
@section('title', 'My Profile')
@section('page', 'profile')

@push('styles')
<style>
    .profile-hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      padding: 2rem 1.5rem;
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      gap: 1.5rem;
      color: var(--text-white);
      margin-bottom: 1.5rem;
      border: 1px solid var(--primary-light);
    }

    @media (max-width: 768px) {
      .profile-hero {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem 1rem;
      }
    }

    .profile-avatar-box .avatar-lg {
      border: 3px solid var(--secondary);
      background-color: var(--bg-card);
      color: var(--primary);
      box-shadow: var(--shadow-lg);
    }

    .profile-meta h2 {
      color: var(--secondary-light);
      font-size: 1.6rem;
      margin-bottom: 0.25rem;
    }

    .profile-meta p {
      color: rgba(255, 255, 255, 0.85);
      font-size: 0.9rem;
    }

    .profile-grid-layout {
      display: grid;
      grid-template-columns: 2fr 2fr;
      gap: 1.25rem;
      animation: fadeIn var(--transition-speed);
    }

    @media (max-width: 992px) {
      .profile-grid-layout {
        grid-template-columns: 1fr;
      }
    }

    .info-list-item {
      display: flex;
      justify-content: space-between;
      padding: 0.65rem 0;
      border-bottom: 1px solid var(--border-light);
      font-size: 0.85rem;
    }

    .info-list-item:last-child {
      border-bottom: none;
    }
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="profile-hero slide-up">
    <div class="profile-avatar-box">
        <div class="avatar avatar-lg" id="profileInitials">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
    </div>
    <div class="profile-meta">
        <h2 id="profileName">{{ $user->name }}</h2>
        <p id="profileSub">{{ $student ? ($student->course ? $student->course->name : $student->enrolled_format) : 'Student' }} | Harita Music Academy</p>
    </div>
</div>

<!-- STUDENT PROFILE VIEW -->
<div class="profile-grid-layout slide-up">
    <div class="card">
        <div class="card-header">
            <h4 class="font-semibold">Enrolment Overview</h4>
        </div>
        <div class="card-body">
            <div class="info-list-item">
                <span class="text-muted">Primary Instrument</span>
                <span class="font-semibold">{{ $student ? ($student->course ? $student->course->name : '-') : '-' }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Assigned Mentor</span>
                <span class="font-semibold">{{ $student && $student->teacher ? $student->teacher->user->name : 'Not Assigned' }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Remaining Credits</span>
                <span class="font-semibold text-primary">{{ $student ? $student->credits : 0 }} Class Credits</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Admission Date</span>
                <span class="font-semibold">{{ $student && $student->joining_date ? \Carbon\Carbon::parse($student->joining_date)->format('F d, Y') : '-' }}</span>
            </div>
            <div class="info-list-item">
                <span class="text-muted">Learning Level</span>
                <span class="font-semibold text-success">{{ $student && $student->enrolled_level ? $student->enrolled_level : '-' }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="font-semibold">Package Purchases History</h4>
        </div>
        <div class="card-body">
            @if(isset($payments) && $payments->count() > 0)
                @foreach($payments as $payment)
                <div class="info-list-item">
                    <div>
                        <div class="font-semibold">Package Payment</div>
                        <div class="text-muted" style="font-size: 0.7rem;">Paid: {{ $payment->payment_mode ?? 'Online' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">₹{{ number_format($payment->amount) }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($payment->transaction_date)->format('M d, Y') }}</div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="p-4 text-center text-muted" style="background: var(--bg-body); border: 1px dashed var(--border-color); border-radius: var(--radius-md);">
                    <p style="font-size: 0.85rem; margin: 0;">No purchase history found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
