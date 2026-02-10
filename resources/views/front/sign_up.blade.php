
@include('front.layout.head')

<body>
    <div class="sign-inup">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="sign-form">
                        <div class="sign-inner">
                           
                            <div class="form-dt">
                                <div class="form-inpts checout-address-step">
                                    <form action="{{ route('user.register') }}" method="POST">
                                        @csrf
                                        <div class="form-title">
                                            <h6>Sign Up</h6>
                                        </div>
                                        <div class="form-group pos_rel">
                                            <input id="" name="name" type="text" placeholder="Full Name"
                                                class="form-control lgn_input" required="">
                                            <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                            @error('name')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group pos_rel">
                                            <input id="" name="email" type="text"
                                                placeholder="Email Address" class="form-control lgn_input" required="">
                                            <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                            @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group pos_rel">
                                            <input id="" name="mobile" type="text"
                                                placeholder="Phone Number" class="form-control lgn_input" required="">
                                            <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                            @error('mobile')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group pos_rel">
                                            <input id="password1" name="password" type="password"
                                                placeholder="Create Password" value=""  class="form-control lgn_input"
                                                required="">
                                            <i class="uil uil-padlock lgn_icon"></i>
                                            @error('password')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <button class="login-btn hover-btn" type="submit">Sign Up Now</button>
                                    </form>
                                </div>

                                <div class="signup-link">
                                    <p>I have an account? - <a href="{{ route('user.login') }}">Sign In Now</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="copyright-text text-center mt-3">
                        <i class="uil uil-copyright"></i>Copyright {{date('Y')}} <b>Telemedicine</b>. All rights reserved
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="{{ asset('front/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/vendor/OwlCarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('front/vendor/semantic/semantic.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('front/js/custom.js') }}"></script>
    <script src="{{ asset('front/js/product.thumbnail.slider.js') }}"></script>
    <script src="{{ asset('front/js/offset_overlay.js') }}"></script>
    <script src="{{ asset('front/js/night-mode.js') }}"></script>
</body>

</html>