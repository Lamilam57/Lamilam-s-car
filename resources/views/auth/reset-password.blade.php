{{-- <x-guest-layout title="Login | Car Findal Service" class="page-signup">
    <h1 class="auth-page-title">Request Password Reset</h1>

    <form action="{{ route('verification.send') }}" method="GET">
        @csrf

        <div class="form-group">
                <div class="form-group">
                <input type="email" placeholder="Your Email" />
              </div>

              <button class="btn btn-primary btn-login w-full">
                Request password reset
              </button>
              
        
        <div class="login-text-dont-have-account">
             Already have an account? -
                <a href="{{ route('login') }}"> Click here to login </a>
        </div>

    </form>
</x-guest-layout> --}}

<x-guest-layout title="Login | Car Findal Service" class="page-signup">
    <h1 class="auth-page-title">Request Password Reset</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="form-group">
            <input 
                type="email" 
                name="email"
                placeholder="Your Email" 
                value="{{ old('email') }}"
                required
                class="form-control"
            />

            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-login w-full">
            Request Password Reset
        </button>

        <div class="login-text-dont-have-account">
            Already have an account? -
            <a href="{{ route('login') }}">Click here to login</a>
        </div>
    </form>
</x-guest-layout>