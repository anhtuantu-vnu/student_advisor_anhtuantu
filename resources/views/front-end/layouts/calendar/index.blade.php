@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />

    <style>
        a.text-decoration-line-through div {
            text-decoration: line-through;
        }
    </style>
@endsection

@push('js_page')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        let eventsData = [],
            intakesData = [],
            allEvents = [],
            calendar;
        let intakeTeachersCache = {};

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

        function getStartEndDateThisYear() {
            let currentYear = new Date().getFullYear();
            let startDate = new Date(currentYear, 0, 1);
            let endDate = new Date(currentYear, 11, 31);

            let formattedStartDate = startDate.toISOString().slice(0, 10);
            let formattedEndDate = endDate.toISOString().slice(0, 10);

            return {
                startDate: formattedStartDate,
                endDate: formattedEndDate,
            };
        }

        function getRandomEventColor() {
            const randomIndex = Math.floor(Math.random() * eventColors.length);
            return eventColors[randomIndex];
        }


        document.addEventListener('DOMContentLoaded', function() {
            function getCalendarEvents(startDate, endDate) {
                $.ajax({
                    url: `/api/user-events?startDate=${startDate}&endDate=${endDate}`,
                    type: "GET",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
                    },
                    beforeSend: function() {
                        document.getElementById("loadingSpinner").classList.remove("d-none");
                    },
                    success: function(result) {
                        if (result.meta.success) {
                            eventsData = eventsData.concat(result.data.events);
                            if (allEvents.length) {
                                allEvents = allEvents.concat(result.data.events);
                                rerenderCalendar();
                                return;
                            }
                        }
                        initCalendar();
                    }
                });
            }

            function getCalendarIntakes() {
                $.ajax({
                    url: "/api/student-intakes",
                    type: "GET",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
                    },
                    beforeSend: function() {
                        document.getElementById("loadingSpinner").classList.remove("d-none");
                    },
                    success: function(result) {
                        if (result.meta.success) {
                            intakesData = result.data;
                        }

                        let getDateRes = getStartEndDateThisYear();
                        getCalendarEvents(getDateRes.startDate, getDateRes.endDate);
                    }
                });
            }

            getCalendarIntakes();

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
                if (intakesData.intakeMembers) {
                    if (intakesData.intakeMembers.length) {
                        intakesData.intakeMembers.map(item => {
                            if (item.intake) {
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
                                                title: JSON.parse(item.intake.subject.name)[
                                                    "{{ auth()->user()->lang }}"],
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
                                                backgroundColor: item.intake.subject.color,
                                                extendedProps: {
                                                    intake: item.intake,
                                                    subject: item.intake.subject,
                                                    type: 'intake',
                                                },
                                                classNames: ['cursor-pointer'],
                                            });
                                        }
                                        currentDate.setDate(currentDate.getDate() + 1);
                                    }
                                }
                            }
                        });
                    }
                }

                if (eventsData && eventsData.length) {
                    eventsData.map(item => {
                        let classNames = ['cursor-pointer'];
                        if (item.active == 0) {
                            classNames.push('text-decoration-line-through');
                        }
                        res.push({
                            id: item.uuid,
                            title: item.name,
                            start: item.start_date.split(" ")[0] + 'T' +
                                addPreFixHourMinute(item.start_hour) + ":" +
                                addPreFixHourMinute(
                                    item.start_minute) + ":00+07:00",
                            startStr: addPreFixHourMinute(item.start_hour) + ":" +
                                addPreFixHourMinute(
                                    item.start_minute),
                            end: item.end_date.split(" ")[0] + 'T' +
                                addPreFixHourMinute(item.end_hour) + ":" +
                                addPreFixHourMinute(
                                    item.end_minute) + ":00+07:00",
                            endStr: addPreFixHourMinute(item.end_hour) + ":" +
                                addPreFixHourMinute(
                                    item.end_minute),
                            display: "block",
                            backgroundColor: item.color,
                            extendedProps: {
                                originalEvent: item,
                                type: 'event',
                            },
                            classNames: classNames,
                        });
                    });
                }

                allEvents = [...res];
            }

            function initCalendar() {
                let params = new URL(window.location.href);
                var view_mode = params.searchParams.get("view_mode") || 'dayGridMonth';
                processEvents();

                var calendarEl = document.getElementById('huet_calendar');
                calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    events: [...allEvents],
                    eventClick: function(info) {
                        handleClickEvent(info);
                    },
                    initialView: view_mode,
                });

                calendar.setOption('locale', 'vn');
                // calendar.on('dateClick', function(info) {
                //     console.log('info', info);
                //     console.log('clicked on ' + info.dateStr);
                // });
                calendar.render();

                // remove loading spinner
                document.getElementById("loadingSpinner").classList.add("d-none");
            }

            function rerenderCalendar() {
                calendar.events = [...allEvents];
                calendar.render();
            }

            function handleClickEvent(info) {
                document.getElementById("openEventInfoModalButton").click();

                if (info.event.extendedProps.type == 'intake') {
                    populateEventInfoTypeIntake(info);
                }
                if (info.event.extendedProps.type == 'event') {
                    populateEventInfoTypeEvent(info);
                }
            }

            function populateEventInfoTypeEvent(info) {
                let currentCreatedBy = info.event.extendedProps.originalEvent.created_by_user;
                let originalEvent = info.event.extendedProps.originalEvent;

                let titleClass = '';
                if (originalEvent.active == 0) {
                    titleClass = 'text-decoration-line-through';
                }
                document.getElementById("eventInfoModalTitle").innerHTML = `
                <a class="${titleClass}" href="/events/${originalEvent.uuid}">${originalEvent.name}</a>
                `;

                if (currentCreatedBy.role == "teacher") {
                    document.getElementById("eventModalUserInfo").innerHTML =
                        "{{ __('texts.texts.teacher.' . auth()->user()->lang) }} " + `<a href="/users/${currentCreatedBy.uuid}" target="_blank">${currentCreatedBy.last_name +
                        " " + currentCreatedBy.first_name}</a>`;
                } else {
                    document.getElementById("eventModalUserInfo").innerHTML =
                        "{{ __('texts.texts.student.' . auth()->user()->lang) }} " + `<a href="/users/${currentCreatedBy.uuid}" target="_blank">${currentCreatedBy.last_name +
                        " " + currentCreatedBy.first_name}</a>`;
                }

                document.getElementById("eventModalTimeInfo").textContent = populateEventDateInfo(originalEvent);
                document.getElementById("eventModalLocationInfo").textContent = originalEvent.location;
                document.getElementById("eventModalDescriptionInfo").innerHTML = `
                    <p class="text-grey-500">${originalEvent.description}</p>
                    ${getInfoEventImages(originalEvent)}
                `;
                document.getElementById("eventModalGoingInfoContainer").classList.remove("d-none");
                document.getElementById("eventModalGoingInfo").textContent = originalEvent.event_members ?
                    originalEvent.event_members.filter(
                        item => item.status == 'going').length : 0;
                document.getElementById("eventModalInterestedInfo").textContent = originalEvent.event_members ?
                    originalEvent.event_members.filter(
                        item => item.status == 'interested').length : 0;
            }

            function getInfoEventImages(event) {
                let files = [];
                try {
                    files = JSON.parse(event.files);
                } catch (err) {
                    console.log('error parse event images', err);
                }

                if (files.length) {
                    let res = '';
                    files.map((item, index) => {
                        item.url =
                            "{{ config('aws_.aws_url.url') . '/' . config('aws_.event_images.path') . '/' . config('aws_.event_images.file_path') }}" +
                            '/' + item.url;
                        res += `
                        <div class="col-xs-4 col-sm-4 p-1">
                            <img src="${item.url}" class="cursor-pointer border rounded-3 w-100 event-info-images" data-source="${item.url}" style="object-fit: cover; aspect-ratio: 1/1;" 
                                alt="${item.name}">
                        </div>
                        `;
                    });

                    return `
                <div class="card-body d-block p-0">
                    <div class="row ps-2 pe-2">
                        ${res}
                    </div>
                </div>
                `;
                }
                return '';
            }

            function customFormatEventDate(input) {
                try {
                    return input.split("-")[2] + '/' + input.split("-")[1] + '/' + input.split("-")[0];
                } catch (err) {
                    return input;
                }
            }

            function populateEventDateInfo(event) {
                if (event.start_minute < 10) {
                    event.start_minute = '0' + event.start_minute;
                }
                if (event.end_minute < 10) {
                    event.end_minute = '0' + event.end_minute;
                }
                if (event.start_date == event.end_date) {
                    return `${event.start_hour}:${event.start_minute} - ${event.end_hour}:${event.end_minute} ${customFormatEventDate(event.start_date.split(" ")[0])}`;
                } else {
                    return `${event.start_hour}:${event.start_minute} ${customFormatEventDate(event.start_date.split(" ")[0])} - ${event.end_hour}:${event.end_minute} ${customFormatEventDate(event.end_date.split(" ")[0])}`;
                }
            }

            function populateEventInfoTypeIntake(info) {
                document.getElementById("eventInfoModalTitle").innerHTML = `
                <a href="/intakes/${info.event.extendedProps.intake.uuid}">${JSON.parse(info.event.extendedProps.subject.name)["{{ auth()->user()->lang }}"]}</a>
                `;

                if (!intakeTeachersCache[info.event.extendedProps.intake.uuid]) {
                    getIntakeTeacherInfo(info.event.extendedProps.intake.uuid);
                } else {
                    let intakeUuid = info.event.extendedProps.intake.uuid;
                    document.getElementById("eventModalUserInfo").innerHTML =
                        "{{ __('texts.texts.teacher.' . auth()->user()->lang) }} " + intakeTeachersCache[
                            intakeUuid].map(item => {
                            return `<a href="/users/${item.uuid}" target="_blank">${item.last_name + " " + item.first_name}</a>`;
                        }).join(", ");
                }

                document.getElementById("eventModalTimeInfo").textContent = populateIntakeDateInfo(info.event
                    .extendedProps.intake);
                document.getElementById("eventModalLocationInfo").textContent = info.event.extendedProps.intake
                    .location;

                document.getElementById("eventModalDescriptionInfo").innerHTML = "";
                document.getElementById("eventModalGoingInfoContainer").classList.add("d-none");
            }

            function populateIntakeDateInfo(intake) {
                if (intake.start_minute < 10) {
                    intake.start_minute = '0' + intake.start_minute;
                }
                if (intake.end_minute < 10) {
                    intake.end_minute = '0' + intake.end_minute;
                }
                return `${intake.start_hour}:${intake.start_minute} - ${intake.end_hour}:${intake.end_minute}`;
            }

            function getIntakeTeacherInfo(intakeUuid) {
                $.ajax({
                    url: `/api/student-intakes/${intakeUuid}/teacher-info`,
                    type: "GET",
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
                    },
                    success: function(result) {
                        if (result.meta.success) {
                            intakeTeachersCache[intakeUuid] = result.data.intakeTeachers
                                .map(item => {
                                    return item.user;
                                });
                            document.getElementById("eventModalUserInfo").innerHTML =
                                "{{ __('texts.texts.teacher.' . auth()->user()->lang) }} " +
                                intakeTeachersCache[
                                    intakeUuid].map(item => {
                                    return `<a href="/users/${item.uuid}" target="_blank">${item.last_name + " " + item.first_name}</a>`;
                                }).join(", ");
                        } else {
                            document.getElementById("eventModalUserInfo").textContent = "NaN";
                        }
                    },
                    error: function(err) {
                        console.log('get intake teacher error', err);
                        document.getElementById("eventModalUserInfo").textContent = "NaN";
                    }
                });
            }
        });

        // add event
        let eventValidations = {
            name_required: {
                vi: "Vui lòng điền tên sự kiện",
                en: "Please input event name",
            },
            description_required: {
                vi: "Vui lòng điền mô tả sự kiện",
                en: "Please input event description",
            },
            location_required: {
                vi: "Vui lòng điền địa điểm tổ chức sự kiện",
                en: "Please input event location",
            },
            start_time_required: {
                vi: "Vui lòng chọn thời gian bắt đầu sự kiện",
                en: "Please input event start time",
            },
            end_time_required: {
                vi: "Vui lòng chọn thời gian kết thúc sự kiện",
                en: "Please input event end time",
            },
            invalid_time: {
                vi: "Thời gian sự kiện không hợp lệ",
                en: "Invalid event time",
            },
            invalid_files: {
                vi: "Vui lòng chỉ upload file ảnh",
                en: "Please only upload image files",
            },
        }

        let saveAddEventButton = document.getElementById("saveAddEventButton");
        let addEventStartTime = document.getElementById("addEventStartTime");
        let addEventEndTime = document.getElementById("addEventEndTime");

        function getEventDate(input) {
            try {
                return input.split("T")[0];
            } catch (err) {
                return null;
            }
        }

        function getEventHour(input) {
            try {
                let time = input.split("T")[1];
                return parseInt(time.split(":")[0]);
            } catch (err) {
                return null;
            }
        }

        function getEventMinute(input) {
            try {
                let time = input.split("T")[1];
                return parseInt(time.split(":")[1]);
            } catch (err) {
                return null;
            }
        }

        function addPreFixHourMinute(input) {
            if (input < 10) {
                return '0' + input;
            }
            return input;
        }

        saveAddEventButton.addEventListener("click", e => {
            let currentLang = "{{ auth()->user()->lang }}";
            let eventName = document.getElementById("addEventName");
            let eventDescription = document.getElementById("addEventDescription");
            let eventLocation = document.getElementById("addEventLocation");
            let eventColor = document.getElementById("addEventColor");

            let eventMessages = [];
            if (!eventName.value) {
                eventMessages.push(eventValidations.name_required[currentLang]);
            }
            if (!eventDescription.value) {
                eventMessages.push(eventValidations.description_required[currentLang]);
            }
            if (!eventLocation.value) {
                eventMessages.push(eventValidations.location_required[currentLang]);
            }
            if (!addEventStartTime.value) {
                eventMessages.push(eventValidations.start_time_required[currentLang]);
            }
            if (!addEventEndTime.value) {
                eventMessages.push(eventValidations.end_time_required[currentLang]);
            }
            if (addEventStartTime.value && addEventEndTime.value) {
                var date1 = new Date(addEventStartTime.value);
                var date2 = new Date(addEventEndTime.value);
                if (date1 >= date2) {
                    eventMessages.push(eventValidations.invalid_time[currentLang]);
                }
            }

            if (eventMessages.length) {
                alret(eventMessages.join(', '));
                return;
            }

            let startDate = getEventDate(addEventStartTime.value);
            let startHour = getEventHour(addEventStartTime.value);
            let startMinute = getEventMinute(addEventStartTime.value);

            let endDate = getEventDate(addEventEndTime.value);
            let endHour = getEventHour(addEventEndTime.value);
            let endMinute = getEventMinute(addEventEndTime.value);

            let formData = new FormData();

            formData.append("event_name", eventName.value);
            formData.append("color", eventColor.value);
            formData.append("event_description", eventDescription.value);
            formData.append("event_location", eventLocation.value);
            formData.append("start_date", startDate);
            formData.append("end_date", endDate);
            formData.append("start_hour", startHour);
            formData.append("start_minute", startMinute);
            formData.append("end_hour", endHour);
            formData.append("end_minute", endMinute);

            $.ajax({
                type: "POST",
                url: `/create-event`,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    document.getElementById("loadingSpinner").classList.remove("d-none");
                },
                complete: function(data) {
                    document.getElementById("loadingSpinner").classList.add("d-none");
                },
                error: function(error) {
                    alert(error.statusText);
                },
                success: function(data) {
                    if (data.meta.success) {
                        if (calendar) {
                            let item = data.data.event;
                            calendar.addEvent({
                                id: item.uuid,
                                title: item.name,
                                start: item.start_date.split(" ")[0] + 'T' +
                                    addPreFixHourMinute(item.start_hour) + ":" +
                                    addPreFixHourMinute(
                                        item.start_minute) + ":00+07:00",
                                startStr: addPreFixHourMinute(item.start_hour) + ":" +
                                    addPreFixHourMinute(
                                        item.start_minute),
                                end: item.end_date.split(" ")[0] + 'T' +
                                    addPreFixHourMinute(item.end_hour) + ":" +
                                    addPreFixHourMinute(
                                        item.end_minute) + ":00+07:00",
                                endStr: addPreFixHourMinute(item.end_hour) + ":" +
                                    addPreFixHourMinute(
                                        item.end_minute),
                                display: "block",
                                backgroundColor: item.color,
                                extendedProps: {
                                    originalEvent: item,
                                    type: 'event',
                                },
                            });
                        }
                        document.getElementById("closeAddEventModal").click();
                    } else {
                        let message = currentLang == "vi" ?
                            "Đã có lỗi xảy ra. Xin vui lòng thử lại sau." :
                            "Error happened. Please try again later."
                        if (data.message) {
                            message = data.message;
                        }
                        alert(message);
                    }
                },
            });
        });
    </script>
