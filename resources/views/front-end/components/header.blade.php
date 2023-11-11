<div id="loadingSpinner" class="loading-spinner d-none">
    <div class="d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </div>
</div>

<div class="nav-header bg-white shadow-xs border-0" style="width: 100%; display: flex; justify-content: space-between;">
    <div class="nav-top">
        <a href="/home">
            <img src="{{ asset('assets/imgs/huet_logo.png') }}" alt="uet_logo"
                style="height: 56px; width: 56px; object-fit: cover;">
        </a>
    </div>

    <form action="/search" class="float-left header-search" id="headerSearchForm" autocomplete="off">
        <div class="form-group mb-0 icon-input">
            <i class="feather-search font-sm text-grey-400"></i>
            <input type="text" placeholder="{{ __('texts.texts.search_something.' . auth()->user()->lang) }}"
                id="headerSearchKeyword" required name="search"
                class="bg-grey border-0 lh-32 pt-2 pb-2 ps-5 pe-3 font-xssss fw-500 rounded-xl w350 theme-dark-bg">
        </div>
    </form>
    {{--    <a href="default.html" class="p-2 text-center ms-3 menu-icon center-menu-icon"><i class="feather-home font-lg alert-primary btn-round-lg theme-dark-bg text-current "></i></a> --}}
    {{--    <a href="default-storie.html" class="p-2 text-center ms-0 menu-icon center-menu-icon"><i class="feather-zap font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500 "></i></a> --}}
    {{--    <a href="default-video.html" class="p-2 text-center ms-0 menu-icon center-menu-icon"><i class="feather-video font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500 "></i></a> --}}
    {{--    <a href="default-group.html" class="p-2 text-center ms-0 menu-icon center-menu-icon"><i class="feather-user font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500 "></i></a> --}}
    {{--    <a href="shop-2.html" class="p-2 text-center ms-0 menu-icon center-menu-icon"><i class="feather-shopping-bag font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500 "></i></a> --}}

    <div style="display: flex; align-items: center;">
        <span class="p-2 text-center menu-icon" id="dropDownLanguages" data-bs-toggle="dropdown" aria-expanded="false">
            @if (auth()->user()->lang == 'vi')
                <img src="{{ asset('assets/imgs/vietnam_flag.png') }}" alt="vietnam_flag"
                    style="height: 32px; width: 32px; border-radius: 100%; object-fit: cover;">
            @else
                <img src="{{ asset('assets/imgs/england_flag.png') }}" alt="england_flag"
                    style="height: 32px; width: 32px; border-radius: 100%; object-fit: cover;">
            @endif

        </span>
        <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg"
            aria-labelledby="dropDownLanguages">
            @include('front-end.components.header.languages')
        </div>

        <span class="p-2 ms-3 text-center menu-icon" id="dropdownNotifications" data-bs-toggle="dropdown"
            aria-expanded="false">
            <span class="dot-count bg-warning d-none" id="headerHasNotificationDot"></span>
            <i class="feather-bell font-xl text-current" id="dropdownNotificationsI"></i>
        </span>
        <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg" id="headerNotificationsWrapper"
            aria-labelledby="dropdownNotifications">
            @include('front-end.components.header.notifications')
        </div>

        <a href="{{ route('chat') }}" class="p-2 text-center ms-3 menu-icon chat-active-btn"><i
                class="feather-message-square font-xl text-current"></i></a>

        {{-- settings here if needed --}}
        {{-- @include('front-end.components.header.settings') --}}

        <span class="p-0 ms-3 menu-icon" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
            <img id="headerUserAvatar" src="{{ auth()->user()->avatar }}" alt="user" class="mt--1 border"
                style="border-radius: 100%; object-fit: cover; width: 40px; height: 40px;">
        </span>
        <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg" aria-labelledby="dropdownProfile">
            @include('front-end.components.header.profile')
        </div>
    </div>
</div>
