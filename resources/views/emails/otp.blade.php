<!DOCTYPE html>
<html>
<head>
    <title>Singup OTP #{{$otp}}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; margin: 0; padding: 16px; background-color: #F7F9FC;">
  
  <div style="width: 100%; max-width: 560px; margin: 0 auto;">

      <header style="padding: 24px;">
        <div style="display: inline-flex; align-items: center; gap: 4px;">
          <img src="https://amazonsoftwares.com/wp-content/uploads/2022/11/azlogo.png" alt="Terratone" style="height: 36px;">
          <span style="font-size: 24px; font-weight: 700; marign: 0;"></span>
        </div>
      </header>
    
        <div style="margin: 0; background-color: #FFF; padding: 32px;">
          <h1 style="color: #233259; font-size: 28px; font-weight: 600;">Verify Singup</h1>
          <span style="color: #233259; font-size: 18px; display: inline-block; padding: 16px 20px; background: #F2F3F5; font-size: 28px; font-weight: 600;">{{$otp}}</span>
          <p style="color: #233259; font-size: 18px;">Copy and paste the code to complete the Singup.</p>
        </div>
    
      <footer style="margin: 32px 0; text-align: center; color: #5A6582; font-size: 16px;">
        <p>We’re here to help if you need it. Contact us at <a href="mailto:support@terradots.com" style="color: inherit;">support@gmail.com</a>.</p>
        <p>&copy; {{ date('Y') }} {{projectname()}}</p>
      </footer>
    
  </div>
    
</body>
</html>