@endpush

@section('modal')
    <div data-bs-toggle="modal" data-bs-target="#eventInfoModal" class="d-none" id="openEventInfoModalButton"></div>

    <div class="modal fade" id="eventInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="eventInfoModalTitle"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                    <div>
                        <i class="feather-calendar me-3"></i>
                        <span id="eventModalTimeInfo"></span>
                    </div>
                    <div>
                        <i class="feather-map-pin me-3"></i>
                        <span id="eventModalLocationInfo"></span>
                    </div>
                    <div>
                        <i class="feather-user me-3"></i>
                        <span id="eventModalUserInfo"></span>
                    </div>
                    <div id="eventModalGoingInfoContainer" class="d-none">
                        <i class="feather-info me-3"></i>
                        <span id="eventModalGoingInfo"></span> going, <span id="eventModalInterestedInfo"></span> interested
                    </div>
                    <div id="eventModalDescriptionInfo"></div>
                </div>
            </div>
        </div>
    </div>
    @include('front-end.layouts.calendar.add_event_modal')
@endsection

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ __('texts.texts.events.' . auth()->user()->lang) }}
                            </h1>
                            <div class="mt-2 mb-3">
                                <button class="btn btn-success text-white" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addEventCalendarModal">
                                    {{ __('texts.texts.add.' . auth()->user()->lang) }}
                                </button>
                            </div>
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
