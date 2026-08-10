<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Harita Music Academy')</title>
  <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/css/dashboard-layout.css') }}">
  @stack('styles')
</head>
<body>
  <!-- PRELOADER -->
  <div id="preloader" class="preloader-overlay">
    <div class="preloader-content">
      <img src="{{ asset('admin-assets/assets/logo.png') }}" class="preloader-logo" alt="Harita Logo">
      <div class="preloader-spinner"></div>
    </div>
  </div>

  <div class="app-container">
    @include('layouts.main.sidebar')
    @include('layouts.main.header')

    <main class="main-content">
      <!-- Success/Error Messages -->
      @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; padding: 1rem; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 0.375rem;">
          <strong>✓</strong> {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem; padding: 1rem; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 0.375rem;">
          <strong>×</strong> {{ session('error') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1rem; padding: 1rem; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 0.375rem;">
          <strong>Validation Errors:</strong>
          <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  @stack('modals')

  <script src="{{ asset('admin-assets/js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>