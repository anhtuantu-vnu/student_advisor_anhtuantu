<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('texts.texts.login.' . $lang) }}</title>

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

                <a href="#"
                    class="d-none d-lg-block fw-500 text-white font-xsss p-3 ms-auto w100 text-center lh-20 rounded-xl"
                    id="dropDownLanguages" data-bs-toggle="dropdown" aria-expanded="false">
                    @if ($lang == 'vi')
                        <img src="{{ asset('assets/imgs/vietnam_flag.png') }}" alt="vietnam_flag"
                            style="height: 32px; width: 32px; border-radius: 100%; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/imgs/england_flag.png') }}" alt="england_flag"
                            style="height: 32px; width: 32px; border-radius: 100%; object-fit: cover;">
                    @endif

                </a>
                <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg"
                    aria-labelledby="dropDownLanguages">
                    @include('front-end.components.header.languages_login')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-5 d-none d-xl-block p-0 vh-100 bg-image-cover bg-no-repeat"
                style="background-image: url(images/login-bg.jpg);"></div>
            <div class="col-xl-7 vh-100 align-items-center d-flex bg-white rounded-3 overflow-hidden">
                <div class="card shadow-none border-0 ms-auto me-auto login-card">
                    <div class="card-body rounded-0 text-left">
                        <h2 class="fw-700 display1-size display2-md-size mb-3">
                            {{ __('texts.texts.login_to_account.' . $lang) }}
                        </h2>
                        <form action="/login" method="POST" id="loginForm">
                            @csrf
                            <div class="form-group icon-input mb-3">
                                <i class="font-sm ti-email text-grey-500 pe-0"></i>
                                <input type="text" id="emailInput"
                                    class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600" name="email"
                                    placeholder="{{ __('texts.texts.email.' . $lang) }}">
                            </div>
                            <div class="form-group icon-input mb-1">
                                <input type="Password" id="passwordInput"
                                    class="style2-input ps-5 form-control text-grey-900 font-xss ls-3" name="password"
                                    placeholder="{{ __('texts.texts.password.' . $lang) }}">
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
                                {{ __('texts.texts.login.' . $lang) }}
                            </button>
                        </form>
                        <div class="col-sm-12 p-0 text-center mt-2">

                            <h6 class="mb-0 d-inline-block bg-white fw-500 font-xsss text-grey-500 mb-3">
                                {{ __('texts.texts.sign_in_with_social_account.' . $lang) }}

                            </h6>
                            <div class="form-group mb-1">
                                <a href="{{ route('login.google') }}"
                                    class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 mb-2">
                                    <img src="{{ asset('images/icon-1.png') }}" alt="icon"
                                        class="ms-2 w40 mb-1 me-5">
                                    {{ __('texts.texts.sign_in_with_google.' . $lang) }}
                                </a>
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
        // Loading Box (Preloader)
        function handlePreloader() {
            if ($('.preloader').length > 0) {
                $('.preloader').delay(200).fadeOut(500);
            }
        }

        function PageLoad() {
            $(window).on("load", function() {
                setInterval(function() {
                    $('.preloader-wrap').fadeOut(300);
                }, 400);
                setInterval(function() {
                    $('body').addClass('loaded');
                }, 600);
            });
        }


        handlePreloader();
        PageLoad();
    </script>

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
