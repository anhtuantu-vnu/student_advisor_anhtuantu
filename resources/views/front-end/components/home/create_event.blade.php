<form action="" id="createEventForm" autocomplete="off">
    <div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3">
        <div class="card-body p-0">
            <a href="#" class=" font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center"><i
                    class="btn-round-sm font-xs text-primary feather-edit-3 me-2 bg-greylight"></i>
                {{ __('texts.texts.create_event.' . auth()->user()->lang) }}
            </a>
        </div>

        <div class="card-body p-0 mt-3">
            <div id="error-post-event" class="alert d-none" role="alert">
            </div>
        </div>

        <div class="card-body p-0 mt-3">
            <input type="text" class="form-control rounded-xxl" name="eventName" id="eventName"
                placeholder="{{ __('texts.texts.event_name.' . auth()->user()->lang) }}">
        </div>

        <div class="card-body p-0 mt-3 d-none" id="eventTimeContainer">
            <div class="row">
                <div class="col-md-6">
                    <label for="eventStartTime">
                        {{ __('texts.texts.event_start_time.' . auth()->user()->lang) }}
                    </label>
                    <input type="datetime-local" class="form-control rounded-xxl" name="eventStartTime"
                        id="eventStartTime" />
                </div>
                <div class="col-md-6">
                    <label for="eventEndTime">
                        {{ __('texts.texts.event_end_time.' . auth()->user()->lang) }}
                    </label>
                    <input type="datetime-local" class="form-control rounded-xxl" name="eventEndTime"
                        id="eventEndTime" />
                </div>
            </div>
        </div>

        <div class="card-body p-0 mt-3 d-none" id="eventDescriptionContainer">
            <div class="row">
                <div class="position-relative col-md-10">
                    <figure class="avatar position-absolute ms-2 mt-1 top-5">
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->last_name }}_avatar"
                            style="object-fit:cover;" class="shadow-sm rounded-circle w30">
                    </figure>
                    <textarea name="eventDescription" id="eventDescription"
                        class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-600 fw-500 border-light-md theme-dark-bg"
                        cols="30" rows="10" placeholder="{{ __('texts.texts.event_description.' . auth()->user()->lang) }}"></textarea>
                </div>
                <div class="col-md-2">
                    <div class="input-color-container">
                        <input type="color"class="input-color" id="eventColor" name="eventColor" value="#9DA9E1">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0 mt-3 d-none" id="eventDepartmentContainer">
            <div class="row">
                <div class="col-md-6">
                    <div class="position-relative">
                        <input type="text" class="form-control rounded-xxl" name="eventDepartments"
                            id="eventDepartmentsInput"
                            placeholder="{{ __('texts.texts.department.' . auth()->user()->lang) }}">
                    </div>
                    <div class="position-absolute bg-white p-2 rounded border d-none" id="departmentChoices"
                        style="min-width: 240px; z-index: 2;">
                    </div>
                    <div>
                        <div class="d-flex flex-wrap" id="chosenDepartmentsContainer"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control rounded-xxl" name="eventLocation" id="eventLocation"
                        placeholder="{{ __('texts.texts.location.' . auth()->user()->lang) }}">
                </div>
            </div>
        </div>

        <div class="card-body p-0 mt-2">
            <div class="d-flex">
                <span class="hiddenFileInputEvent rounded-3">
                    <input type="file" name="eventFiles" accept="image/*" id="eventFiles" multiple />
                </span>
            </div>
            <div class="mt-2 d-flex flex-wrap" id="previewEventImages">
            </div>
        </div>
        <div class="card-body d-flex p-0 mt-2">
            <button class="btn btn-success text-white" id="saveEventButton" type="button">
                {{ __('texts.texts.save.' . auth()->user()->lang) }}
            </button>
        </div>
    </div>
</form>

