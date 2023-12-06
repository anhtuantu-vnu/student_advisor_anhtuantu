@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <style>
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
    </style>
@endsection

@push('js_page')
    <script>
        let intakeStudentsTbody = document.getElementById("intake-students-tbody");
        let intakeStudentsTr = document.querySelectorAll('.intake-students-tr');
        let searchStudentName = document.getElementById("searchStudentName");
        let scheduleMeetingButton = document.getElementById("scheduleMeetingButton");
        let checkAllStudents = document.getElementById("checkAllStudents");
        let unCheckAllStudents = document.getElementById("unCheckAllStudents");
        let studentCheckboxes = document.querySelectorAll(".student-checkbox");
        let chosenStudents = [];

        Array.from(studentCheckboxes).forEach(box => {
            box.addEventListener("change", e => {
                if (e.target.checked) {
                    let findStudent = chosenStudents.find(student => {
                        return student.uuid == e.target.dataset.studentUuid;
                    });
                    if (!findStudent) {
                        chosenStudents.push(JSON.parse(e.target.dataset.student));
                        scheduleMeetingButton.removeAttribute("disabled");
                    }
                } else {
                    chosenStudents = chosenStudents.filter(student => {
                        return student.uuid != e.target.dataset.studentUuid;
                    });
                    if (!chosenStudents.length) {
                        scheduleMeetingButton.setAttribute("disabled", "");
                    }
                }
            });
        });

        searchStudentName.addEventListener("keyup", e => {
            if (e.target.value) {
                Array.from(intakeStudentsTr).forEach(tr => {
                    if (tr.dataset.studentName.toLowerCase().indexOf(e.target.value) != -1) {
                        tr.classList.remove("d-none");
                    } else {
                        tr.classList.add("d-none");
                    }
                });
            } else {
                Array.from(intakeStudentsTr).forEach(tr => {
                    tr.classList.remove("d-none");
                });
            }
        });

        checkAllStudents.addEventListener("click", e => {
            Array.from(studentCheckboxes).forEach(box => {
                box.checked = true;
                chosenStudents.push(JSON.parse(box.dataset.student));
            });

            scheduleMeetingButton.removeAttribute("disabled");
        });

        unCheckAllStudents.addEventListener("click", e => {
            Array.from(studentCheckboxes).forEach(box => {
                box.checked = false;
            });
            chosenStudents = [];
            scheduleMeetingButton.setAttribute("disabled", "");
        });

        // open info modal
        let studentInfoTr = document.querySelectorAll(".student-info-tr");

        Array.from(studentInfoTr).forEach(tr => {
            tr.addEventListener("click", e => {
                document.getElementById("openStudentInfoModal").click();

                let student = JSON.parse(tr.dataset.student);
                document.getElementById("studentInfoModalTitle").innerHTML = `
                <div class="d-flex flex-wrap align-items-center">
                  <img class="border" style="height: 56px; width: 56px; margin-right: 8px; border-radius: 100%; aspect-ratio: 1; object-fit: cover;" src="${student.avatar}" alt="${student.last_name}_logo"/>  
                  <div>${student.last_name + " " + student.first_name}</div>
                </div>
                `;


                let genderMap = {
                    1: 'Male',
                    2: 'Female',
                    3: 'Other',
                }
                document.getElementById("studentNameInfo").innerText =
                    `${student.last_name + " " + student.first_name}`;
                document.getElementById("studentDepartmentInfo").innerText =
                    `${JSON.parse(student.department.name)["{{ auth()->user()->lang }}"]}`;
                document.getElementById("studentEmailInfo").innerText = `${student.email}`;
                document.getElementById("studentPhoneInfo").innerText = `${student.phone || ''}`;
                document.getElementById("studentGenderInfo").innerText =
                    `${genderMap[parseInt(student.gender)]}`;
                document.getElementById("studentDobInfo").innerText =
                    `${formatDateCustom(student.date_of_birth)}`;
            });
        });

        function formatDateCustom(input) {
            try {
                return `${input.split("-")[2]}/${input.split("-")[1]}/${input.split("-")[0]}`;
            } catch (err) {
                return input;
            }
        }

        // send custom email
        let sendCustomEmailButton = document.getElementById("sendCustomEmailButton");
        let sendCustomEmailButtons = document.querySelectorAll(".send-custom-email-button");

        Array.from(sendCustomEmailButtons).forEach(button => {
            button.addEventListener("click", e => {
                let student = JSON.parse(button.dataset.student);
                document.getElementById("openCustomEmailModalButton").click();

                document.getElementById("sendCustomEmailModalTitle").innerText =
                    `{{ __('texts.texts.send_email_to.' . auth()->user()->lang) }} ${student.last_name + " " + student.first_name}`;

                document.getElementById("customEmailToStudent").value = student.last_name + " " + student
                    .first_name;
                document.getElementById("customEmailSubject").value = "[" + student.last_name + " " +
                    student
                    .first_name + "]";
                document.getElementById("customEmailToEmail").value = student.email;
                document.getElementById("customEmailCcEmail").value = "{{ auth()->user()->email }}";
            });
        });

        sendCustomEmailButton.addEventListener("click", e => {
            if (!e.target.dataset.loading || e.target.dataset.loading == "false") {
                let customEmailsValidation = {
                    email_required: {
                        "vi": "Vui lòng điền địa chỉ email người nhận",
                        "en": "Please input receiver's email address",
                    },
                    content_required: {
                        "vi": "Vui lòng nhập nội dung email",
                        "en": "Please input email's content"
                    },
                    subject_required: {
                        "vi": "Vui lòng nhập tiêu đề email",
                        "en": "Please input email's subjecr"
                    }
                };

                let subject = document.getElementById("customEmailSubject").value;
                let content = document.getElementById("customEmailContent").value;
                let toEmail = document.getElementById("customEmailToEmail").value;
                let ccEmail = document.getElementById("customEmailCcEmail").value;
                let toName = document.getElementById("customEmailToStudent").value;
                let fromName = "{{ auth()->user()->last_name . ' ' . auth()->user()->first_name }}";

                if (!toEmail) {
                    alert(customEmailsValidation.email_required["{{ auth()->user()->lang }}"]);
                    return;
                }
                if (!subject) {
                    alert(customEmailsValidation.subject_required["{{ auth()->user()->lang }}"]);
                    return;
                }
                if (!content) {
                    alert(customEmailsValidation.content_required["{{ auth()->user()->lang }}"]);
                    return;
                }

                let formData = "toEmail=" + toEmail +
                    "&subject=" + subject +
                    "&ccEmails=" + ccEmail +
                    "&content=" + content +
                    "&fromName=" + "{{ auth()->user()->last_name . ' ' . auth()->user()->first_name }}" +
                    "&toName=" + document.getElementById("customEmailToStudent").value;
                $.ajax({
                    type: "POST",
                    url: `/send-custom-email`,
                    data: formData,
                    beforeSend: function() {
                        e.target.setAttribute("disabled", "");
                        document.getElementById("sendEmailLoading").classList.remove("d-none");
                        e.target.dataset.loading = "true";
                    },
                    complete: function() {
                        e.target.removeAttribute("disabled");
                        document.getElementById("sendEmailLoading").classList.add("d-none");
                        e.target.dataset.loading = "false";
                    },
                    error: function(error) {
                        alert(error.statusText);
                    },
                    success: function(data) {
                        if (data.meta.success) {
                            alert(data.data.message);
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
        });

        // schedule meeting
        let saveMeetingButton = document.getElementById("saveMeetingButton");
        let openScheduleMeetingModalButton = document.getElementById("openScheduleMeetingModalButton");
        let chosenStudentNamesModal = document.getElementById("chosenStudentNamesModal");

        scheduleMeetingButton.addEventListener("click", e => {
            openScheduleMeetingModalButton.click();

            chosenStudentNamesModal.innerHTML = '';
            chosenStudents.forEach(item => {
                chosenStudentNamesModal.innerHTML += `
                <span class="badge badge-primary" style="margin-right: 8px; margin-bottom: 8px;">
                  <div class="d-flex flex-wrap align-items-center">
                    <img src="${item.avatar}" alt="${item.last_name}_logo" style="width: 32px; height: 32px; object-fit: cover; border-radius: 100%; margin-right: 8px;" class="border" />
                    <div>
                      ${item.last_name + " " + item.first_name}
                    </div>
                  </div>  
                </span>
                `;
            });

            document.getElementById("meetingName").value =
                "Meeting with teacher {{ auth()->user()->last_name . ' ' . auth()->user()->first_name }}";
            document.getElementById("meetingDescription").value =
                "Meeting with teacher {{ auth()->user()->last_name . ' ' . auth()->user()->first_name }}";
        });

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

        saveMeetingButton.addEventListener("click", e => {
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
            }

            let currentLang = "{{ auth()->user()->lang }}";

            let meetingName = document.getElementById("meetingName");
            let meetingStartTime = document.getElementById("meetingStartTime");
            let meetingEndTime = document.getElementById("meetingEndTime");
            let meetingLocation = document.getElementById("meetingLocation");
            let meetingColor = document.getElementById("meetingColor");
            let meetingDescription = document.getElementById("meetingDescription");
            let messages = [];

            if (!meetingName.value) {
                messages.push(eventValidations.name_required[currentLang]);
            }
            if (!meetingDescription.value) {
                messages.push(eventValidations.description_required[currentLang]);
            }
            if (!meetingLocation.value) {
                messages.push(eventValidations.location_required[currentLang]);
            }
            if (!meetingStartTime.value) {
                messages.push(eventValidations.start_time_required[currentLang]);
            }
            if (!meetingEndTime.value) {
                messages.push(eventValidations.end_time_required[currentLang]);
            }
            if (meetingStartTime.value && meetingEndTime.value) {
                var date1 = new Date(meetingStartTime.value);
                var date2 = new Date(meetingEndTime.value);
                if (date1 >= date2) {
                    messages.push(eventValidations.invalid_time[currentLang]);
                }
            }

            if (messages.length) {
                alert(messages.join(", "));
                return;
            }

            let startDate = getEventDate(meetingStartTime.value);
            let startHour = getEventHour(meetingStartTime.value);
            let startMinute = getEventMinute(meetingStartTime.value);

            let endDate = getEventDate(meetingEndTime.value);
            let endHour = getEventHour(meetingEndTime.value);
            let endMinute = getEventMinute(meetingEndTime.value);

            let formData = new FormData();
            formData.append("event_name", meetingName.value);
            formData.append("color", meetingColor.value);
            formData.append("event_description", meetingDescription.value);
            formData.append("event_location", meetingLocation.value);
            formData.append("start_date", startDate);
            formData.append("end_date", endDate);
            formData.append("start_hour", startHour);
            formData.append("start_minute", startMinute);
            formData.append("end_hour", endHour);
            formData.append("end_minute", endMinute);
            formData.append("type", "meeting");

            $.ajax({
                type: "POST",
                url: `/create-event`,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    e.target.setAttribute("disabled", "");
                    document.getElementById("scheduleMeetingLoading").classList.remove("d-none");
                    e.target.dataset.loading = "true";
                },
                complete: function(data) {
                    e.target.removeAttribute("disabled");
                    document.getElementById("scheduleMeetingLoading").classList.add("d-none");
                    e.target.dataset.loading = "false";
                },
                error: function(error) {
                    alert(error.statusText);
                },
                success: function(data) {
                    if (data.meta.success) {
                        alert(
                            "{{ __('texts.texts.meeting_created_successfully.' . auth()->user()->lang) }}"
                        );
                        sendEventInvitations(data.data.event, chosenStudents);
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
        });

        function sendEventInvitations(event, studentsToInvite) {
            let userIds = studentsToInvite.map(item => item.uuid);
            let userNames = studentsToInvite.map(item => item.last_name + " " + item.first_name);
            let userEmails = studentsToInvite.map(item => item.email);
            let eventUrl = window.location.origin + "/events/" + event.uuid;

            let formData = 'userIds=' + userIds.join(",") +
                '&userNames=' + userNames.join(",") +
                '&userEmails=' + userEmails.join(",") +
                '&eventUrl=' + eventUrl;
            $.ajax({
                type: "POST",
                url: `/events/${event.id}/invite`,
                data: formData,
                error: function(error) {
                    alert("cannot send event invitations: " + error.statusText);
                },
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
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ json_decode($intake->subject->name, true)[auth()->user()->lang] }}
                            </h1>
                            <div>
                                <div id=''>
                                    <div class="mb-3">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('texts.texts.student.' . auth()->user()->lang) }}"
                                                        id="searchStudentName">
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="cursor-pointer" id="checkAllStudents">
                                                        {{ __('texts.texts.check_all.' . auth()->user()->lang) }}
                                                    </span>
                                                    /
                                                    <span class="cursor-pointer" id="unCheckAllStudents">
                                                        {{ __('texts.texts.uncheck_all.' . auth()->user()->lang) }}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="btn btn-primary text-white" disabled
                                                        id="scheduleMeetingButton" type="button">
                                                        {{ __('texts.texts.schedule_meeting.' . auth()->user()->lang) }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div style="max-height: 50vh; overflow-y: scroll;">
                                        <table class="table table-bordered table-sm">
                                            <thead class="thead-dark">
                                                <tr>
                                                    @if (auth()->user()->role == App\Http\Controllers\_CONST::TEACHER_ROLE)
                                                        <th scope="col">
                                                            #
                                                        </th>
                                                    @endif
                                                    <th scope="col">
                                                        {{ __('texts.texts.student.' . auth()->user()->lang) }}
                                                    </th>
                                                    @if (auth()->user()->role == App\Http\Controllers\_CONST::TEACHER_ROLE)
                                                        <th scope="col">
                                                            {{ __('texts.texts.points.' . auth()->user()->lang) }}
                                                        </th>
                                                        <th scope="col">
                                                            {{ __('texts.texts.action.' . auth()->user()->lang) }}
                                                        </th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody id="intake-students-tbody">
                                                @foreach ($intakeMembersStudents as $member)
                                                    <tr class="intake-students-tr"
                                                        data-student-name="{{ $member->user->last_name . ' ' . $member->user->first_name }}">
                                                        @if (auth()->user()->role == App\Http\Controllers\_CONST::TEACHER_ROLE)
                                                            <td>
                                                                <input type="checkbox" class="student-checkbox"
                                                                    data-student-uuid="{{ $member->user->uuid }}"
                                                                    data-student='{{ json_encode($member->user) }}'>
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <div class="d-flex flex-wrap align-items-center cursor-pointer student-info-tr"
                                                                data-student='{{ json_encode($member->user) }}'>
                                                                <div>
                                                                    <img src="{{ $member->user->avatar }}"
                                                                        alt="{{ $member->user->last_name }}_logo"
                                                                        class="border"
                                                                        style="width: 56px; height: 56px; object-fit: cover; border-radius: 100%; margin-right: 16px;">
                                                                </div>
                                                                <div>
                                                                    {{ $member->user->last_name . ' ' . $member->user->first_name }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @if (auth()->user()->role == App\Http\Controllers\_CONST::TEACHER_ROLE)
                                                            <td>
                                                                <ul>
                                                                    <li>
                                                                        {{ __('texts.texts.attendance_points.' . auth()->user()->lang) }}:
                                                                        @if ($member->attendance_points <= 6)
                                                                            <span class="text-danger">
                                                                                {{ $member->attendance_points }}
                                                                            </span>
                                                                        @else
                                                                            <span>
                                                                                {{ $member->attendance_points }}
                                                                            </span>
                                                                        @endif
                                                                    </li>
                                                                    <li>
                                                                        {{ __('texts.texts.mid_term_points.' . auth()->user()->lang) }}:
                                                                        @if ($member->mid_term_points <= 6)
                                                                            <span class="text-danger">
                                                                                {{ $member->mid_term_points }}
                                                                            </span>
                                                                        @else
                                                                            <span>
                                                                                {{ $member->mid_term_points }}
                                                                            </span>
                                                                        @endif
                                                                    </li>
                                                                    <li>
                                                                        {{ __('texts.texts.last_term_points.' . auth()->user()->lang) }}:
                                                                        @if ($member->last_term_points <= 6)
                                                                            <span class="text-danger">
                                                                                {{ $member->last_term_points }}
                                                                            </span>
                                                                        @else
                                                                            <span>
                                                                                {{ $member->last_term_points }}
                                                                            </span>
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-success send-custom-email-button"
                                                                    data-student='{{ json_encode($member->user) }}'>
                                                                    <i class="feather-mail text-white font-lg"></i>
                                                                </button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('front-end.layouts.intake.student_info_modal')
    @include('front-end.layouts.intake.send_custom_email_modal')
    @include('front-end.layouts.intake.schedule_meeting_modal')
@endsection
