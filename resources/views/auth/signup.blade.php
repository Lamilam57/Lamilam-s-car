<x-guest-layout title="Signup | Car Findal Service" class="page-signup">
    <h1 class="auth-page-title">Signup</h1>

    <form action="{{ route('postSignUp') }}" method="POST">
        @csrf

        <div class="form-group">
            <input type="email" name="email" placeholder="Your Email" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Your Password" required>
        </div>

        <div class="form-group">
            <input
                type="password"
                name="password_confirmation"
                placeholder="Repeat Password"
                required
            >
        </div>

        <hr>

        <div class="form-group">
            <input type="text" name="name" placeholder="Name">
        </div>

        <div class="form-group">
            <input type="text" name="phone" placeholder="Phone">
        </div>

        <button class="btn btn-primary btn-login w-full">
            Signup
        </button>

        <div class="grid grid-cols-2 gap-1 social-auth-buttons">
            <x-facebook-btn/>
            <x-google-btn/>
        </div>

        <div class="login-text-dont-have-account">
            Already have an account? -
            <a href="{{ route('login') }}"> Click here to login </a>
        </div>
    </form>
</x-guest-layout>
