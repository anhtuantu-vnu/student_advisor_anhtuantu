<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from uitheme.net/sociala/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Jul 2023 01:21:01 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Elomoas - Online Course and LMS HTML Template</title>
    <!--    <link rel="stylesheet" href="css/themify-icons.css">-->
    <!--    <link rel="stylesheet" href="css/feather.css">-->
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="color-theme-blue">
    {{--    Loaded--}}
    <div class="preloader"></div>

    <div class="main-wrapper">
        <!--  Header  -->
        @include('font-end.layout.header')
        <!--  End Header  -->

        <!--  Sidebar  -->
        @include('font-end.layout.sidebar')
        <!--  Sidebar  -->

        <!--  Right Chat  -->
        @include('font-end.layout.right_chat')
        <!--  Right Chat  -->

        {{-- Footer--}}
        @include('font-end.layout.footer');
        {{-- End Footer--}}
    </div>

</body>
