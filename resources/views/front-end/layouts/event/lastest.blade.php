@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@push('js_page')
    <script>
        let latestEventsContainer = document.getElementById("latest_events-container");
        let loadMoreLatestEvents = document.getElementById("loadMoreLatestEvents");

        let currentLatestEventPage = 1,
            latestEventLimit = 8;
        let latestEvents = [];

        loadMoreLatestEvents.addEventListener("click", e => {
            currentLatestEventPage++;
            getLatestEvents();
        });

        function getLatestEvents() {
            $.ajax({
                type: "GET",
                url: `/events?page=${currentLatestEventPage}&limit=${latestEventLimit}&type=event&active=1`,
                success: function(data) {
                    if (data.meta.success) {
                        latestEvents = latestEvents.concat(data.data.events);
                        if (data.data.events.length < latestEventLimit) {
                            loadMoreLatestEvents.classList.add("d-none");
                        } else {
                            loadMoreLatestEvents.classList.remove("d-none");
                        }

                        populateLatestEvents();
                    }
                },
            });
        }

        getLatestEvents();

        function getEventMonthLatest(event) {
            let eventDate = new Date(event.start_date);
            let monthsArr = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
            return monthsArr[eventDate.getMonth()];
        }

        function getEventDateLatest(event) {
            let eventDate = new Date(event.start_date);
            return eventDate.getDate();
        }

        function populateLatestEvents() {
            latestEvents.forEach(item => {
                if (!item.shown) {
                    item.shown = true;
                    latestEventsContainer.innerHTML += `
                    <div class="col-md-4">
                      <div class="card-body d-flex pt-0 ps-4 pe-4 pb-3 overflow-hidden">
                        <div class="me-2 p-3 rounded-xxl" style="background-color: ${item.color};">
                          <h4 class="fw-700 font-lg ls-3 lh-1 text-white mb-0">
                            <span class="ls-1 d-block font-xsss text-white fw-600 text-uppercase">
                              ${getEventMonthLatest(item)}
                            </span>${getEventDateLatest(item)}
                          </h4>
                        </div>
                        <a href="/events/${item.uuid}">
                          <div>
                            <h4 class="fw-700 text-grey-900 font-xssss mt-2 ${item.active == 0 ? 'text-decoration-line-through': ''}">
                              ${item.name}
                              <span class="d-block font-xsssss fw-500 mt-1 lh-4 text-grey-500">
                                ${item.location}
                              </span>
                            </h4>
                          </div>
                        </a>
                      </div>
                    </div>
                    `;
                }
            });
        }
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <h1 class="fw-700 mb-0 mt-0 text-grey-900" style="font-size: 34px !important;">
                            {{ __('texts.texts.latest_events.' . auth()->user()->lang) }}
                        </h1>

                        <div class="mt-3">
                            <div class="row" id="latest_events-container">
                            </div>
                            <div class="mt-2">
                                <span class="text-primary text-decoration-underline" id="loadMoreLatestEvents">
                                    <small class="cursor-pointer">
                                        {{ __('texts.texts.load_more.' . auth()->user()->lang) }}
                                    </small>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
