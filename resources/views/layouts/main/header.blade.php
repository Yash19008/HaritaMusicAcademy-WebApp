<!-- HEADER -->
    <header class="header">
      <div class="header-left">
        <button class="menu-toggle">☰</button>
        <div class="header-title-container">
          <span class="header-pre-title">Welcome back</span>
          <h1 class="header-title">Dashboard Overview</h1>
        </div>
      </div>

      <div class="header-right">
        <!-- Notification Dropdown -->
        <div class="header-profile-dropdown">
          <button class="header-icon-btn" data-toggle="dropdown" data-target="notificationDropdown">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
              <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            <span class="badge-dot"></span>
          </button>
          <div id="notificationDropdown" class="dropdown-menu notification-dropdown">
            <div class="dropdown-header">Academy Notifications</div>
            <div class="notification-item">
              <div class="notification-item-icon">🔔</div>
              <div class="notification-item-content">
                <div class="notification-item-title">Leave Request pending</div>
                <div>Teacher Meera Sharma applied for 3 days of leave.</div>
                <div class="notification-item-time">10 minutes ago</div>
              </div>
            </div>
            <div class="notification-item">
              <div class="notification-item-icon">💳</div>
              <div class="notification-item-content">
                <div class="notification-item-title">Payment Received</div>
                <div>Aria Sharma bought Tabla 20-Class Package.</div>
                <div class="notification-item-time">2 hours ago</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="header-profile-dropdown">
          <button class="header-profile-trigger" data-toggle="dropdown" data-target="profileDropdown">
            <div class="avatar">AD</div>
          </button>
          <div id="profileDropdown" class="dropdown-menu">
            <div class="dropdown-header">User Menu</div>
            <a href="{{ route('admin.profile') }}" class="dropdown-item">My Profile</a>
            <a href="{{ route('admin.settings') }}" class="dropdown-item">Settings</a>
            <div style="border-top: 1px solid var(--border-color); margin: 0.25rem 0;"></div>
            <a href="/" class="dropdown-item" style="color: var(--danger)">Log Out</a>
          </div>
        </div>
      </div>
    </header>