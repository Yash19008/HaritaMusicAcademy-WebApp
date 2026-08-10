@extends('layouts.main')
@section('page', 'settings')

@section('content')
<div class="grid grid-2 gap-4 slide-up">
        <!-- ACADEMY PROFILE (Admin Only) -->
        

        <!-- SCHEDULING RULES (Admin Only) -->
        

        <!-- USER PROFILE PREFERENCES (Visible to All Roles) -->
        <div class="card grid-2-col-span-2" style="grid-column: span 2;">
          <div class="card-header">
            <h4 class="font-semibold">My Account Credentials</h4>
          </div>
          <form id="userAccountForm" onsubmit="saveUserAccount(event)" class="card-body">
            <div class="grid grid-2 gap-3">
              <div class="form-group">
                <label class="form-label" for="usrName">My Name</label>
                <input type="text" id="usrName" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="usrEmail">My Email Address</label>
                <input type="email" id="usrEmail" class="form-control" required>
              </div>
            </div>
            <div class="grid grid-2 gap-3">
              <div class="form-group">
                <label class="form-label" for="usrPassword">New Password</label>
                <input type="password" id="usrPassword" class="form-control" placeholder="Leave blank to keep unchanged">
              </div>
              <div class="form-group">
                <label class="form-label" for="usrPasswordConfirm">Confirm Password</label>
                <input type="password" id="usrPasswordConfirm" class="form-control" placeholder="Leave blank to keep unchanged">
              </div>
            </div>

            <!-- Teacher-specific Fields -->
            

            <button type="submit" class="btn btn-primary">Update Profile Settings</button>
          </form>
        </div>
      </div>

      <!-- Developed by Sitesoch footer -->
      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>
@endsection
