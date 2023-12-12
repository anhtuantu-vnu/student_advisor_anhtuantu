@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

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
            min-width: 332px;
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

@push('js_page')
    <script>
        let eventImages = document.querySelectorAll(".event-images");
        let closePreviewImageModal = document.getElementById("closePreviewImageModal");
        let imageModal = document.getElementById("imageModal");
        let previewImage = document.getElementById("previewImage");

        closePreviewImageModal.addEventListener("click", e => {
            imageModal.classList.add("d-none");
        });

        Array.from(eventImages).forEach(image => {
            image.addEventListener("click", e => {
                imageModal.classList.remove("d-none");
                previewImage.setAttribute("src", image.getAttribute("src"));
            });
        });

        // get departments
        let updateEventDepartments = [];

        function getUpdateDepartments() {
            $.ajax({
                type: "GET",
                url: "/api/departments",
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
                },
                success: function(data) {
                    if (data.meta.success) {
                        updateEventDepartments = data.data.departments;
                        populateExistingDepartments();
                    }
                },
            });
        }

        getUpdateDepartments();

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

        let saveUpdateEventButton = document.getElementById("saveUpdateEventButton");
        let updateEventStartTime = document.getElementById("updateEventStartTime");
        let updateEventEndTime = document.getElementById("updateEventEndTime");

        let updateEventDepartment = document.getElementById("updateEventDepartment");
        let updateDepartmentChoices = document.getElementById("updateDepartmentChoices");
        let chosenUpdateDepartmentsContainer = document.getElementById("chosenUpdateDepartmentsContainer");

        let updateEventFilesInput = document.getElementById("updateEventFiles");
        let updatePreviewEventImages = document.getElementById("updatePreviewEventImages");

        let updateChosenDepartments = [];
        let updateEventFiles = [];

        updateEventFilesInput.addEventListener("change", e => {
            let files = e.target.files;
            let filesValid = true;
            Array.from(files).forEach(item => {
                if (item.type.indexOf("image") == -1) {
                    filesValid = false;
                }
            });

            if (!filesValid) {
                alert(eventValidations.invalid_files["{{ auth()->user()->lang }}"]);
                return;
            }

            updatePreviewEventImages.innerHTML = "";
            Array.from(files).forEach(item => {
                updatePreviewEventImages.innerHTML +=
                    `<div style="width: 96px; margin-right: 16px;" class="mt-2"><img src="${URL.createObjectURL(item)}" style="height: 96px; width: 96px; object-fit: cover;" class="border"/><small>...${item.name.substring(item.name.length - 8)}</small></div>`;
            });

            updateEventFiles = Array.from(files);
        });

        updateEventDepartment.addEventListener("keyup", e => {
            showUpdateDepartmentChoices(e.target.value);
        });

        updateEventDepartment.addEventListener("focus", e => {
            showUpdateDepartmentChoices(e.target.value);
        });

        function showUpdateDepartmentChoices(keyword) {
            let currentLang = "{{ auth()->user()->lang }}";
            let filteredDepartments = [...updateEventDepartments];
            if (keyword) {
                filteredDepartments = updateEventDepartments.filter(item => {
                    return item.name.toLowerCase().indexOf(keyword.toLowerCase()) != -1;
                });
            }

            updateDepartmentChoices.classList.remove("d-none");

            if (filteredDepartments.length) {
                let newInnerHtml = "";
                filteredDepartments.forEach(item => {
                    newInnerHtml +=
                        `<li class="cursor-pointer hover-li update-department-li" data-uuid="${item.uuid}" data-name="${JSON.parse(item.name)[currentLang]}">${JSON.parse(item.name)[currentLang]}</li>`;
                });
                updateDepartmentChoices.innerHTML =
                    `<div class="text-right cursor-pointer"><span id="closeUpdateDeparmentChoicesContainer">&times;</span></div><ul style="max-height: 120px; overflow-y: scroll;">${newInnerHtml}<ul>`;
                addClickEventUpdateDepartmentLi();
            } else {
                updateDepartmentChoices.innerHTML =
                    `<div class="text-right cursor-pointer"><span id="closeUpdateDeparmentChoicesContainer">&times;</span></div><ul><li>${"{{ __('texts.texts.no_result_found.' . auth()->user()->lang) }}"}</li><ul>`;
            }

            let closeUpdateDeparmentChoicesContainer = document.getElementById("closeUpdateDeparmentChoicesContainer");
            closeUpdateDeparmentChoicesContainer.addEventListener("click", e => {
                updateDepartmentChoices.classList.add("d-none");
            });
        }

        function addClickEventUpdateDepartmentLi() {
            let departmentLis = document.querySelectorAll(".update-department-li");
            console.log('updateChosenDepartments', updateChosenDepartments)
            Array.from(departmentLis).forEach(li => {
                li.addEventListener("click", e => {
                    let findDeparment = updateChosenDepartments.find(item => {
                        return item.uuid == e.target.dataset.uuid;
                    });
                    if (!findDeparment) {
                        updateChosenDepartments.push({
                            uuid: e.target.dataset.uuid,
                            name: e.target.dataset.name,
                        });
                    }

                    updateDepartmentChoices.classList.add("d-none");
                    showUpdateChosenDepartments();
                });
            });
        }

        function populateExistingDepartments() {
            let existingDepartments = "{{ $event->tags }}".split(',');
            chosenUpdateDepartmentsContainer.innerHTML = "";
            existingDepartments.forEach(dep => {
                let findDeparment = updateEventDepartments.find(item => {
                    return item.uuid == dep;
                });

                if (findDeparment) {
                    updateChosenDepartments.push({
                        uuid: findDeparment.uuid,
                        name: JSON.parse(findDeparment.name)["{{ auth()->user()->lang }}"],
                    });
                    chosenUpdateDepartmentsContainer.innerHTML +=
                        `<span class="badge badge-info update-chosen-department-badge cursor-pointer" style="margin-right: 8px; margin-top: 8px;" data-uuid="${findDeparment.uuid}">${JSON.parse(findDeparment.name)["{{ auth()->user()->lang }}"]}</span>`;
                }
            });

            addClickUpdateEventRemoveDepartment();
        }

        function addClickUpdateEventRemoveDepartment() {
            let chosenDepartmentBadges = document.querySelectorAll(".update-chosen-department-badge");
            Array.from(chosenDepartmentBadges).forEach(item => {
                item.addEventListener("click", e => {
                    let removeDepartmentConfirm = confirm(
                        "{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
                    if (removeDepartmentConfirm) {
                        let findIndex = updateChosenDepartments.findIndex(item => {
                            return item.uuid == e.target.dataset.uuid;
                        });
                        if (findIndex >= 0) {
                            updateChosenDepartments.splice(findIndex, 1);
                            showUpdateChosenDepartments();
                        }
                    }
                });
            });
        }

        function showUpdateChosenDepartments() {
            chosenUpdateDepartmentsContainer.innerHTML = "";
            console.log('updateChosenDepartments', updateChosenDepartments);
            updateChosenDepartments.forEach(item => {
                chosenUpdateDepartmentsContainer.innerHTML +=
                    `<span class="badge badge-info update-chosen-department-badge cursor-pointer" style="margin-right: 8px; margin-top: 8px;" data-uuid="${item.uuid}">${item.name}</span>`;
            });

            addClickUpdateEventRemoveDepartment();
        }

        updateEventStartTime.value = formdateDateTimeValueEventUpdate("{{ $event->start_date }}",
            "{{ $event->start_hour }}", "{{ $event->start_minute }}");
        updateEventEndTime.value = formdateDateTimeValueEventUpdate("{{ $event->end_date }}",
            "{{ $event->end_hour }}", "{{ $event->end_minute }}");

        function formdateDateTimeValueEventUpdate(date, hour, minute) {
            hour = hour >= 10 ? hour: '0' + hour;
            minute = minute >= 10 ? minute: '0' + minute;
            return date.split(" ")[0] + "T" + hour + ":" + minute;
        }

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

        saveUpdateEventButton.addEventListener("click", e => {
            let currentLang = "{{ auth()->user()->lang }}";
            let eventName = document.getElementById("updateEventName");
            let eventDescription = document.getElementById("updateEventDescription");
            let eventLocation = document.getElementById("updateEventLocation");
            let eventColor = document.getElementById("updateEventColor");

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
            if (!updateEventStartTime.value) {
                eventMessages.push(eventValidations.start_time_required[currentLang]);
            }
            if (!updateEventEndTime.value) {
                eventMessages.push(eventValidations.end_time_required[currentLang]);
            }
            if (updateEventStartTime.value && updateEventEndTime.value) {
                var date1 = new Date(updateEventStartTime.value);
                var date2 = new Date(updateEventEndTime.value);
                if (date1 >= date2) {
                    eventMessages.push(eventValidations.invalid_time[currentLang]);
                }
            }

            if (eventMessages.length) {
                alret(eventMessages.join(', '));
                return;
            }

            let startDate = getEventDate(updateEventStartTime.value);
            let startHour = getEventHour(updateEventStartTime.value);
            let startMinute = getEventMinute(updateEventStartTime.value);

            let endDate = getEventDate(updateEventEndTime.value);
            let endHour = getEventHour(updateEventEndTime.value);
            let endMinute = getEventMinute(updateEventEndTime.value);

            let formData = new FormData();

            formData.append("id", "{{ $event->id }}");
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
            formData.append("chosen_departments", updateChosenDepartments.map(item => item.uuid).join(','));

            if (updateEventFiles && updateEventFiles.length) {
                updateEventFiles.forEach(file => {
                    formData.append("files[]", file, file.name);
                });
            }

            $.ajax({
                type: "POST",
                url: `/events/{{ $event->id }}`,
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
                        let message = currentLang == "vi" ? "Cập nhật sự kiện thành công." :
                            "Event updated successfully.";
                        alert(message);
                        window.location.reload();
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

        // remove images
        let removeAllImagesButton = document.getElementById("removeAllImagesButton");
        removeAllImagesButton.addEventListener("click", e => {
            let currentLang = "{{ auth()->user()->lang }}";
            let deleteConfirm = confirm("{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
            if (!deleteConfirm) {
                return;
            }

            $.ajax({
                type: "POST",
                url: `/events/{{ $event->id }}/remove-images`,
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
                        let message = currentLang == "vi" ? "Cập nhật sự kiện thành công." :
                            "Event updated successfully.";
                        alert(message);
                        window.location.reload();
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

@section('content')
    {{-- images modal --}}
    <div id="imageModal" class="event-image-modal d-none">
        <span id="closePreviewImageModal" class="close-event-image-modal">&times;</span>
        <img id="previewImage" src="" alt="Modal_Image">
    </div>

    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <div class="mb-2">
                                <a href="/events/{{ $event->uuid }}">
                                    {{ __('texts.texts.back.' . auth()->user()->lang) . ' ' }}
                                </a>
                            </div>
                            <div class="d-flex flex-wrap">
                                <div style="margin-right: 16px;">
                                    <div style="width: 96px; height: 96px; border-radius: 8px;" class="border">
                                        <div class="d-flex align-items-center justify-content-center text-white"
                                            style="background: #fa525e; height: 32px; border-top-right-radius: 8px; border-top-left-radius: 8px;">
                                            {{ date('M', strtotime($event->start_date)) }}
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center" style="height: 64px;">
                                            <span class="fw-700 text-grey-900" style="font-size: 24px !important;">
                                                {{ date('d', strtotime($event->start_date)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h1 class="fw-700 mb-0 mt-0 text-grey-900" style="font-size: 34px !important;">
                                        {{ $event->name }}
                                    </h1>
                                    <div>
                                        <p class="text-grey-700">
                                            {{ __('texts.texts.hosted_by.' . auth()->user()->lang) . ' ' }}
                                            <a href="/users/{{ $event->createdByUser->uuid }}">
                                                {{ $event->createdByUser->last_name . ' ' . $event->createdByUser->first_name }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @if (auth()->user()->uuid == $event->created_by && $event->active == 1)
                                    <div class="mt-3">
                                        <form action="" id="updateEventForm">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <input type="text" class="form-control" value="{{ $event->name }}"
                                                        id="updateEventName"
                                                        placeholder="{{ __('texts.texts.event_name.' . auth()->user()->lang) . ' ' }}">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ $event->location }}" id="updateEventLocation"
                                                        placeholder="{{ __('texts.texts.location.' . auth()->user()->lang) . ' ' }}">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="updateEventStartTime">
                                                        {{ __('texts.texts.event_start_time.' . auth()->user()->lang) . ' ' }}
                                                    </label>
                                                    <input type="datetime-local" class="form-control"
                                                        id="updateEventStartTime"
                                                        placeholder="{{ __('texts.texts.start_time.' . auth()->user()->lang) . ' ' }}">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="updateEventEndTime">
                                                        {{ __('texts.texts.event_end_time.' . auth()->user()->lang) . ' ' }}
                                                    </label>
                                                    <input type="datetime-local" class="form-control"
                                                        id="updateEventEndTime"
                                                        placeholder="{{ __('texts.texts.end_time.' . auth()->user()->lang) . ' ' }}">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control"
                                                            id="updateEventDepartment"
                                                            placeholder="{{ __('texts.texts.department.' . auth()->user()->lang) . ' ' }}">
                                                    </div>
                                                    <div class="position-absolute bg-white p-2 rounded border d-none"
                                                        id="updateDepartmentChoices" style="min-width: 240px; z-index: 2;">
                                                    </div>
                                                    <div>
                                                        <div class="d-flex flex-wrap" id="chosenUpdateDepartmentsContainer">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <div class="input-color-container">
                                                        <input type="color" class="input-color" id="updateEventColor"
                                                            value="{{ $event->color }}"
                                                            placeholder="{{ __('texts.texts.color.' . auth()->user()->lang) . ' ' }}">
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <textarea id="updateEventDescription"
                                                        class="bor-0 w-100 rounded-xxl p-2 text-grey-600 fw-500 border-light-md theme-dark-bg" cols="30" rows="10"
                                                        placeholder="{{ __('texts.texts.event_description.' . auth()->user()->lang) }}">{{ $event->description }}</textarea>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <div class="card-body">
                                                        <div class="d-flex">
                                                            <span class="hiddenFileInputEvent rounded-3">
                                                                <input type="file" name="eventFiles" accept="image/*"
                                                                    id="updateEventFiles" multiple />
                                                            </span>
                                                        </div>
                                                        <div class="mt-2 d-flex flex-wrap" id="updatePreviewEventImages">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-primary text-white" type="button"
                                                        id="saveUpdateEventButton">
                                                        {{ __('texts.texts.save.' . auth()->user()->lang) . ' ' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="row mt-3">
                                            <div>
                                                <b>
                                                    {{ __('texts.texts.images.' . auth()->user()->lang) }}
                                                </b>
                                            </div>

                                            @if (count(json_decode($event->files, true)) > 0)
                                                @foreach (json_decode($event->files, true) as $file)
                                                    <div class="col-md-3">
                                                        <img class="border cursor-pointer event-images"
                                                            style="width: 100%; aspect-ratio: 1; object-fit: cover;"
                                                            src="{{ config('aws_.aws_url.url') . '/' . config('aws_.event_images.path') . '/' . config('aws_.event_images.file_path') . '/' . $file['url'] }}"
                                                            alt="{{ $file['name'] }}">
                                                    </div>
                                                @endforeach

                                                <div class="mt-2">
                                                    <button class="btn btn-danger text-white" type="button"
                                                        id="removeAllImagesButton">
                                                        {{ __('texts.texts.remove_all_images.' . auth()->user()->lang) }}
                                                    </button>
                                                </div>
                                            @else
                                                <span>
                                                    {{ __('texts.texts.no_images_found.' . auth()->user()->lang) }}
                                                </span>
                                            @endif

                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-12">
                                                {{ __('texts.texts.not_your_event.' . auth()->user()->lang) }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
