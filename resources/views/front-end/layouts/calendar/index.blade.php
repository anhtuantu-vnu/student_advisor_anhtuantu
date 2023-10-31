@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />
@endsection

@push('js_page')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        let eventsData;
        const eventColors = [
            '#4285F4', // Blue
            '#0F9D58', // Green
            '#F4B400', // Yellow
            '#DB4437', // Red
            '#673AB7', // Purple
            '#00ACC1', // Teal
            '#009688', // Green
            '#FF5722', // Deep Orange
            '#795548', // Brown
            '#827717' // Lime
        ];

        function getRandomEventColor() {
            const randomIndex = Math.floor(Math.random() * eventColors.length);
            return eventColors[randomIndex];
        }


        document.addEventListener('DOMContentLoaded', function() {
            function getCalendarEvents() {
                $.ajax({
                    url: "/api/student-intakes",
                    type: "GET",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
                    },
                    success: function(result) {
                        console.log('result', result)
                        if (result.meta.success) {
                            eventsData = result.data;
                        }
                        initCalendar();
                    }
                });
            }

            getCalendarEvents();

            function addPreFixHourMinute(input) {
                if (input < 10) {
                    return '0' + input;
                }
                return input;
            }

            function formatGivenDate(givenDate) {
                return givenDate.toISOString().split('T')[0];
            }

            function processEvents() {
                let res = [];
                let intakeColorMap = {};
                if (eventsData.intakeMembers) {
                    if (eventsData.intakeMembers.length) {
                        eventsData.intakeMembers.map(item => {
                            if (item.intake) {
                                if (!intakeColorMap[item.intake.uuid]) {
                                    intakeColorMap[item.intake.uuid] = getRandomEventColor();
                                }
                                var givenDateString = item.intake.end_date;
                                var givenDate = new Date(givenDateString);
                                var today = new Date();

                                if (givenDate > today) {
                                    let weekDays = item.intake.week_days.split(",");
                                    let currentDate = new Date(item.intake.start_date);

                                    while (currentDate <= givenDate) {
                                        if (weekDays.indexOf(`${currentDate.getDay() + 1}`) != -1) {
                                            res.push({
                                                id: item.uuid,
                                                title: item.intake.code,
                                                start: formatGivenDate(currentDate) + 'T' +
                                                    addPreFixHourMinute(item.intake
                                                        .start_hour) + ":" +
                                                    addPreFixHourMinute(
                                                        item.intake.start_minute) + ":00+07:00",
                                                startStr: addPreFixHourMinute(item.intake
                                                        .start_hour) + ":" +
                                                    addPreFixHourMinute(
                                                        item.intake.start_minute),
                                                end: formatGivenDate(currentDate) + 'T' +
                                                    addPreFixHourMinute(item.intake
                                                        .end_hour) + ":" + addPreFixHourMinute(
                                                        item.intake.end_minute) + ":00+07:00",
                                                endStr: addPreFixHourMinute(item.intake
                                                        .end_hour) + ":" +
                                                    addPreFixHourMinute(
                                                        item.intake.end_minute),
                                                display: "block",
                                                backgroundColor: intakeColorMap[item.intake.uuid],
                                                extendedProps: {
                                                    intake: item.intake,
                                                    subject: item.intake.subject
                                                }
                                            });
                                        }
                                        currentDate.setDate(currentDate.getDate() + 1);
                                    }
                                }
                            }
                        });
                    }
                }

                console.log("res", res);
                console.log("eventsData", eventsData);
                return res;
            }

            function initCalendar() {
                let events = processEvents();
                var calendarEl = document.getElementById('huet_calendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    events: [...events],
                    eventClick: function(info) {
                        console.log("info", info);
                    }
                });

                calendar.setOption('locale', 'vn');
                calendar.on('dateClick', function(info) {
                    console.log('info', info);
                    console.log('clicked on ' + info.dateStr);
                });
                console.log('calendar', calendar);
                calendar.render();
            }
        });
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white">
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ __('texts.texts.events.' . auth()->user()->lang) }}
                            </h1>
                            <div>
                                <div class="response"></div>
                                <div id='huet_calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
