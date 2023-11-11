<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feather.css') }}">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/imgs/huet_logo.png') }}">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app_custom.css') }}">

    {{--  Style for page  --}}
    @yield('style_page')

    {{--  Script custom --}}
    <script type="text/javascript" src="{{ asset('js/app_custom.js') }}"></script>

    <script>
        function formatRelativeTime(timestamp) {
            const now = new Date();
            const date = new Date(timestamp);
            const secondsAgo = Math.floor((now - date) / 1000);

            if (secondsAgo < 60) {
                return secondsAgo + " seconds ago";
            } else if (secondsAgo < 3600) {
                const minutesAgo = Math.floor(secondsAgo / 60);
                return minutesAgo + " minute" + (minutesAgo > 1 ? "s" : "") + " ago";
            } else if (secondsAgo < 86400) {
                const hoursAgo = Math.floor(secondsAgo / 3600);
                return hoursAgo + " hour" + (hoursAgo > 1 ? "s" : "") + " ago";
            } else {
                const daysAgo = Math.floor(secondsAgo / 86400);
                if (daysAgo >= 7) {
                    return formatDateCreatedAt(timestamp);
                }
                return daysAgo + " day" + (daysAgo > 1 ? "s" : "") + " ago";
            }
        }
    </script>

    @yield('header_js_page')

    <script src="{{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <style>
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1069;
        }
    </style>
</head>

<body class="color-theme-blue">
    {{--    Loaded --}}
    {{--    <div class="preloader"></div> --}}

    <div class="main-wrapper">
        <!--  Header  -->
        @include('front-end.components.header')
        <!--  End Header  -->

        <!--  Sidebar  -->
        @include('front-end.components.home.sidebar')
        <!--  End Sidebar  -->

        {{--  Content  --}}
        @yield('content')
        {{--  End Content  --}}

        <!--  Right Chat  -->
        {{-- @include('front-end.components.right_chat') --}}
        <!--  End Right Chat  -->

        {{-- Footer --}}
        @include('front-end.components.footer')
        {{-- End Footer --}}
    </div>

    {{--  Modal  --}}
    @yield('modal')
    {{--  End Modal  --}}

    @stack('js_page')
    <script>
	    getDepartments()
	    // load departments
	    function getDepartments() {
		    $.ajax({
			    type: "GET",
			    url: "/api/unread-message",
			    headers: {
				    "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
			    },
			    success: function(data) {
				    if (data.meta.success) {
					    $("#countMessage").html(data.data)
					    if(data.data === 0) {
						    $("#haveMessage").css("visibility", "hidden");
                        } else {
						    $("#haveMessage").css("visibility", "visible");
					    }
				    }
			    },
		    });
	    }
    </script>
</body>
