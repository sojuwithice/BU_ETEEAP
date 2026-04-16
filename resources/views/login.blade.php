<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BU ETEEAP Login/Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE') }}"></script>
</head>
<body>

<div class="container 
  @if($errors->any()) no-animation @endif
  @if($errors->signup->any()) right-panel-active @endif
" id="container">

  <div class="form-container sign-up-container">
  <form method="POST" action="{{ route('register.post') }}">
    @csrf

    <h1 class="form-title">SIGN UP</h1>

    <div class="role-selection">
      <label class="role-item">
        <input type="radio" name="role" value="student" checked>
        <div class="role-btn">
          <i class="fa-solid fa-user-graduate"></i>
          <span>Student</span>
        </div>
      </label>
      <label class="role-item">
        <input type="radio" name="role" value="staff">
        <div class="role-btn">
          <i class="fa-solid fa-user-tie"></i>
          <span>Staff</span>
        </div>
      </label>
    </div>
      
    <div class="input-row">
      <div class="input-group">
        <label>First name</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Juan" />
        
        @error('first_name', 'signup')
          <small class="error-text">{{ $message }}</small>
        @enderror
      </div>
      <div class="input-group">
        <label>Last name</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" />

        @error('last_name', 'signup')
          <small class="error-text">{{ $message }}</small>
        @enderror
      </div>
    </div>

    <div class="input-group full-width">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. juandelacruz@gmail.com" />

      @error('email', 'signup')
        <small class="error-text">{{ $message }}</small>
      @enderror
    </div>

    <div class="input-group full-width">
      <label>Password</label>
      <div class="password-wrapper">
        <input type="password" name="password" placeholder="Enter your Password" />
        <i class="fa-solid fa-eye eye-toggle" onclick="togglePassword(this)"></i>
      </div>

      @error('password', 'signup')
        <small class="error-text">{{ $message }}</small>
      @enderror
    </div>

    <div class="input-group full-width">
      <label>Confirm Password</label>
      <div class="password-wrapper">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" />
        <i class="fa-solid fa-eye eye-toggle" onclick="togglePassword(this)"></i>
      </div>

      @error('password_confirmation', 'signup')
        <small class="error-text">{{ $message }}</small>
      @enderror
    </div>

    <!-- RECAPTCHA TOKEN -->
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <p class="terms-text">
      By creating an account you agree to BU ETEEAP's 
      <a href="#">Terms of Services</a> and <a href="#">Privacy Policy</a>.
    </p>

    <button type="submit" class="primary-btn">SIGN UP</button>
      
    <p class="switch-field">
      Already have an Account? <span onclick="toggle()">Login here</span>
    </p>
  </form>
</div>

<!-- LOGIN -->
<div class="form-container sign-in-container">
  <form method="POST" action="{{ route('login.post') }}">
    @csrf

    <h1 class="form-title">LOGIN</h1>

    <div class="role-selection">
      <label class="role-item">
        <input type="radio" name="role" value="student" checked>
        <div class="role-btn">
          <i class="fa-solid fa-user-graduate"></i>
          <span>Student</span>
        </div>
      </label>
      <label class="role-item">
        <input type="radio" name="role" value="staff">
        <div class="role-btn">
          <i class="fa-solid fa-user-tie"></i>
          <span>Staff</span>
        </div>
      </label>
    </div>
      
    <div class="input-group full-width">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your Email Account" />

      @error('email', 'login')
        <small class="error-text">{{ $message }}</small>
      @enderror
    </div>

    <div class="input-group full-width">
      <label>Password</label>
      <div class="password-wrapper">
        <input type="password" name="password" placeholder="Enter your Password" />
        <i class="fa-solid fa-eye eye-toggle" onclick="togglePassword(this)"></i>
      </div>

      @error('password', 'login')
        <small class="error-text">{{ $message }}</small>
      @enderror
    </div>

    <div class="form-utils">
      <label class="remember-me">
        <input type="checkbox"> Remember me
      </label>
      <a href="#" class="forgot-pass">Forgot Password?</a>
    </div>

    <button type="submit" class="primary-btn">LOGIN</button>

    <p class="switch-field">
      Don't have an Account? <span onclick="toggle()">Sign up here</span>
    </p>
  </form>
</div>
  <div class="overlay-container">
    <div class="overlay">
      
      <div class="overlay-panel overlay-left">
        <div class="logo-header">
            <img src="{{ asset('images/bu_logo.png') }}" alt="logo">
            <img src="{{ asset('images/eteeap_logo.png') }}" alt="logo">
        </div>
        <div class="text-content">
            <h1>Welcome Back</h1>
            <p>Access your account to continue your application and stay updated with your progress and requirements.</p>
        </div>
      </div>

      <div class="overlay-panel overlay-right">
        <div class="logo-header">
            <img src="{{ asset('images/bu_logo.png') }}" alt="logo">
            <img src="{{ asset('images/eteeap_logo.png') }}" alt="logo">
        </div>
        <div class="text-content">
            <h1>Welcome</h1>
            <p>Create an account to begin your application and explore the opportunities available to you.</p>
        </div>
      </div>

    </div>
  </div>

</div>

<script>
  const container = document.getElementById('container');

  function toggle() {
    @if(!$errors->any())
      container.classList.toggle("right-panel-active");
    @endif
  }

  function togglePassword(icon) {
    const passwordInput = icon.parentElement.querySelector('input');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }

  grecaptcha.ready(function() {
    grecaptcha.execute('{{ env('RECAPTCHA_SITE') }}', {action: 'submit'})
    .then(function(token) {
        document.getElementById('recaptcha_token').value = token;
    });
  });
</script>

</body>
</html>