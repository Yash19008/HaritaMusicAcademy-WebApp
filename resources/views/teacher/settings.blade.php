@extends('layouts.main')
@section('page', 'settings')

@section('content')


<div class="grid grid-2 gap-4 slide-up mb-4">
    <div class="card grid-2-col-span-2" style="grid-column: span 2;">
        <div class="card-header">
            <h4 class="font-semibold">My Account Credentials</h4>
        </div>
        <form method="POST" action="{{ route('teacher.settings.save') }}" class="card-body">
            @csrf
            <div class="grid grid-2 gap-3 mb-3">
                <div class="form-group">
                    <label class="form-label" for="usrName">My Name</label>
                    <input type="text" name="name" id="usrName" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="usrEmail">My Email Address</label>
                    <input type="email" id="usrEmail" class="form-control" value="{{ $user->email }}" disabled>
                    <small class="text-muted">Contact administrator to change email.</small>
                </div>
            </div>
            <div class="grid grid-2 gap-3 mb-4">
                <div class="form-group">
                    <label class="form-label" for="usrPassword">New Password</label>
                    <input type="password" name="password" id="usrPassword" class="form-control" placeholder="Leave blank to keep unchanged">
                </div>
                <div class="form-group">
                    <label class="form-label" for="usrPasswordConfirm">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="usrPasswordConfirm" class="form-control" placeholder="Leave blank to keep unchanged">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile Settings</button>
        </form>
    </div>
</div>
@endsection
