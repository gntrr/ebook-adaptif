<x-guest-layout>
    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="{{ asset('images/login-assets.png') }}" alt="" style="max-width: 572px">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <a href="index.html" class="auth-right__logo">
                    <img src="{{ asset('images/logo-black.png') }}" alt="" style="max-width: 160px">
                </a>
                <h2 class="mb-8">Sign Up</h2>
                <p class="text-gray-600 text-15 mb-32">Please sign up to your account and start the adventure</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-24">
                        <label for="name" class="form-label mb-8 h6"> Name</label>
                        <div class="position-relative">
                            <input type="text" class="form-control py-11 ps-40" id="name" placeholder="Type your name" name="name" required autofocus value="{{ old('name') }}">
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-user"></i></span>
                        </div>
                        @if ($errors->has('name'))
                            <span class="text-sm text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    {{-- Email Address --}}
                    <div class="mb-24">
                        <label for="email" class="form-label mb-8 h6">Email </label>
                        <div class="position-relative">
                            <input type="email" class="form-control py-11 ps-40" id="email" placeholder="Type your email address" name="email" required value="{{ old('email') }}">
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-envelope"></i></span>
                        </div>
                        @if ($errors->has('email'))
                            <span class="text-sm text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div class="mb-24">
                        <label for="current-password" class="form-label mb-8 h6">New Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control py-11 ps-40" id="current-password" placeholder="Enter New Password" name="password" required>
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#current-password"></span>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-lock"></i></span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="text-sm text-danger">{{ $errors->first('password') }}</span>
                        @else
                            <span class="text-gray-900 text-15 mt-4">Must be at least 8 characters</span>
                        @endif
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-32">
                        <label for="password_confirmation" class="form-label mb-8 h6">Confirm New Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control py-11 ps-40" id="password_confirmation" placeholder="Confirm New Password" name="password_confirmation" required>
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#password_confirmation"></span>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex"><i class="ph ph-lock"></i></span>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-sm text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-main rounded-pill w-100">Sign Up</button>
                    <p class="mt-32 text-gray-600 text-center">Already have an account?
                        <a href="{{ route('login') }}" class="text-main-600 hover-text-decoration-underline"> Log In</a>
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
