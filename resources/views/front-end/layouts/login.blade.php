<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from uitheme.net/sociala/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Jul 2023 01:21:01 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <link rel="stylesheet" href="{{asset("css/themify-icons.css")}}">
    <link rel="stylesheet" href="{{asset('css/feather.css')}}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body class="color-theme-blue">
<div class="preloader"></div>

<div class="main-wrap">

    <div class="nav-header bg-transparent shadow-none border-0">
        <div class="nav-top w-100">
            <a href={{ route('app.home') }}><i class="feather-zap text-success display1-size me-2 ms-0"></i><span
                    class="d-inline-block fredoka-font ls-3 fw-600 text-current font-xxl logo-text mb-0">Sociala. </span>
            </a>
            <a href="#" class="mob-menu ms-auto me-2 chat-active-btn"><i
                    class="feather-message-circle text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
            <a href="default-video.html" class="mob-menu me-2"><i
                    class="feather-video text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
            <a href="#" class="me-2 menu-search-icon mob-menu"><i
                    class="feather-search text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
            <button class="nav-menu me-0 ms-2"></button>

            <a href="#"
               class="header-btn d-none d-lg-block bg-dark fw-500 text-white font-xsss p-3 ms-auto w100 text-center lh-20 rounded-xl"
               data-bs-toggle="modal" data-bs-target="#Modallogin">Login</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 d-none d-xl-block p-0 vh-100 bg-image-cover bg-no-repeat"
             style="background-image: url(images/login-bg.jpg);"></div>
        <div class="col-xl-7 vh-100 align-items-center d-flex bg-white rounded-3 overflow-hidden">
            <div class="card shadow-none border-0 ms-auto me-auto login-card">
                <div class="card-body rounded-0 text-left">
                    <h2 class="fw-700 display1-size display2-md-size mb-3">Login into <br>your account</h2>
                    <form action="/login" method="POST">
                        @csrf
                        <div class="form-group icon-input mb-3">
                            <i class="font-sm ti-email text-grey-500 pe-0"></i>
                            <input type="text" class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600"
                                   name="email"
                                   placeholder="Your Email Address">
                        </div>
                        <div class="form-group icon-input mb-1">
                            <input type="Password" class="style2-input ps-5 form-control text-grey-900 font-xss ls-3"
                                   name="password"
                                   placeholder="Password">
                            <i class="font-sm ti-lock text-grey-500 pe-0"></i>
                        </div>
                        @if ($errors->any())
                            <div class="alert-danger mt-2" style="background: none; margin-left: 4px; font-size: 14px">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <button
                            type="submit"
                            class="mt-2 form-control text-center style2-input text-white fw-600 bg-dark border-0 p-0 ">
                            Login
                        </button>
                    </form>
                    <div class="col-sm-12 p-0 text-center mt-2">

                        <h6 class="mb-0 d-inline-block bg-white fw-500 font-xsss text-grey-500 mb-3">Or, Sign in with
                            your social account </h6>
                        <div class="form-group mb-1">
                            <a href="{{route('login.google')}}"
                               class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 mb-2">
                                <img src="{{ asset('images/icon-1.png') }}" alt="icon" class="ms-2 w40 mb-1 me-5"> Sign
                                in with Google
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
                        <h2 class="fw-700 display1-size display2-md-size mb-4">Login into <br>your account</h2>
                        <form>

                            <div class="form-group icon-input mb-3">
                                <i class="font-sm ti-email text-grey-500 pe-0"></i>
                                <input type="text" class="style2-input ps-5 form-control text-grey-900 font-xsss fw-600"
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
                                Login
                            </button>
                        </form>
                        <div class="col-sm-12 p-0 text-center mt-3 ">

                            <h6 class="mb-0 d-inline-block bg-white fw-600 font-xsss text-grey-500 mb-4">Or, Sign in
                                with your social account </h6>
                            <div class="form-group mb-1">
                                <a href="#"
                                   class="form-control text-left style2-input text-white fw-600 bg-facebook border-0 p-0 mb-2"><img
                                        src="{{ asset('images/icon-1.png') }}" alt="icon" class="ms-2 w40 mb-1 me-5">
                                    Sign in with Google</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src=" {{ asset('js/plugin.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
