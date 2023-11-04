@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/emoji.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

    <style>
        .hiddenFileInputEvent>input {
            height: 100%;
            width: 100;
            opacity: 0;
            cursor: pointer;
        }

        .hiddenFileInputEvent {
            border: 1px solid #ccc;
            width: 32px;
            height: 32px;
            display: inline-block;
            overflow: hidden;
            cursor: pointer;

            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-image: url({{ asset('assets/imgs/photos_videos.png') }});
        }

        .input-color-container {
            position: relative;
            overflow: hidden;
            width: 40px;
            height: 40px;
            border: solid 2px #ddd;
            border-radius: 40px;
        }

        .input-color {
            position: absolute;
            right: -8px;
            top: -8px;
            width: 56px;
            height: 56px;
            border: none;
        }

        .input-color-label {
            cursor: pointer;
            text-decoration: underline;
            color: #3498db;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .hover-li:hover {
            background: #ccc;
        }

        /* Modal styles */
        .event-image-modal {
            position: fixed;
            z-index: 1000;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Modal content styles */
        .event-image-modal img {
            display: block;
            margin: 0 auto;
            max-width: 90%;
            max-height: 90%;
        }

        /* Close button styles */
        .close-event-image-modal {
            position: absolute;
            top: 15px;
            right: 30px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    {{-- images modal --}}
    <div id="imageModal" class="event-image-modal d-none">
        <span id="closePreviewImageModal" class="close-event-image-modal">&times;</span>
        <img id="previewImage" src="" alt="Modal_Image">
        aaaaa
    </div>

    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left">
                <!-- loader wrapper -->
                <div class="preloader-wrap p-3">
                    <div class="box shimmer">
                        <div class="lines">
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                        </div>
                    </div>
                    <div class="box shimmer mb-3">
                        <div class="lines">
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                        </div>
                    </div>
                    <div class="box shimmer">
                        <div class="lines">
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                        </div>
                    </div>
                </div>
                <!-- loader wrapper -->
                <div class="row feed-body">
                    <div class="col-xl-8 col-xxl-9 col-lg-8">
                        {{-- stories --}}
                        {{-- @include('front-end.components.home.stories') --}}

                        @include('front-end.components.home.create_event')

                        @include('front-end.components.home.events_feeds')

                        {{-- live here --}}
                        {{-- @include('front-end.components.home.live_stream') --}}

                        <div class="card w-100 text-center shadow-xss rounded-xxl border-0 p-4 mb-3 mt-3">
                            <div class="snippet mt-2 ms-auto me-auto" data-title=".dot-typing">
                                <div class="stage">
                                    <div class="dot-typing"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- right content --}}
                    @include('front-end.components.home.right_content')
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js_page')
    <script>
        let homeEventsFeedsContainer = document.getElementById("homeEventsFeedsContainer");
        let homeEvents = [];
        let eventPage = 1;
        let eventLimit = 1;
        let eventDepartments = [];
        let updateEventFiles = {};

        let currentLang = "{{ auth()->user()->lang }}";
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

        function handlePreloader() {
            if ($('.preloader').length > 0) {
                $('.preloader').delay(200).fadeOut(500);
            }
        }

        function PageLoad() {
            $(window).on("load", function() {
                $('.preloader-wrap').fadeOut(300);
                $('body').addClass('loaded');
            });
        }

        handlePreloader();

        // format dates, hours, minutes
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

        // load departments
        function getDepartments() {
            $.ajax({
                type: "GET",
                url: "/api/departments",
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
                },
                success: function(data) {
                    if (data.meta.success) {
                        eventDepartments = data.data.departments;
                    }
                },
            });
        }

        getDepartments();

        // load events
        function loadEvents() {
            $.ajax({
                type: "GET",
                url: `/events?page=${eventPage}&limit=${eventLimit}`,
                success: function(data) {
                    console.log('events', data);
                    if (data.meta.success) {
                        events = data.data.events;
                        homeEvents = homeEvents.concat([...events]);
                    }

                    populateEventsFeeds();
                },
                complete: function() {
                    if (!$('body').hasClass('loaded')) {
                        PageLoad();
                    }
                },
            });
        }

        loadEvents();

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
                return daysAgo + " day" + (daysAgo > 1 ? "s" : "") + " ago";
            }
        }

        function getEventDescription(event, maxLength = 50) {
            let seeMoreText = "{{ auth()->user()->lang }}" == "vi" ? "Xem thêm" : "See more";
            if (event.description.length > maxLength) {
                const truncatedText = event.description.slice(0, maxLength) + '...';
                const seeMoreLink = `<span class="fw-600 text-primary ms-2">${seeMoreText}</span>`;
                return `${truncatedText} ${seeMoreLink}`;
            } else {
                return event.description;
            }
        }

        function getEventImages(event) {
            let files = [];
            try {
                files = JSON.parse(event.files);
            } catch (err) {
                console.log('error parse event images', err);
            }

            if (files.length) {
                let res = '';
                let isMoreThanThreeImages = files.siz3 > 3;
                files.map((item, index) => {
                    item.url =
                        "{{ config('aws_.aws_url.url') . '/' . config('aws_.event_images.path') . '/' . config('aws_.event_images.file_path') }}" +
                        '/' + item.url;
                    if (isMoreThanThreeImages) {

                    } else {
                        res += `
                        <div class="col-xs-4 col-sm-4 p-1">
                            <img src="${item.url}" class="cursor-pointer border rounded-3 w-100 feed-images" data-source="${item.url}" style="object-fit: cover; aspect-ratio: 1/1;" 
                                alt="${item.name}">
                        </div>
                        `;
                    }
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

        function formatDateEvent(input) {
            try {
                return `${input.split('-')[2]}/${input.split('-')[1]}/${input.split('-')[0]}`;
            } catch (err) {
                return input;
            }
        }

        function formatDateTimeEvent(event) {
            if (event.start_date.split(' ')[0] == event.end_date.split(' ')[0]) {
                return event.start_hour + ':' + event.start_minute + ' - ' + event.end_hour + ':' + event.end_minute + ' ' +
                    formatDateEvent(event.end_date.split(' ')[0]);;
            }
            return event.start_hour + ':' + event.start_minute + ' ' + formatDateEvent(event.start_date.split(' ')[0]) +
                ' - ' + event.end_hour + ':' + event.end_minute + ' ' + formatDateEvent(event.end_date.split(' ')[0]);
        }

        function formdateDateTimeValueEvent(event, type) {
            return event[type + '_date'].split(" ")[0] + "T" + event[type + '_hour'] + ":" + event[type + '_minute'];
        }

        function populateEventsFeeds() {
            let eventsFeedsInnerHTML = homeEventsFeedsContainer.innerHTML;
            homeEvents.forEach(event => {
                getEventImages(event);

                let actions = '';
                let deleteActionLabel = "{{ auth()->user()->lang }}" == "vi" ? "Huỷ sự kiện" : "Cancel event";
                let deleteActionDescription = "{{ auth()->user()->lang }}" == "vi" ? "Huỷ sự kiện này khỏi lịch" :
                    "Cancel event from calendar";
                let updateActionLabel = "{{ auth()->user()->lang }}" == "vi" ? "Chỉnh sửa sự kiện" : "Update event";
                let updateActionDescription = "{{ auth()->user()->lang }}" == "vi" ?
                    "Chỉnh sửa mô tả, thời gian, địa điểm sự kiện" : "Update event time, description, location";

                if ("{{ auth()->user()->uuid }}" == event.created_by.uuid) {
                    actions = `
                    <a href="#" class="ms-auto" id="dropdownMenuEvent_${event.uuid}" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg"
                        aria-labelledby="dropdownMenuEvent_${event.uuid}">
                        <div class="card-body p-0 d-flex mt-2">
                            <i class="feather-info text-primary me-3 font-lg"></i>
                            <h4 class="cursor-pointer fw-600 text-grey-900 font-xssss mt-0 me-4 update-event-button" data-uuid="${event.uuid}">
                                ${updateActionLabel}
                                <span class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500 update-event-button" data-uuid="${event.uuid}">
                                    ${updateActionDescription}
                                </span>
                            </h4>
                        </div>
                        <div class="card-body p-0 d-flex">
                            <i class="feather-x-circle text-danger me-3 font-lg"></i>
                            <h4 class="cursor-pointer fw-600 text-grey-900 font-xssss mt-0 me-4 delete-event-button">
                                ${deleteActionLabel}
                                <span class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">
                                    ${deleteActionDescription}
                                </span>
                            </h4>
                        </div>
                    </div>
                    `;
                }

                eventsFeedsInnerHTML += `
                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3" id="feed_event_${event.uuid}">
                    <div class="card-body p-0 d-flex">
                        <figure class="avatar me-3"><img src="${event.created_by.avatar}" alt="${event.created_by.last_name}_avatar"
                                class="shadow-sm rounded-circle w45"></figure>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">${event.created_by.last_name + ' ' + event.created_by.first_name} <span
                                class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">${formatRelativeTime(event.created_at)}</span></h4>
                        ${actions}
                    </div>
                    <div class="card-body p-0 mb-3">
                        <div id="event_information_${event.uuid}">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div>
                                    <i class="feather-calendar me-3"></i> ${formatDateTimeEvent(event)}
                                </div>
                                <div>
                                    <i class="feather-map-pin me-3"></i> ${event.location}
                                </div>
                            </div>
                            <div class="me-lg-5 mt-2">
                                <b class="fw-500 text-black w-100">${event.name}</b>
                                <p class="text-grey-500 lh-26 w-100">${getEventDescription(event, 50)}</p>
                            </div>    
                        </div>
                        <div id="update_event_information_${event.uuid}" class="d-none">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control rounded-xxl" name="eventName" id="eventName_${event.uuid}" 
                                        placeholder="{{ __('texts.texts.event_name.' . auth()->user()->lang) }}" data-value="${event.name}" value="${event.name}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control rounded-xxl" name="eventLocation" id="eventLocation_${event.uuid}" 
                                        placeholder="{{ __('texts.texts.location.' . auth()->user()->lang) }}" data-value="${event.location}" value="${event.location}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="position-relative">
                                            <input type="text" class="form-control rounded-xxl update-event-department" name="eventDepartments"
                                                id="eventDepartmentsInput_${event.uuid}" data-departments="${event.tags}" data-update-departments="${event.tags}"
                                                placeholder="{{ __('texts.texts.department.' . auth()->user()->lang) }}">
                                        </div>
                                        <div class="position-absolute bg-white p-2 rounded border d-none" id="departmentChoices_${event.uuid}"
                                            style="min-width: 240px; z-index: 2;">
                                        </div>
                                        <div>
                                            <div class="d-flex flex-wrap" id="chosenDepartmentsContainer_${event.uuid}"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="input-color-container">
                                            <input type="color"class="input-color" id="eventColor_${event.uuid}" name="eventColor" data-value="${event.color}" value="${event.color}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="eventStartTime_${event.uuid}">
                                            {{ __('texts.texts.event_start_time.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="datetime-local" class="form-control rounded-xxl" name="eventStartTime" 
                                        id="eventStartTime_${event.uuid}" data-value="${formdateDateTimeValueEvent(event, "start")}" value="${formdateDateTimeValueEvent(event, "start")}" />
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="eventEndTime_${event.uuid}">
                                            {{ __('texts.texts.event_end_time.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="datetime-local" class="form-control rounded-xxl" name="eventEndTime" 
                                        id="eventEndTime_${event.uuid}" data-value="${formdateDateTimeValueEvent(event, "end")}" value="${formdateDateTimeValueEvent(event, "end")}" />
                                    </div>
                                    <div class="col-12 mb-2">
                                        <textarea name="eventDescription_${event.uuid}" id="eventDescription_${event.uuid}" 
                                        class="h100 bor-0 w-100 rounded-xxl p-2 font-xssss text-grey-600 fw-500 border-light-md theme-dark-bg" 
                                        cols="30" rows="10" placeholder="{{ __('texts.texts.event_description.' . auth()->user()->lang) }}" data-value="${event.description}">${event.description}</textarea>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="d-flex">
                                            <span class="hiddenFileInputEvent rounded-3">
                                                <input type="file" name="eventFiles" class="update-event-file" accept="image/*" id="eventFiles_${event.uuid}" multiple />
                                            </span>
                                        </div>
                                        <div class="mt-2 d-flex flex-wrap" id="previewEventImages_${event.uuid}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-success text-white save-update-event" id="saveEventButton_${event.uuid}" data-uuid="${event.uuid}" data-id="${event.id}" type="button">
                                            {{ __('texts.texts.save.' . auth()->user()->lang) }}
                                        </button>
                                        <button class="btn btn-secondary text-white cancel-update-event" id="cancelSaveEventButton_${event.uuid}" data-uuid="${event.uuid}" type="button">
                                            {{ __('texts.texts.cancel.' . auth()->user()->lang) }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    ${getEventImages(event)}
                    <div class="card-body d-flex p-0 mt-3">
                        <a href="#"
                            class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2"><i
                                class="feather-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss"></i>
                            <i class="feather-heart text-white bg-red-gradiant me-2 btn-round-xs font-xss"></i>2.8K
                            Like</a>
                        <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss"><i
                                class="feather-message-circle text-dark text-grey-900 btn-round-sm font-lg"></i><span
                                class="d-none-xss">22 Comment</span></a>
                        <a href="#" id="dropdownMenu21" data-bs-toggle="dropdown" aria-expanded="false"
                            class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss"><i
                                class="feather-share-2 text-grey-900 text-dark btn-round-sm font-lg"></i><span
                                class="d-none-xs">Share</span></a>
                    </div>
                </div>
                `;
            });

            homeEventsFeedsContainer.innerHTML = eventsFeedsInnerHTML;
            addImagesOpenModalEvent();
            addOpenUpdateEvent();
        }

        function addClickEventDepartmentLiUpdate(uuid) {
            let departmentLis = document.querySelectorAll(".department-li-" + uuid);
            Array.from(departmentLis).forEach(li => {
                li.addEventListener("click", e => {
                    let currentEventDepartmentsInput = document.getElementById("eventDepartmentsInput_" +
                        uuid);
                    let chosenTags = currentEventDepartmentsInput.dataset.departments.split(',');
                    let currentChosenDepartments = currentEventDepartmentsInput.dataset[
                        'updateDepartments'].split(',');
                    let findDeparment = currentChosenDepartments.find(item => {
                        return item == e.target.dataset.uuid;
                    });
                    if (!findDeparment) {
                        currentChosenDepartments.push(e.target.dataset.uuid);
                        currentEventDepartmentsInput.dataset[
                            'updateDepartments'] = currentChosenDepartments.join(',');
                    }

                    document.getElementById("departmentChoices_" + uuid).classList.add("d-none");
                    showChosenDepartmentsUpdate(uuid);
                });
            });
        }

        function showChosenDepartmentsUpdate(uuid) {
            let currentChosenDepartmentsContainer = document.getElementById("chosenDepartmentsContainer_" + uuid);
            currentChosenDepartmentsContainer.innerHTML = "";
            eventDepartments.forEach(item => {
                let currentEventDepartmentsInput = document.getElementById("eventDepartmentsInput_" +
                    uuid);
                let currentChosenDepartments = currentEventDepartmentsInput.dataset[
                    'updateDepartments'].split(',');
                if (currentChosenDepartments.indexOf(item.uuid) != -1) {
                    currentChosenDepartmentsContainer.innerHTML +=
                        `<span class="badge badge-info chosen-department-badge-${uuid} cursor-pointer" style="margin-right: 8px; margin-top: 8px;" data-event="${uuid}" data-uuid="${item.uuid}">${JSON.parse(item.name)["{{ auth()->user()->lang }}"]}</span>`;
                }
            });

            addClickEventRemoveDepartmentUpdate(uuid);
        }

        function addClickEventRemoveDepartmentUpdate(uuid) {
            let chosenDepartmentBadges = document.querySelectorAll(".chosen-department-badge-" + uuid);
            Array.from(chosenDepartmentBadges).forEach(item => {
                item.addEventListener("click", e => {
                    let removeDepartmentConfirm = confirm(
                        "{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
                    if (removeDepartmentConfirm) {
                        let currentEventDepartmentsInput = document.getElementById(
                            "eventDepartmentsInput_" +
                            uuid);
                        let currentChosenDepartments = currentEventDepartmentsInput.dataset[
                            'updateDepartments'].split(',');
                        let findIndex = currentChosenDepartments.findIndex(item => {
                            return item == e.target.dataset.uuid;
                        });
                        if (findIndex >= 0) {
                            currentChosenDepartments.splice(findIndex, 1);
                            currentEventDepartmentsInput.dataset[
                                'updateDepartments'] = currentChosenDepartments.join(',');
                            showChosenDepartmentsUpdate(uuid);
                        }
                    }
                });
            });
        }

        function showUpdateDepartmentChoises(keyword, uuid) {
            let filteredDepartments = [...eventDepartments];
            if (keyword) {
                filteredDepartments = eventDepartments.filter(item => {
                    return item.name.toLowerCase().indexOf(keyword.toLowerCase()) != -1;
                });
            }

            let currentDepartmentChoices = document.getElementById("departmentChoices_" + uuid);
            currentDepartmentChoices.classList.remove("d-none");

            if (filteredDepartments.length) {
                let newInnerHtml = "";
                filteredDepartments.forEach(item => {
                    newInnerHtml +=
                        `<li class="cursor-pointer hover-li department-li-${uuid}" data-event=${uuid} data-uuid="${item.uuid}" data-name="${JSON.parse(item.name)[currentLang]}">${JSON.parse(item.name)[currentLang]}</li>`;
                });
                currentDepartmentChoices.innerHTML =
                    `<div class="text-right cursor-pointer"><span id="closeDeparmentChoicesContainer_${uuid}">&times;</span></div><ul style="max-height: 120px; overflow-y: scroll;">${newInnerHtml}<ul>`;
                addClickEventDepartmentLiUpdate(uuid);
            } else {
                currentDepartmentChoices.innerHTML =
                    `<div class="text-right cursor-pointer"><span id="closeDeparmentChoicesContainer_${uuid}">&times;</span></div><ul><li>${"{{ __('texts.texts.no_result_found.' . auth()->user()->lang) }}"}</li><ul>`;
            }

            let closeDeparmentChoicesContainer = document.getElementById(`closeDeparmentChoicesContainer_${uuid}`);
            closeDeparmentChoicesContainer.addEventListener("click", e => {
                currentDepartmentChoices.classList.add("d-none");
            });
        }

        function addOpenUpdateEvent() {
            let updateEventButtons = document.querySelectorAll(".update-event-button");
            Array.from(updateEventButtons).forEach(button => {
                button.addEventListener("click", e => {
                    let currentUuid = e.target.dataset.uuid;
                    document.getElementById(`event_information_${currentUuid}`).classList.add("d-none");
                    document.getElementById(`update_event_information_${currentUuid}`).classList.remove(
                        "d-none");

                    // handle departments
                    let currentEventDepartmentsInput = document.getElementById("eventDepartmentsInput_" +
                        currentUuid);
                    let currentChosenDepartmentsContainer = document.getElementById(
                        "chosenDepartmentsContainer_" + currentUuid);
                    currentChosenDepartmentsContainer.innerHTML = "";

                    let chosenTag = currentEventDepartmentsInput.dataset.departments;
                    eventDepartments.forEach(department => {
                        if (chosenTag.split(',').indexOf(department.uuid) != -1) {
                            currentChosenDepartmentsContainer.innerHTML +=
                                `<span class="badge badge-info chosen-department-badge-${currentUuid} cursor-pointer" style="margin-right: 8px; margin-top: 8px;" data-uuid="${department.uuid}">${JSON.parse(department.name)["{{ auth()->user()->lang }}"]}</span>`;
                        }
                    });
                    addClickEventRemoveDepartmentUpdate(currentUuid);

                    currentEventDepartmentsInput.addEventListener("focus", e => {
                        showUpdateDepartmentChoises(e.target.value, currentUuid);
                    });

                    currentEventDepartmentsInput.addEventListener("keyup", e => {
                        showUpdateDepartmentChoises(e.target.value, currentUuid);
                    });

                    // handle upload files
                    let currentEventFiles = document.getElementById("eventFiles_" + currentUuid);
                    currentEventFiles.addEventListener("change", e => {
                        let files = e.target.files;
                        let filesValid = true;
                        Array.from(files).forEach(item => {
                            if (item.type.indexOf("image") == -1) {
                                filesValid = false;
                            }
                        });

                        if (!filesValid) {
                            alert(eventValidations.invalid_files[
                                "{{ auth()->user()->lang }}"]);
                            e.target.value = null;
                            return;
                        }

                        let currentPreviewEventImages = document.getElementById(
                            "previewEventImages_" + currentUuid);
                        currentPreviewEventImages.innerHTML = "";
                        Array.from(files).forEach(item => {
                            currentPreviewEventImages.innerHTML +=
                                `<div style="width: 96px; margin-right: 16px;" class="mt-2"><img src="${URL.createObjectURL(item)}" style="height: 96px; width: 96px; object-fit: cover;" class="border"/><small>${item.name}</small></div>`;
                        });

                        updateEventFiles[currentUuid] = Array.from(files);
                    });
                });
            });

            // cancel update
            let cancelUpdateEventButtons = document.querySelectorAll(".cancel-update-event");
            Array.from(cancelUpdateEventButtons).forEach(button => {
                button.addEventListener("click", e => {
                    let currentUuid = e.target.dataset.uuid;
                    document.getElementById(`event_information_${currentUuid}`).classList.remove("d-none");
                    document.getElementById(`update_event_information_${currentUuid}`).classList.add(
                        "d-none");

                    document.getElementById(`eventName_${currentUuid}`).value = document.getElementById(
                        `eventName_${currentUuid}`).dataset.value;
                    document.getElementById(`eventLocation_${currentUuid}`).value = document.getElementById(
                        `eventLocation_${currentUuid}`).dataset.value;
                    document.getElementById(`eventDescription_${currentUuid}`).value = document
                        .getElementById(`eventDescription_${currentUuid}`).dataset.value;
                    document.getElementById(`eventColor_${currentUuid}`).value = document.getElementById(
                        `eventColor_${currentUuid}`).dataset.value;
                    document.getElementById(`eventStartTime_${currentUuid}`).value = document
                        .getElementById(`eventStartTime_${currentUuid}`).dataset.value;
                    document.getElementById(`eventEndTime_${currentUuid}`).value = document.getElementById(
                        `eventEndTime_${currentUuid}`).dataset.value;
                    document.getElementById(`eventDepartmentsInput_${currentUuid}`).dataset.departments =
                        document.getElementById(`eventDepartmentsInput_${currentUuid}`).dataset
                        .updateDepartments;
                    document.getElementById(`eventFiles_${currentUuid}`).value = null;
                    document.getElementById("previewEventImages_" + currentUuid).innerHTML = "";
                    delete updateEventFiles[currentUuid];
                });
            });

            // save update
            let saveUpdateEventButtons = document.querySelectorAll(".save-update-event");
            Array.from(saveUpdateEventButtons).forEach(button => {
                button.addEventListener("click", e => {
                    updateEvent(e.target.dataset.id, e.target.dataset.uuid);
                });
            });
        }

        function updateEvent(id, uuid) {
            let messages = [];

            let eventName = document.getElementById("eventName_" + uuid);
            let eventDescription = document.getElementById("eventDescription_" + uuid);
            let eventLocation = document.getElementById("eventLocation_" + uuid);
            let eventStartTime = document.getElementById("eventStartTime_" + uuid);
            let eventEndTime = document.getElementById("eventEndTime_" + uuid);

            if (!eventName.value) {
                messages.push(eventValidations.name_required[currentLang]);
            }
            if (!eventDescription.value) {
                messages.push(eventValidations.description_required[currentLang]);
            }
            if (!eventLocation.value) {
                messages.push(eventValidations.location_required[currentLang]);
            }
            if (!eventStartTime.value) {
                messages.push(eventValidations.start_time_required[currentLang]);
            }
            if (!eventEndTime.value) {
                messages.push(eventValidations.end_time_required[currentLang]);
            }
            if (eventStartTime.value && eventEndTime.value) {
                var date1 = new Date(eventStartTime.value);
                var date2 = new Date(eventEndTime.value);
                if (date1 >= date2) {
                    messages.push(eventValidations.invalid_time[currentLang]);
                }
            }

            if (messages.length) {
                alert(messages.join(", "));
                return;
            }

            let startDate = getEventDate(eventStartTime.value);
            let startHour = getEventHour(eventStartTime.value);
            let startMinute = getEventMinute(eventStartTime.value);

            let endDate = getEventDate(eventEndTime.value);
            let endHour = getEventHour(eventEndTime.value);
            let endMinute = getEventMinute(eventEndTime.value);

            let formData = new FormData();
            formData.append("id", id);
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
            formData.append("chosen_departments", document.getElementById("eventDepartmentsInput_" + uuid).dataset[
                'updateDepartments']);

            if (updateEventFiles[uuid] && updateEventFiles[uuid].length) {
                updateEventFiles[uuid].forEach(file => {
                    formData.append("files[]", file, file.name);
                });
            }

            $.ajax({
                type: "POST",
                url: `/events/${id}`,
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
                    showPostEventMessages("danger", [error.statusText]);
                },
                success: function(data) {
                    console.log('data', data);
                    if (data.meta.success) {
                        let event = data.data.event;
                    } else {
                        let message = currentLang == "vi" ?
                            "Đã có lỗi xảy ra. Xin vui lòng thử lại sau." :
                            "Error happened. Please try again later."
                        if (data.message) {
                            message = data.message;
                        }

                        alert(message);
                        return;
                    }
                },
            });
        }

        // add event to close image modal
        let closePreviewImageModal = document.getElementById("closePreviewImageModal");
        closePreviewImageModal.addEventListener("click", e => {
            document.getElementById("imageModal").classList.add("d-none");
        });

        function addImagesOpenModalEvent() {
            let feedImages = document.querySelectorAll(".feed-images");
            Array.from(feedImages).forEach(img => {
                img.addEventListener("click", e => {
                    e.preventDefault();
                    document.getElementById("imageModal").classList.remove("d-none");
                    document.getElementById("previewImage").setAttribute("src", e.target.dataset.source);
                });
            })
        }
    </script>
@endpush
