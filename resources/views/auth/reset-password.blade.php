<x-guest-layout title="Login | Car Findal Service" class="page-signup">
    <h1 class="auth-page-title">Request Password Reset</h1>

    <form action="" method="POST">
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
</x-guest-layout>
