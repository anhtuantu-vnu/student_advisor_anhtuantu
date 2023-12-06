<nav class="navigation scroll-bar">
    <div class="container ps-0 pe-0">
        <div class="nav-content">
            @if (auth()->user()->role != 'admin')
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
                            <a href="{{ route('event.get.lastest') }}" class="nav-content-bttn open-font">
                                <i class="font-xl text-current feather-map-pin me-3"></i>
                                <span>{{ __('texts.texts.latest_events.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
            <div
                class="nav-wrap bg-white bg-transparent-card rounded-xxl shadow-xss pt-3 pb-1 <?php if(auth()->user()->role == 'admin') { ?>mt-2<?php } ?>">
                <div class="nav-caption fw-600 font-xssss text-grey-500">
                    <span>{{ __('texts.texts.account.' . auth()->user()->lang) }}</span>
                </div>
                <ul class="mb-1">
                    <li class="logo d-none d-xl-block d-lg-block"></li>
                    @if (auth()->user()->role === 'admin')
                        <li>
                            <a href="/admin-dashboard" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-monitor me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.dashboard.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/users?type=teacher" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-users me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.teachers.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/users?type=student" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-user-check me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.students.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/departments" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-home me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.departments.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/subjects" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-hash me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.subjects.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/classes" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-tag me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.classes.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/intakes" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-bookmark me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.intakes.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/admin/notifications" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-bell me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.notifications.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('view_import') }}" class="nav-content-bttn open-font h-auto pt-2 pb-2">
                                <i class="font-sm feather-settings me-3 text-grey-500"></i>
                                <span>{{ __('texts.texts.import.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role != 'admin')
                        <li>
                            <a href="/system-notifications" class="nav-content-bttn open-font">
                                <i class="font-sm feather-bell me-3 text-grey-500">
                                </i>
                                <span>{{ __('texts.texts.system_notifications.' . auth()->user()->lang) }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="/student-chat" class="nav-content-bttn open-font">
                                <i class="font-sm feather-message-square me-3 text-grey-500">
                                </i>
                                <span>{{ __('texts.texts.chat.' . auth()->user()->lang) }}</span>
                                <span class="circle-count bg-warning mt-1" id = "countMessage">0</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