<script>
    let eventName = document.getElementById("eventName");
    let eventDescription = document.getElementById("eventDescription");
    let eventLocation = document.getElementById("eventLocation");
    let eventStartTime = document.getElementById("eventStartTime");
    let eventEndTime = document.getElementById("eventEndTime");
    let eventColor = document.getElementById("eventColor");
    let eventFiles = document.getElementById("eventFiles");
    let previewEventImages = document.getElementById("previewEventImages");
    let uploadEventFiles;
    let eventMessages = [];

    let eventDepartmentsInput = document.getElementById("eventDepartmentsInput");
    let departmentChoices = document.getElementById("departmentChoices");
    let chosenDepartmentsContainer = document.getElementById("chosenDepartmentsContainer");
    let chosenDepartments = [];

    let errorPostEvent = document.getElementById("error-post-event");

    let eventTimeContainer = document.getElementById("eventTimeContainer");
    let eventDescriptionContainer = document.getElementById("eventDescriptionContainer");
    let eventDepartmentContainer = document.getElementById("eventDepartmentContainer");

    let saveEventButton = document.getElementById("saveEventButton");

    function showDepartmentChoises(keyword) {
        let filteredDepartments = [...eventDepartments];
        if (keyword) {
            filteredDepartments = eventDepartments.filter(item => {
                return item.name.toLowerCase().indexOf(keyword.toLowerCase()) != -1;
            });
        }

        departmentChoices.classList.remove("d-none");

        if (filteredDepartments.length) {
            let newInnerHtml = "";
            filteredDepartments.forEach(item => {
                newInnerHtml +=
                    `<li class="cursor-pointer hover-li department-li" data-uuid="${item.uuid}" data-name="${JSON.parse(item.name)[currentLang]}">${JSON.parse(item.name)[currentLang]}</li>`;
            });
            departmentChoices.innerHTML =
                `<div class="text-right cursor-pointer"><span id="closeDeparmentChoicesContainer">&times;</span></div><ul style="max-height: 120px; overflow-y: scroll;">${newInnerHtml}<ul>`;
            addClickEventDepartmentLi();
        } else {
            departmentChoices.innerHTML =
                `<div class="text-right cursor-pointer"><span id="closeDeparmentChoicesContainer">&times;</span></div><ul><li>${"{{ __('texts.texts.no_result_found.' . auth()->user()->lang) }}"}</li><ul>`;
        }

        let closeDeparmentChoicesContainer = document.getElementById("closeDeparmentChoicesContainer");
        closeDeparmentChoicesContainer.addEventListener("click", e => {
            departmentChoices.classList.add("d-none");
        });
    }

    function addClickEventDepartmentLi() {
        let departmentLis = document.querySelectorAll(".department-li");
        Array.from(departmentLis).forEach(li => {
            li.addEventListener("click", e => {
                let findDeparment = chosenDepartments.find(item => {
                    return item.uuid == e.target.dataset.uuid;
                });
                if (!findDeparment) {
                    chosenDepartments.push({
                        uuid: e.target.dataset.uuid,
                        name: e.target.dataset.name,
                    });
                }

                departmentChoices.classList.add("d-none");
                showChosenDepartments();
            });
        });
    }

    function showChosenDepartments() {
        chosenDepartmentsContainer.innerHTML = "";
        chosenDepartments.forEach(item => {
            chosenDepartmentsContainer.innerHTML +=
                `<span class="badge badge-info chosen-department-badge cursor-pointer" style="margin-right: 8px; margin-top: 8px;" data-uuid="${item.uuid}">${item.name}</span>`;
        });

        addClickEventRemoveDepartment();
    }

    function addClickEventRemoveDepartment() {
        let chosenDepartmentBadges = document.querySelectorAll(".chosen-department-badge");
        Array.from(chosenDepartmentBadges).forEach(item => {
            item.addEventListener("click", e => {
                let removeDepartmentConfirm = confirm(
                    "{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
                if (removeDepartmentConfirm) {
                    let findIndex = chosenDepartments.findIndex(item => {
                        return item.uuid == e.target.dataset.uuid;
                    });
                    if (findIndex >= 0) {
                        chosenDepartments.splice(findIndex, 1);
                        showChosenDepartments();
                    }
                }
            });
        });
    }

    eventDepartmentsInput.addEventListener("focus", e => {
        let keyword = e.target.value;
        showDepartmentChoises(keyword);
    });

    eventDepartmentsInput.addEventListener("keyup", e => {
        showDepartmentChoises(e.target.value);
    });

    eventName.addEventListener("focus", e => {
        eventTimeContainer.classList.remove("d-none");
        eventDescriptionContainer.classList.remove("d-none");
        eventDepartmentContainer.classList.remove("d-none");
    });

    eventName.addEventListener("blur", e => {
        if (e.target.value) {
            eventTimeContainer.classList.remove("d-none");
            eventDescriptionContainer.classList.remove("d-none");
            eventDepartmentContainer.classList.remove("d-none");
        } else {
            eventTimeContainer.classList.add("d-none");
            eventDescriptionContainer.classList.add("d-none");
            eventDepartmentContainer.classList.add("d-none");
        }
    });

    function showPostEventMessages(type, messages) {
        let types = ['danger', 'info', 'success', 'warning', 'primary', 'secondary', 'light', 'dark'];
        errorPostEvent.classList.remove("d-none");
        types.forEach(item => {
            errorPostEvent.classList.remove("alert-" + item);
        });

        errorPostEvent.classList.add("alert-" + type);

        let messageLis = "";
        messages.forEach(item => {
            messageLis += `<li>${item}</li>`;
        });

        errorPostEvent.innerHTML = `<ul>${messageLis}</ul>`;

        document.getElementById("createEventForm").scrollIntoView({
            behavior: 'smooth'
        });
    }

    function hidePostEventMessages() {
        errorPostEvent.classList.add("d-none");
        errorPostEvent.innerHTML = "";
    }

    eventFiles.addEventListener("change", e => {
        let files = e.target.files;
        let filesValid = true;
        Array.from(files).forEach(item => {
            if (item.type.indexOf("image") == -1) {
                filesValid = false;
            }
        });

        if (!filesValid) {
            eventMessages.push(eventValidations.invalid_files["{{ auth()->user()->lang }}"]);
            showPostEventMessages("danger", eventMessages);
            return;
        }

        previewEventImages.innerHTML = "";
        Array.from(files).forEach(item => {
            previewEventImages.innerHTML +=
                `<div style="width: 96px; margin-right: 16px;" class="mt-2"><img src="${URL.createObjectURL(item)}" style="height: 96px; width: 96px; object-fit: cover;" class="border"/><small>${item.name}</small></div>`;
        });

        uploadEventFiles = Array.from(files);
    });

    saveEventButton.addEventListener("click", e => {
        hidePostEventMessages();
        eventMessages = [];
        if (!eventName.value) {
            eventMessages.push(eventValidations.name_required[currentLang]);
        }
        if (!eventDescription.value) {
            eventMessages.push(eventValidations.description_required[currentLang]);
        }
        if (!eventLocation.value) {
            eventMessages.push(eventValidations.location_required[currentLang]);
        }
        if (!eventStartTime.value) {
            eventMessages.push(eventValidations.start_time_required[currentLang]);
        }
        if (!eventEndTime.value) {
            eventMessages.push(eventValidations.end_time_required[currentLang]);
        }
        if (eventStartTime.value && eventEndTime.value) {
            var date1 = new Date(eventStartTime.value);
            var date2 = new Date(eventEndTime.value);
            if (date1 >= date2) {
                eventMessages.push(eventValidations.invalid_time[currentLang]);
            }
        }

        if (eventMessages.length) {
            showPostEventMessages("danger", eventMessages);
            return;
        }

        let startDate = getEventDate(eventStartTime.value);
        let startHour = getEventHour(eventStartTime.value);
        let startMinute = getEventMinute(eventStartTime.value);

        let endDate = getEventDate(eventEndTime.value);
        let endHour = getEventHour(eventEndTime.value);
        let endMinute = getEventMinute(eventEndTime.value);

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
        formData.append("chosen_departments", chosenDepartments.map(item => item.uuid).join(','));

        if (uploadEventFiles && uploadEventFiles.length) {
            uploadEventFiles.forEach(file => {
                formData.append("files[]", file, file.name);
            });
        }

        $.ajax({
            type: "POST",
            url: "/create-event",
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
                if (data.meta.success) {
                    let message = currentLang == "vi" ? "Tạo sự kiện thành công." :
                        "Event created successfully.";
                    eventMessages.push(message);
                    showPostEventMessages("success", eventMessages);

                    clearEventFormData();
                    // add new event to feeds
                    let event = data.data.event;

                    prependEvent(event);
                } else {
                    let message = currentLang == "vi" ?
                        "Đã có lỗi xảy ra. Xin vui lòng thử lại sau." :
                        "Error happened. Please try again later."
                    if (data.message) {
                        message = data.message;
                    }

                    eventMessages.push(message);
                    showPostEventMessages("danger", eventMessages);
                }
            },
        });
    });

    function prependEvent(event) {
        let homeEventsFeedsContainer_ = document.getElementById("homeEventsFeedsContainer");
        if (homeEventsFeedsContainer_) {
            let actions = '';
            let deleteActionLabel = "{{ auth()->user()->lang }}" == "vi" ? "Huỷ sự kiện" : "Cancel event";
            let deleteActionDescription = "{{ auth()->user()->lang }}" == "vi" ? "Huỷ sự kiện này khỏi lịch" :
                "Cancel event from calendar";
            let updateActionLabel = "{{ auth()->user()->lang }}" == "vi" ? "Chỉnh sửa sự kiện" : "Update event";
            let updateActionDescription = "{{ auth()->user()->lang }}" == "vi" ?
                "Chỉnh sửa mô tả, thời gian, địa điểm sự kiện" : "Update event time, description, location";

            if ("{{ auth()->user()->uuid }}" == event.created_by_user.uuid) {
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
            homeEventsFeedsContainer_.innerHTML = `
                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3" id="feed_event_${event.uuid}">
                    <div class="card-body p-0 d-flex">
                        <figure class="avatar me-3"><img src="${event.created_by_user.avatar}" alt="${event.created_by_user.last_name}_avatar"
                                class="shadow-sm rounded-circle w45"></figure>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">${event.created_by_user.last_name + ' ' + event.created_by_user.first_name} <span
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
                                ${getEventDepartmentTags(event)}
                            </div>
                            <div class="me-lg-5 mt-2">
                                <a href="/events/${event.uuid}"><b class="fw-500 text-black w-100">${event.name}</b></a>
                                <p class="text-grey-500 lh-26 w-100" id="eventDescriptionShow_${event.uuid}">${getEventDescription(event.uuid, event.description, 50)}</p>
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
                        <span class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2">
                            <i class="feather-check-circle text-white bg-success me-1 btn-round-xs font-xss cursor-pointer going-to-event" data-id="${event.id}" data-uuid="${event.uuid}" id="goingToEvent_${event.uuid}"></i>
                            <span id="eventGoingCounter_${event.uuid}">${event.event_members.filter(item => item.status == 'going').length}</span>&nbsp;Going
                        </span>
                        <span class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss">
                            <i class="feather-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss cursor-pointer interested-in-event" data-id="${event.id}" data-uuid="${event.uuid}" id="interestedInEvent_${event.uuid}"></i>
                            <span id="eventInterestedCounter_${event.uuid}">${event.event_members.filter(item => item.status == 'interested').length}</span>&nbsp;Interested
                        </span>
                        <span class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss">
                            <i class="feather-copy text-grey-900 text-dark btn-round-sm font-lg cursor-pointer copy-event-url" data-uuid="${event.uuid}" id="copyEventUrl_${event.uuid}"></i>
                        </span>
                    </div>
                </div>
                ` + homeEventsFeedsContainer_.innerHTML;

            addImagesOpenModalEvent();
            addOpenUpdateEvent();
            addSeeMoreOrHideEventDescriptionEvent();
            addEventDeleteEvent();
        }
    }

    function clearEventFormData() {
        eventName.value = '';
        eventDescription.value = '';
        eventStartTime.value = '';
        eventEndTime.value = '';
        eventFiles.value = null;
        previewEventImages.innerHTML = "";
        chosenDepartments = [];
        showChosenDepartments();
    }
</script>
