<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('texts.texts.login') }}</title>

    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feather.css') }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/imgs/huet_logo.png') }}">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="color-theme-blue">
    <div class="preloader"></div>

    <div class="main-wrap">

        <div class="nav-header bg-transparent shadow-none border-0">
            <div class="nav-top w-100">
                <a href={{ route('app.home') }}>
                    <img src="{{ asset('assets/imgs/huet_logo.png') }}" alt="uet_logo"
                        style="height: 80px; width: 80px; object-fit: cover;">
                </a>

                <a href="/login"
                    class="header-btn d-none d-lg-block bg-dark fw-500 text-white font-xsss p-3 ms-auto w100 text-center lh-20 rounded-xl">
                    {{ __('texts.texts.login.' . auth()->user()->lang) }}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-5 d-none d-xl-block p-0 vh-100 bg-image-cover bg-no-repeat"
                style="background-image: url(images/login-bg.jpg);"></div>
            <div class="col-xl-7 vh-100 align-items-center d-flex bg-white rounded-3 overflow-hidden">
                <div class="card shadow-none border-0 ms-auto me-auto login-card">
                    <div class="card-body rounded-0 text-left">
                        <h2 class="fw-700 display1-size display2-md-size mb-3">
                            {{ __('texts.texts.login_to_account.' . auth()->user()->lang) }}
                        </h2>
                        <form action="/login" method="POST" id="loginForm">
                            @csrf
                            <div class="form-group icon-input mb-3">
                                <i class="font-sm ti-email text-grey-500 pe-0"></i>
                                <input type="text" id="emailInput"
                                    class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600" name="email"
                                    placeholder="Your Email Address">
                            </div>
                            <div class="form-group icon-input mb-1">
                                <input type="Password" id="passwordInput"
                                    class="style2-input ps-5 form-control text-grey-900 font-xss ls-3" name="password"
                                    placeholder="Password">
                                <i class="font-sm ti-lock text-grey-500 pe-0"></i>
                            </div>
                            @if ($errors->any())
                                <div class="alert-danger mt-2"
                                    style="background: none; margin-left: 4px; font-size: 14px">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            @if ($error != 'error')
                                                <li>
                                                    {{ __($error) }}
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <button type="button" id="loginButton"
                                class="mt-2 form-control text-center style2-input text-white fw-600 bg-dark border-0 p-0 ">
                                {{ __('texts.texts.login.' . auth()->user()->lang) }}
                            </button>
                        </form>
                        <div class="col-sm-12 p-0 text-center mt-2">

                            <h6 class="mb-0 d-inline-block bg-white fw-500 font-xsss text-grey-500 mb-3">
                                {{ __('texts.texts.sign_in_with_social_account.' . auth()->user()->lang) }}

                            </h6>
                            <div class="form-group mb-1">
                                <a href="{{ route('login.google') }}"
                                    class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 mb-2">
                                    <img src="{{ asset('images/icon-1.png') }}" alt="icon"
                                        class="ms-2 w40 mb-1 me-5">
                                    {{ __('texts.texts.sign_in_with_google.' . auth()->user()->lang) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal bottom fade" style="overflow-y: scroll;" id="Modallogin" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i
                        class="ti-close text-grey-500"></i></button>
                <div class="modal-body p-3 d-flex align-items-center bg-none">
                    <div class="card shadow-none rounded-0 w-100 p-2 pt-3 border-0">
                        <div class="card-body rounded-0 text-left p-3">
                            <h2 class="fw-700 display1-size display2-md-size mb-4">
                                {{ __('texts.texts.login_to_account') }}
                            </h2>
                            <form>

                                <div class="form-group icon-input mb-3">
                                    <i class="font-sm ti-email text-grey-500 pe-0"></i>
                                    <input type="text"
                                        class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600"
                                        placeholder="Your Email Address">
                                </div>
                                <div class="form-group icon-input mb-1">
                                    <input type="Password"
                                        class="style2-input ps-5 form-control text-grey-900 font-xss ls-3"
                                        placeholder="Password">
                                    <i class="font-sm ti-lock text-grey-500 pe-0"></i>
                                </div>
                                <button
                                    class="mt-2 form-control text-center style2-input text-white fw-600 bg-dark border-0 p-0 ">
                                    {{ __('texts.texts.login') }}
                                </button>
                            </form>
                            <div class="col-sm-12 p-0 text-center mt-3 ">

                                <h6 class="mb-0 d-inline-block bg-white fw-600 font-xsss text-grey-500 mb-4">
                                    {{ __('texts.texts.sign_in_with_social_account') }}
                                </h6>
                                <div class="form-group mb-1">
                                    <a href="#"
                                        class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 mb-2"><img
                                            src="{{ asset('images/icon-1.png') }}" alt="icon"
                                            class="ms-2 w40 mb-1 me-5">
                                        {{ __('texts.texts.sign_in_with_google') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src=" {{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script>
        let loginForm = document.getElementById("loginForm");
        let loginButton = document.getElementById("loginButton");

        loginButton.addEventListener("click", e => {
            e.preventDefault();

            let formData = "email=" + document.getElementById("emailInput").value + "&password=" + document
                .getElementById("passwordInput").value;
            $.ajax({
                url: "/api/login",
                type: "POST",
                data: formData,
                success: function(result) {
                    if (result.meta.success) {
                        localStorage.setItem("jwtToken", result.data.authorization.token);
                        localStorage.setItem("user", JSON.stringify(result.data.user));
                    }
                    loginForm.submit();
                },
                error: function() {
                    loginForm.submit();
                },
            });
        });
    </script>
</body>

</html>
