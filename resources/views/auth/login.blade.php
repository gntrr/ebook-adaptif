<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="{{ asset('images/login-assets.png') }}" alt="" style="max-width: 572px">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <a href="index.html" class="auth-right__logo">
                    <img src="{{ asset('images/logo-black.png') }}" alt="" style="max-width: 160px">
                </a>
                <h2 class="mb-8">Welcome to Back! <img src="{{ asset('edmate/assets/images/icons/wave-hand.png') }}" alt=""></h2>
                <p class="text-gray-600 text-15 mb-32">Please sign in to your account and start the adventure</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-24">
                        <label for="fname" class="form-label mb-8 h6">Email</label>
                        <div class="position-relative">
                            <input type="text" class="form-control py-11 ps-40" id="fname" placeholder="Type your email" name="email" required autofocus value="{{ old('email') }}">
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-user"></i></span>
                        </div>
                        @if ($errors->has('email'))
                            <span class="text-sm text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="mb-24">
                        <label for="current-password" class="form-label mb-8 h6">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control py-11 ps-40" id="current-password" placeholder="Enter Password" name="password" required>
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#current-password"></span>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-lock"></i></span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="text-sm text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="mb-32 flex-between flex-wrap gap-8">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input flex-shrink-0 rounded-4" type="checkbox" value="" id="remember">
                            <label class="form-check-label text-15 flex-grow-1" for="remember">Remember Me </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-main-600 hover-text-decoration-underline text-15 fw-medium">Forgot Password?</a>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-main rounded-pill w-100">Sign In</button>
                    <p class="mt-32 text-gray-600 text-center">New on our platform?
                        <a href="{{ route('register') }}" class="text-main-600 hover-text-decoration-underline">Create an account</a>
                    </p>

                    <div class="divider my-32 position-relative text-center">
                        <span class="divider__text text-gray-600 text-13 fw-medium px-26 bg-white">or</span>
                    </div>

                    <ul class="flex-align gap-10 flex-wrap justify-content-center">
                        <li>
                            <a href="https://www.facebook.com" class="w-38 h-38 flex-center rounded-6 text-facebook-600 bg-facebook-50 hover-bg-facebook-600 hover-text-white text-lg">
                                <i class="ph-fill ph-facebook-logo"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com" class="w-38 h-38 flex-center rounded-6 text-twitter-600 bg-twitter-50 hover-bg-twitter-600 hover-text-white text-lg">
                                <i class="ph-fill ph-twitter-logo"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.google.com" class="w-38 h-38 flex-center rounded-6 text-google-600 bg-google-50 hover-bg-google-600 hover-text-white text-lg">
                                <i class="ph ph-google-logo"></i>
                            </a>
                        </li>
                    </ul>
                    
                </form>
            </div>
        </div>
    </section>

</x-guest-layout>
