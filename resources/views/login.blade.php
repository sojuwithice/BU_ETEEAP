<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BU ETEEAP Login/Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container" id="container">

  <div class="form-container sign-up-container">
    <form action="#">
      <h1 class="form-title">SIGN UP</h1>
      
      <div class="input-row">
        <div class="input-group">
          <label>First name</label>
          <input type="text" placeholder="e.g. Juan" />
        </div>
        <div class="input-group">
          <label>Last name</label>
          <input type="text" placeholder="e.g. Dela Cruz" />
        </div>
      </div>

      <div class="input-group full-width">
        <label>Email</label>
        <input type="email" placeholder="e.g. juandelacruz@gmail.com" />
      </div>

      <div class="input-group full-width">
        <label>Password</label>
        <div class="password-wrapper">
          <input type="password" placeholder="Enter your Password" />
          <i class="fa-solid fa-eye eye-toggle" onclick="togglePassword(this)"></i>
        </div>
      </div>

      <div class="input-group full-width">
        <label>Confirm Password</label>
        <div class="password-wrapper">
          <input type="password" placeholder="Enter your Password" />
          <i class="fa-solid fa-eye eye-toggle" onclick="togglePassword(this)"></i>
        </div>
      </div>

      <p class="terms-text">
        By creating an account you agree to BU ETEEAP's 
        <a href="#">Terms of Services</a> and <a href="#">Privacy Policy</a>.
      </p>

      <button type="button" class="primary-btn">SIGN UP</button>
      
      <p class="switch-field">
        Already have an Account? <span onclick="toggle()">Login here</span>
      </p>
    </form>
  </div>

  <div class="form-container sign-in-container">
    <form action="#">
      <h1 class="form-title">LOGIN</h1>
      
      <div class="input-group full-width">
        <label>Email</label>
        <input type="email" placeholder="Enter your Email Account" />
      </div>

      <div class="input-group full-width">
        <label>Password</label>
        <div class="password-wrapper">
          <input type="password" placeholder="Enter your Password" />
          <i class="fa-solid fa-eye eye-toggle" onclick="togglePassword(this)"></i>
        </div>
      </div>

      <div class="form-utils">
        <label class="remember-me">
          <input type="checkbox"> Remember me
        </label>
        <a href="#" class="forgot-pass">Forgot Password?</a>
      </div>

      <button type="button" class="primary-btn">LOGIN</button>

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
  
  // Eto yung function para sa manual toggle (yung "Login here" / "Sign up here" links)
  function toggle() {
    container.classList.toggle("right-panel-active");
  }

  // Eto yung logic para sa "Apply Now" galing sa Landing Page
  window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const mode = urlParams.get('mode');
    
    // Kung ang URL ay may ?mode=signup, i-trigger ang slide pakanan
    if (mode === 'signup' && container) {
      container.classList.add("right-panel-active");
    }
  };

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
</script>

</body>
</html>