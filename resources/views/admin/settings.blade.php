@extends('layouts.main')
@section('page', 'settings')

@section('content')
<div class="grid grid-2 gap-4 slide-up">
        <!-- ACADEMY PROFILE (Admin Only) -->
        <div class="card" data-role-limit="admin">
          <div class="card-header">
            <h4 class="font-semibold">Academy Public Info</h4>
          </div>
          <form id="academyConfigForm" onsubmit="saveAcademyConfig(event)" class="card-body">
            <div class="form-group">
              <label class="form-label" for="acName">Academy Name</label>
              <input type="text" id="acName" class="form-control" value="Harita Music Academy" required>
            </div>
            <div class="grid grid-2 gap-2">
              <div class="form-group">
                <label class="form-label" for="acEmail">Contact Email</label>
                <input type="email" id="acEmail" class="form-control" value="info@haritamusic.com" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="acPhone">Support Phone</label>
                <input type="text" id="acPhone" class="form-control" value="+91 80 4432 1099" required>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label" for="acAddress">Address</label>
              <input type="text" id="acAddress" class="form-control" value="12, Veena Avenue, Carnatic Nagar, Chennai - 600028" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Academy Details</button>
          </form>
        </div>

        <!-- SCHEDULING RULES (Admin Only) -->
        <div class="card" data-role-limit="admin">
          <div class="card-header">
            <h4 class="font-semibold">Scheduling Policies</h4>
          </div>
          <form id="policiesForm" onsubmit="savePolicies(event)" class="card-body">
            <div class="form-group">
              <label class="form-label" for="polDuration">Default Class Duration</label>
              <select id="polDuration" class="form-control">
                <option value="60">60 Minutes</option>
                <option value="45">45 Minutes</option>
                <option value="30">30 Minutes</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="polRescheduleLimit">Reschedule Locking Window (Hours)</label>
              <input type="number" id="polRescheduleLimit" class="form-control" value="24" min="1">
            </div>
            <div class="form-group">
              <label class="checkbox-label">
                <input type="checkbox" id="polApproval" checked> Admin approval required for cover teachers on leaves
              </label>
            </div>
            <div class="form-group">
              <label class="checkbox-label">
                <input type="checkbox" id="polAutoCredits"> Automatically deduct student credit when class starts
              </label>
            </div>
            <button type="submit" class="btn btn-primary">Save System Policies</button>
          </form>
        </div>



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
            <div id="teacherFields" style="border-top: 1px solid var(--border-light); padding-top: 1.25rem; margin-top: 1.25rem;">
              <h5 class="font-bold text-serif text-primary mb-3" style="font-size: 0.95rem;">Teacher Profile Public Details</h5>
              <div class="form-group mb-3">
                <label class="form-label" for="usrYoutube">YouTube Video Link (Showcase your talent)</label>
                <input type="text" id="usrYoutube" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ">
              </div>
              <div class="form-group mb-3">
                <label class="form-label" for="usrCertifications">Certifications (comma separated)</label>
                <input type="text" id="usrCertifications" class="form-control" placeholder="e.g. Carnatic Vocal Acharya, Trinity Grade 8">
              </div>
              <div class="form-group mb-3">
                <label class="form-label" for="usrBio">Biography / About Me</label>
                <textarea id="usrBio" class="form-control" style="height: 80px;" placeholder="Describe your experience and teaching methodology..."></textarea>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile Settings</button>
          </form>
        </div>
        <!-- BUSINESS RULES (Admin Only) -->
        <div class="card" data-role-limit="admin">
          <div class="card-header">
            <h4 class="font-semibold">Business Rules</h4>
          </div>
          <form id="businessRulesForm" onsubmit="saveBusinessRules(event)" class="card-body">
            <div class="grid grid-2 gap-3">
              <div class="form-group">
                <label class="form-label" for="oppTeacher">Opportunity Teacher</label>
                <div style="position: relative; display: flex; align-items: center;">
                  <input type="number" id="oppTeacher" class="form-control" value="10" min="0" max="100" style="padding-right: 2.5rem;" required>
                  <span style="position: absolute; right: 1rem; font-weight: bold; color: var(--text-muted);">%</span>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label" for="oppStudent">Opportunity Student</label>
                <div style="position: relative; display: flex; align-items: center;">
                  <input type="number" id="oppStudent" class="form-control" value="2" min="0" style="padding-right: 5.5rem;" required>
                  <span style="position: absolute; right: 1rem; font-weight: bold; color: var(--text-muted);">Credits</span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Business Rules</button>
          </form>
        </div>
      </div>

      <!-- Developed by Sitesoch footer -->
      <footer class="footer">
        <p>© 2026 Harita Music Academy. All rights reserved. | Developed by <a href="https://sitesoch.com" target="_blank">Sitesoch</a></p>
      </footer>
@endsection
