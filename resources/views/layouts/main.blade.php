<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Harita Music Academy')</title>
  <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}?v=1.2">
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

      <div style="margin-top: auto; padding-top: 2rem; padding-bottom: 0.5rem; text-align: right; font-size: 0.85rem;">
        <a href="{{ url('/privacy') }}" style="color: var(--text-muted, #6c757d); text-decoration: none; font-weight: 500;">Privacy Policy</a>
      </div>
    </main>
  </div>

  @stack('modals')

  @if(auth()->check() && auth()->user()->hasRole('teacher'))
  <!-- OPPORTUNITY POPUP (Hidden by default) -->
  <div id="opportunity-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.6); z-index: 99999; justify-content: center; align-items: center; color: white;">
    <div style="background: #fff; color: #333; padding: 2rem; border-radius: 12px; text-align: center; max-width: 500px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
      <h2 style="color: #2a9d8f; margin-bottom: 0.5rem;">🎉 New Class Opportunity!</h2>
      <p style="font-size: 1.1rem; margin-bottom: 1rem;">Are you available to cover this class?</p>
      
      <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: left;">
        <strong>Subject:</strong> <span id="opp-subject"></span><br>
        <strong>Date:</strong> <span id="opp-date"></span><br>
        <strong>Time:</strong> <span id="opp-time"></span><br>
        <strong>Bonus Reward:</strong> <span style="color: #2a9d8f; font-weight: bold;">&#8377;<span id="opp-bonus"></span></span>
      </div>

      <div style="display: flex; gap: 1rem; justify-content: center;">
        <button id="opp-reject" style="padding: 0.75rem 1.5rem; border: none; border-radius: 6px; background: #adb5bd; color: white; font-weight: bold; cursor: pointer;">Reject</button>
        <button id="opp-accept" style="padding: 0.75rem 1.5rem; border: none; border-radius: 6px; background: #2a9d8f; color: white; font-weight: bold; cursor: pointer; font-size: 1.1rem;">Accept Class</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        let opportunityPolling;
        let currentOppId = null;

        function checkOpportunity() {
            if (document.getElementById('opportunity-popup').style.display === 'flex') return;

            fetch('{{ route("teacher.opportunities.current") }}')
                .then(res => res.json())
                .then(data => {
                    if (data && data.id) {
                        showOpportunity(data);
                    }
                })
                .catch(err => console.error(err));
        }

        function showOpportunity(data) {
            currentOppId = data.id;
            document.getElementById('opp-subject').innerText = data.subject;
            document.getElementById('opp-date').innerText = data.date;
            document.getElementById('opp-time').innerText = data.time;
            document.getElementById('opp-bonus').innerText = data.bonus;
            
            document.getElementById('opportunity-popup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('opportunity-popup').style.display = 'none';
            currentOppId = null;
        }

        document.getElementById('opp-accept').addEventListener('click', function() {
            if (!currentOppId) return;
            const btn = this;
            btn.innerText = 'Accepting...';
            btn.disabled = true;

            fetch(`/teacher/opportunities/${currentOppId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                closePopup();
                btn.innerText = 'Accept Class';
                btn.disabled = false;
                if (data.success) {
                    alert('Success! The class has been assigned to you. Google Calendar updated.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Someone else already accepted this opportunity.');
                }
            })
            .catch(err => {
                btn.innerText = 'Accept Class';
                btn.disabled = false;
                closePopup();
            });
        });

        document.getElementById('opp-reject').addEventListener('click', function() {
            if (!currentOppId) return;
            fetch(`/teacher/opportunities/${currentOppId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            closePopup();
        });

        // Poll every 5 seconds
        opportunityPolling = setInterval(checkOpportunity, 5000);
    });
  </script>
  @endif

  @if(auth()->check() && auth()->user()->hasRole('student') && auth()->user()->student && auth()->user()->student->credits <= 2 && is_null(auth()->user()->student->renewal_interest))
  <div id="renewal-popup" class="modal-overlay" style="display: flex; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 9999;">
      <div class="modal-content" style="background: white; padding: 2rem; border-radius: 12px; max-width: 450px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
          <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
          <h2 style="margin-bottom: 1rem; color: #dc2626;">Low Credits Alert!</h2>
          <p style="margin-bottom: 1.5rem; color: #4b5563; font-size: 1.1rem;">
              You have only <strong>{{ auth()->user()->student->credits }} classes left!</strong><br><br>
              Are you interested in purchasing a new package or enrolling in another course?
          </p>
          <div style="display: flex; gap: 1rem; justify-content: center;">
              <button id="renewal-yes" class="btn btn-primary" style="flex: 1;">Yes, I'm interested!</button>
              <button id="renewal-no" class="btn btn-secondary" style="flex: 1;">No, thanks</button>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          function submitRenewalInterest(interest) {
              const btnYes = document.getElementById('renewal-yes');
              const btnNo = document.getElementById('renewal-no');
              
              if (interest === 'interested') {
                  btnYes.innerText = 'Submitting...';
                  btnYes.disabled = true;
                  btnNo.disabled = true;
              } else {
                  btnNo.innerText = 'Submitting...';
                  btnYes.disabled = true;
                  btnNo.disabled = true;
              }

              fetch('{{ route("student.renewal-interest.submit") }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                      'Accept': 'application/json'
                  },
                  body: JSON.stringify({ interest: interest })
              })
              .then(res => res.json())
              .then(data => {
                  if (data.success) {
                      if (interest === 'interested') {
                          alert('Thank you! Our admin team has been notified and will contact you shortly.');
                      }
                      document.getElementById('renewal-popup').style.display = 'none';
                  }
              })
              .catch(err => {
                  console.error(err);
                  document.getElementById('renewal-popup').style.display = 'none';
              });
          }

          document.getElementById('renewal-yes').addEventListener('click', function() {
              submitRenewalInterest('interested');
          });

          document.getElementById('renewal-no').addEventListener('click', function() {
              submitRenewalInterest('declined');
          });
      });
  </script>
  @endif

  <script src="{{ asset('admin-assets/js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>