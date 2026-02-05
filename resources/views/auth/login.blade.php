<x-guest-layout title="Login | Car Findal Service" class="page-signup">
  <h1 class="auth-page-title">Login</h1>
  
  <!-- Display errors -->
  <x-error-message/>    


    <form action="{{ route('postLogin') }}" method="POST">
      
        @csrf

        <div class="form-group">
          <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Your Email"
            required
            class="form-input w-full"
          >
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Your Password" />
        </div>
        <div class="flex justify-between items-center mb-medium">
                <!-- Left: Remember Me Checkbox -->
          <div>
            <label class="flex items-center gap-2 cursor-pointer">
              <input 
                type="checkbox" 
                name="remember" 
                id="remember" 
                class="form-checkbox"
                value="1"
                {{ old('remember') ? 'checked' : '' }}
              >
              <span>Remember me</span>
            </label>
          </div>


                <!-- Right: Reset Password Link -->
          <div>
            <a href="{{ route('reset-password') }}" class="auth-page-password-reset">
              Reset Password
            </a>
          </div>
        </div>


        <button class="btn btn-primary btn-login w-full">Login</button>

        <div class="grid grid-cols-2 gap-1 social-auth-buttons">
            <x-facebook-btn/>
            <x-google-btn/>
        </div>

        <div class="login-text-dont-have-account">
            Don't have an account? -
            <a href="{{ route('signup') }}">  Click here to create one </a>
        </div>

    </form>
</x-guest-layout>
