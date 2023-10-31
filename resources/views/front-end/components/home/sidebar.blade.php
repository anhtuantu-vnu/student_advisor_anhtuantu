<nav class="navigation scroll-bar">
    <div class="container ps-0 pe-0">
        <div class="nav-content">
            <div class="nav-wrap bg-white bg-transparent-card rounded-xxl shadow-xss pt-3 pb-1 mb-2 mt-2">
                <div class="nav-caption fw-600 font-xssss text-grey-500">
                    <span>{{ __('texts.texts.news_feeds.' . auth()->user()->lang) }}</span>
                </div>
                <ul class="mb-1 top-content">
                    <li class="logo d-none d-xl-block d-lg-block"></li>
                    <li>
                        <a href="{{ route('app.home') }}" class="nav-content-bttn open-font">
                            <i class="feather-home btn-round-md bg-blue-gradiant me-3"></i>
                            <span>{{ __('texts.texts.home.' . auth()->user()->lang) }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('plan') }}" class="nav-content-bttn open-font">
                            <i class="feather-award btn-round-md bg-red-gradiant me-3"></i>
                            <span>{{ __('texts.texts.plans.' . auth()->user()->lang) }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('calendar') }}" class="nav-content-bttn open-font">
                            <i class="feather-calendar btn-round-md bg-gold-gradiant me-3"></i>
                            <span>{{ __('texts.texts.calendar.' . auth()->user()->lang) }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-wrap bg-white bg-transparent-card rounded-xxl shadow-xss pt-3 pb-1 mb-2">
                <div class="nav-caption fw-600 font-xssss text-grey-500">
                    <span>{{ __('texts.texts.quick_access.' . auth()->user()->lang) }}</span>
                </div>
                <ul class="mb-3">
                    <li>
                        <a href="default-hotel.html" class="nav-content-bttn open-font">
                            <i class="font-xl text-current feather-map-pin me-3"></i>
                            <span>{{ __('texts.texts.latest_events.' . auth()->user()->lang) }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-wrap bg-white bg-transparent-card rounded-xxl shadow-xss pt-3 pb-1">
                <div class="nav-caption fw-600 font-xssss text-grey-500">
                    <span>{{ __('texts.texts.account.' . auth()->user()->lang) }}</span>
                </div>
                <ul class="mb-1">
                    <li class="logo d-none d-xl-block d-lg-block"></li>
                    <li>
                        <a href="default-settings.html" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                            <i class="font-sm feather-settings me-3 text-grey-500"></i>
                            <span>{{ __('texts.texts.settings.' . auth()->user()->lang) }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="default-email-box.html" class="nav-content-bttn open-font">
                            <i class="font-sm feather-message-square me-3 text-grey-500">
                            </i>
                            <span>{{ __('texts.texts.chat.' . auth()->user()->lang) }}</span>
                            <span class="circle-count bg-warning mt-1">584</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
