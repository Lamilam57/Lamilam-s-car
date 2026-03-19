<x-guest-layout title="Reset Password">
    <h1>Set New Password</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <input 
                type="email" 
                name="email" 
                value="{{ request()->email }}" 
                required 
                readonly
            >
        </div>

        <div class="form-group">
            <input 
                type="password" 
                name="password" 
                placeholder="New Password" 
                required
            >
        </div>

        <div class="form-group">
            <input 
                type="password" 
                name="password_confirmation" 
                placeholder="Confirm Password" 
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Update Password
        </button>
    </form>
</x-guest-layout>