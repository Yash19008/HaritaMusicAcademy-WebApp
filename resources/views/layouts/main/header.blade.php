@php
    // Look up the active page label from sidebar nav items
    $activePage = trim($__env->yieldContent('page', 'dashboard'));
    $user        = auth()->user();
    $isTeacher   = $user->hasRole('teacher');
    $isStudent   = $user->hasRole('student');
    $isInternal  = !$isTeacher && !$isStudent;

    // Build minimal label map from roles
    $allNavLabels = [
        // Admin / internal
        'dashboard'    => 'Dashboard',
        'students'     => 'Student Master',
        'teachers'     => 'Teacher Master',
        'credits'      => $isStudent ? 'My Credits' : 'Credit Management',
        'class-booking'=> 'Class Booking',
        'leaves'       => $isTeacher ? 'Leaves' : 'Leave Approval',
        'roles'        => 'Access Control',
        'sales'        => 'Sales Dashboard',
        'demos'        => 'Demo Classes',
        'demo-classes' => 'Demo Classes',
        'reports'      => 'Reports Feed',
        'syllabus'     => $isInternal ? 'Syllabus Master' : 'Syllabus',
        'curriculum'   => 'Curriculum Master',
        'payroll'      => 'Payroll',
        'referrals'    => 'Referrals',
        'feedbacks'    => 'Feedbacks',
        'feedback'     => 'Feedback',
        'profile'      => $isInternal ? 'My Profile' : 'Profile',
        'settings'     => 'Settings',
        'my-classes'   => 'My Classes',
        'resources'    => 'Resources',
    ];

    $headerTitle    = $allNavLabels[$activePage] ?? ucwords(str_replace('-', ' ', $activePage));
    $headerPreTitle = $headerTitle === 'Dashboard' ? 'Welcome back' : 'You are viewing';
@endphp
<!-- HEADER -->
    <header class="header">
      <div class="header-left">
        <button class="menu-toggle">☰</button>
        <div class="header-title-container">
          <span class="header-pre-title">{{ $headerPreTitle }}</span>
          <h1 class="header-title">{{ $headerTitle }}</h1>
        </div>
      </div>

      <div class="header-right">
        <!-- Notification Dropdown -->
        @php
            $unreadNotifications = auth()->user()->unreadNotifications;
            $topNotifications = $unreadNotifications->take(10);
            $unreadCount = $unreadNotifications->count();
        @endphp
        <div class="header-profile-dropdown">
          <button class="header-icon-btn" data-toggle="dropdown" data-target="notificationDropdown" onclick="markNotificationsAsRead()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
              <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            @if($unreadCount > 0)
              <span class="badge-dot" id="notificationBadge"></span>
            @endif
          </button>
          <style>
            .notification-dropdown {
              width: 320px;
              max-height: 400px;
              overflow-y: auto;
              padding: 0;
            }
            .notification-dropdown .dropdown-header {
              padding: 10px 15px;
              font-weight: 700;
              background: #f8f9fa;
              border-bottom: 1px solid var(--border-color);
              position: sticky;
              top: 0;
              z-index: 10;
              font-size: 0.9rem;
            }
            .notification-item {
              display: flex;
              align-items: flex-start;
              gap: 12px;
              padding: 12px 15px;
              border-bottom: 1px solid var(--border-color);
              transition: background 0.2s;
              text-decoration: none;
              color: inherit;
            }
            .notification-item:hover {
              background: #f1f5f9;
            }
            .notification-item:last-child {
              border-bottom: none;
            }
            .notification-item-icon {
              font-size: 1.25rem;
              line-height: 1;
              margin-top: 2px;
            }
            .notification-item-content {
              flex: 1;
              min-width: 0;
            }
            .notification-item-title {
              font-weight: 600;
              font-size: 0.85rem;
              color: var(--text-main);
              margin-bottom: 2px;
            }
            .notification-item-message {
              font-size: 0.8rem;
              color: var(--text-muted);
              line-height: 1.3;
              margin-bottom: 4px;
            }
            .notification-item-time {
              font-size: 0.7rem;
              color: #94a3b8;
              font-weight: 500;
            }
          </style>
          <div id="notificationDropdown" class="dropdown-menu notification-dropdown">
            <div class="dropdown-header">Academy Notifications</div>
            
            @forelse($topNotifications as $notification)
            @php 
                $data = $notification->data;
                $createdAt = \Carbon\Carbon::parse($notification->created_at);
                
                // Convert timezone if student
                if (auth()->user()->hasRole('student') && auth()->user()->student) {
                    $tz = auth()->user()->student->timezone ?: 'Asia/Kolkata';
                    $createdAt->setTimezone($tz);
                }
            @endphp
            @php $notifUrl = $data['url'] ?? null; @endphp
            @if($notifUrl)
            <a href="{{ $notifUrl }}" class="notification-item" style="text-decoration:none;">
            @else
            <div class="notification-item">
            @endif
              <div class="notification-item-icon">{{ $data['icon'] ?? '🔔' }}</div>
              <div class="notification-item-content">
                <div class="notification-item-title">{{ $data['title'] ?? 'Notification' }}</div>
                <div class="notification-item-message">{{ $data['message'] ?? '' }}</div>
                <div class="notification-item-time">{{ $createdAt->diffForHumans() }}</div>
              </div>
            @if($notifUrl)
            </a>
            @else
            </div>
            @endif
            @empty
            <div class="notification-item">
              <div class="notification-item-content">
                <div class="notification-item-message" style="margin:0;">No new notifications.</div>
              </div>
            </div>
            @endforelse

            <a href="{{ route('notifications.index') }}" class="dropdown-item" style="text-align: center; color: var(--primary); font-weight: 600; padding: 10px; background: #f8f9fa; border-top: 1px solid var(--border-color); position: sticky; bottom: 0;">See All Notifications</a>
          </div>
        </div>

        <script>
            function markNotificationsAsRead() {
                var badge = document.getElementById('notificationBadge');
                if (badge) {
                    badge.style.display = 'none';
                    fetch('{{ route("notifications.markAsRead") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    }).catch(error => console.error('Error marking notifications as read:', error));
                }
            }
        </script>

        <!-- Profile Dropdown -->
        <div class="header-profile-dropdown">
          <button class="header-profile-trigger" data-toggle="dropdown" data-target="profileDropdown">
            @if(isset(auth()->user()->avatar) && auth()->user()->avatar)
              <img src="{{ auth()->user()->avatar }}" class="avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;" alt="{{ auth()->user()->name }}">
            @else
              <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</div>
            @endif
          </button>
          <div id="profileDropdown" class="dropdown-menu">
            <div class="dropdown-header">User Menu</div>
            @php
                if ($isTeacher) {
                    $profileRoute  = route('teacher.profile');
                    $settingsRoute = route('teacher.settings');
                } elseif ($isStudent) {
                    $profileRoute  = route('student.profile');
                    $settingsRoute = route('student.settings');
                } else {
                    $profileRoute  = route('admin.profile');
                    $settingsRoute = route('admin.settings');
                }
            @endphp
            <a href="{{ $profileRoute }}" class="dropdown-item">My Profile</a>
            <a href="{{ $settingsRoute }}" class="dropdown-item">Settings</a>
            <div style="border-top: 1px solid var(--border-color); margin: 0.25rem 0;"></div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
              @csrf
              <button type="submit" class="dropdown-item" style="color: var(--danger); width: 100%; text-align: left; background: none; border: none; padding: 0.5rem 1rem; cursor: pointer;">Log Out</button>
            </form>
          </div>
        </div>
      </div>
    </header>