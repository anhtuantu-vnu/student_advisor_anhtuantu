@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <style>
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

        .member-li:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        #goingMembers {
            display: none;
        }

        #goingContainer:hover #goingMembers {
            display: block;
            z-index: 2;
        }

        #interestedMembers {
            display: none;
        }

        #interestedContainer:hover #interestedMembers {
            display: block;
            z-index: 2;
        }

        #invitedPeople {
            display: none;
        }

        #invitedContainer:hover #invitedPeople {
            display: block;
            z-index: 2;
        }
    </style>
@endsection

@if ($event != null)
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

            let goingToEventButtonDetail = document.getElementById("goingToEventButtonDetail");
            let interestedInEventButtonDetail = document.getElementById("interestedInEventButtonDetail");
            let rejectEventButton = document.getElementById("rejectEventButton");

            goingToEventButtonDetail.addEventListener("click", e => {
                handleGoingToEventDetail(e);
            });

            interestedInEventButtonDetail.addEventListener("click", e => {
                handleInterestedInEventDetail(e);
            });

            if (rejectEventButton) {
                rejectEventButton.addEventListener("click", e => {
                    handleRejectEventInvitation(e);
                });
            }

            function handleGoingToEventDetail(e) {
                if (!e.target.dataset.loading || e.target.dataset.loading == "false") {
                    e.target.dataset.loading = "true";
                    $.ajax({
                        type: "POST",
                        url: `/events/{{ $event->id }}/going`,
                        complete: function() {
                            e.target.dataset.loading = "false";
                        },
                        error: function(error) {
                            alert(error.statusText);
                        },
                        success: function(data) {
                            if (data.meta.success) {
                                let goingCounter = parseInt(document.getElementById("goingCountSpan").textContent);
                                if (data.data.eventMember) {
                                    document.getElementById("invitedToEventContainer").classList.add("d-none");
                                    document.getElementById("goingSign").classList.remove("d-none");
                                    goingCounter++;
                                } else {
                                    document.getElementById("goingSign").classList.add("d-none");
                                    if (goingCounter > 0) {
                                        goingCounter--;
                                    }
                                }
                                document.getElementById("goingCountSpan").textContent = goingCounter;
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
            }

            function handleInterestedInEventDetail(e) {
                if (!e.target.dataset.loading || e.target.dataset.loading == "false") {
                    $.ajax({
                        type: "POST",
                        url: `/events/{{ $event->id }}/interested`,
                        beforeSend: function() {
                            e.target.dataset.loading = "true";
                        },
                        complete: function() {
                            e.target.dataset.loading = "false";
                        },
                        error: function(error) {
                            alert(error.statusText);
                        },
                        success: function(data) {
                            if (data.meta.success) {
                                let interestedCounter = parseInt(document.getElementById("interestedCountSpan")
                                    .textContent);
                                if (data.data.eventMember) {
                                    document.getElementById("likeSign").classList.remove("d-none");
                                    interestedCounter++;
                                } else {
                                    document.getElementById("likeSign").classList.add("d-none");
                                    if (interestedCounter > 0) {
                                        interestedCounter--;
                                    }
                                }
                                document.getElementById("interestedCountSpan").textContent = interestedCounter;
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
            }

            function handleRejectEventInvitation(e) {
                if (!e.target.dataset.loading || e.target.dataset.loading == "false") {
                    $.ajax({
                        type: "POST",
                        url: `/events/{{ $event->id }}/reject`,
                        beforeSend: function() {
                            e.target.dataset.loading = "true";
                        },
                        complete: function() {
                            e.target.dataset.loading = "false";
                        },
                        error: function(error) {
                            alert(error.statusText);
                        },
                        success: function(data) {
                            if (data.meta.success) {
                                document.getElementById("rejectSign").classList.remove("d-none");
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
            }

            // cancel event
            let cancelEventButton = document.getElementById("cancelEventButton");
            cancelEventButton.addEventListener("click", e => {
                let cancelEventConfirm = confirm(
                    "{{ __('texts.texts.cancel_event_confirm.' . auth()->user()->lang) }}");
                if (!cancelEventConfirm) {
                    return;
                }

                if (!e.target.loading || e.target.loading == "false") {
                    $.ajax({
                        type: "POST",
                        url: `/events/{{ $event->id }}/cancel`,
                        beforeSend: function() {
                            e.target.dataset.loading = "true";
                        },
                        complete: function() {
                            e.target.dataset.loading = "false";
                        },
                        error: function(error) {
                            alert(error.statusText);
                        },
                        success: function(data) {
                            if (data.meta.success) {
                                let message = "{{ auth()->user()->language }}" == "vi" ?
                                    "Sự kiện huỷ thành công" : "Cancel event successfully";
                                alert(message);
                                window.location.reload();
                                return;
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
        </script>
    @endpush
@endif

@section('content')
    {{-- images modal --}}
    <div id="imageModal" class="event-image-modal d-none">
        <span id="closePreviewImageModal" class="close-event-image-modal">&times;</span>
        <img id="previewImage" src="" alt="Modal_Image">
    </div>

    @if ($event == null)
        <div class="main-content right-chat-active">
            <div class="middle-sidebar-bottom">
                <div class="middle-sidebar-left pe-0">
                    <div class="row">
                        <div class="col-12 p-5 bg-white rounded-xxl">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900" style="font-size: 34px !important;">
                                {{ __('texts.texts.cannot_find_event.' . auth()->user()->lang) }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="main-content right-chat-active">
            <div class="middle-sidebar-bottom">
                <div class="middle-sidebar-left pe-0">
                    <div class="row">
                        <div class="col-12 p-5 bg-white rounded-xxl">
                            <div class="plan_header">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="d-flex flex-wrap">
                                        <div style="margin-right: 16px;">
                                            <div style="width: 96px; height: 96px; border-radius: 8px;" class="border">
                                                <div class="d-flex align-items-center justify-content-center text-white"
                                                    style="background: #fa525e; height: 32px; border-top-right-radius: 8px; border-top-left-radius: 8px;">
                                                    {{ date('M', strtotime($event->start_date)) }}
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center"
                                                    style="height: 64px;">
                                                    <span class="fw-700 text-grey-900" style="font-size: 24px !important;">
                                                        {{ date('d', strtotime($event->start_date)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 <?php if($event->active == 0) { ?>text-decoration-line-through<?php } ?>"
                                                style="font-size: 34px !important; max-width: 320px;">
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
                                    @if (auth()->user()->uuid == $event->created_by && $event->active == 1)
                                        <div>
                                            <a href="#" class="ms-auto"
                                                id="dropdownUpdateMenuEvent_{{ $event->uuid }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg"
                                                aria-labelledby="dropdownUpdateMenuEvent_{{ $event->uuid }}">
                                                <div class="card-body p-0 d-flex mt-2">
                                                    <i class="feather-info text-primary me-3 font-lg"></i>
                                                    <a href="/events/{{ $event->uuid }}/update">
                                                        <h4 class="cursor-pointer fw-600 text-grey-900 font-xssss mt-0 me-4 update-event-button"
                                                            data-uuid="${event.uuid}">
                                                            {{ __('texts.texts.update.' . auth()->user()->lang) }}
                                                            <span
                                                                class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500 update-event-button"
                                                                data-uuid="${event.uuid}">
                                                                {{ __('texts.texts.update_event_short_description.' . auth()->user()->lang) }}
                                                            </span>
                                                        </h4>
                                                    </a>
                                                </div>
                                                <div class="card-body p-0 d-flex" id="cancelEventButton">
                                                    <i class="feather-x-circle text-danger me-3 font-lg"></i>
                                                    <h4
                                                        class="cursor-pointer fw-600 text-grey-900 font-xssss mt-0 me-4 delete-event-button">
                                                        {{ __('texts.texts.delete.' . auth()->user()->lang) }}
                                                        <span class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">
                                                            {{ __('texts.texts.delete_event_short_description.' . auth()->user()->lang) }}
                                                        </span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="mt-3">
                                        <div>
                                            <i class="feather-calendar me-3"></i>
                                            @if ($event->start_date == $event->end_date)
                                                {{ ($event->start_hour >= 10 ? $event->start_hour : '0' . $event->start_hour) . ':' . ($event->start_minute >= 10 ? $event->start_minute : '0'. $event->start_minute) }}
                                                - {{ ($event->end_hour >= 10 ? $event->end_hour : '0' . $event->end_hour) . ':' . ($event->end_minute >= 10 ? $event->end_minute : '0'. $event->end_minute) }}
                                                {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                                            @else
                                                {{ ($event->start_hour >= 10 ? $event->start_hour : '0' . $event->start_hour) . ':' . ($event->start_minute >= 10 ? $event->start_minute : '0'. $event->start_minute) }}
                                                {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                                                {{ ($event->end_hour >= 10 ? $event->end_hour : '0' . $event->end_hour) . ':' . ($event->end_minute >= 10 ? $event->end_minute : '0'. $event->end_minute) }} 
                                                {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                            @endif
                                        </div>
                                        <div>
                                            <i class="feather-map-pin me-3"></i>
                                            {{ $event->location }}
                                        </div>
                                        <div>
                                            <i class="feather-info me-3"></i>
                                            <span class="cursor-pointer">
                                                <span id="goingContainer" class="position-relative">
                                                    <span id="goingCountSpan">{{ $goingCount }}</span> going
                                                    @if ($event->eventMembers->count() > 0)
                                                        <div class="position-absolute bg-white p-3 rounded border"
                                                            style="top: 12px; right: 0;" id="goingMembers">
                                                            <ul style="max-height: 160px; overflow-y: scroll;">
                                                                @foreach ($event->eventMembers as $member)
                                                                    @if ($member->status == \App\Models\EventMember::STATUS_GOING)
                                                                        <li class="mb-2 member-li">
                                                                            <a href="/users/{{ $member->user->uuid }}">
                                                                                <div class="d-flex flex-wrap align-items-center"
                                                                                    style="min-width: 200px;">
                                                                                    <div>
                                                                                        <img src="{{ $member->user->avatar }}"
                                                                                            alt="{{ $member->user->last_name }}_logo"
                                                                                            style="width: 32px; height: 32px; margin-right: 8px; border-radius: 100%; object-fit: cover;"
                                                                                            class="border">
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $member->user->last_name . ' ' . $member->user->first_name }}
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </span>,
                                                <span id="interestedContainer" class="position-relative">
                                                    <span id="interestedCountSpan">{{ $interestedCount }}</span> interested
                                                    @if ($event->eventMembers->count() > 0)
                                                        <div class="position-absolute bg-white p-3 rounded border"
                                                            style="top: 12px; right: 0;" id="interestedMembers">
                                                            <ul style="max-height: 160px; overflow-y: scroll;">
                                                                @foreach ($event->eventMembers as $member)
                                                                    @if ($member->status == \App\Models\EventMember::STATUS_INTERESTED)
                                                                        <li class="mb-2 member-li">
                                                                            <a href="/users/{{ $member->user->uuid }}">
                                                                                <div class="d-flex flex-wrap align-items-center"
                                                                                    style="min-width: 200px;">
                                                                                    <div>
                                                                                        <img src="{{ $member->user->avatar }}"
                                                                                            alt="{{ $member->user->last_name }}_logo"
                                                                                            style="width: 32px; height: 32px; margin-right: 8px; border-radius: 100%; object-fit: cover;"
                                                                                            class="border">
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $member->user->last_name . ' ' . $member->user->first_name }}
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </span>
                                            </span>
                                        </div>

                                        @if ($invitations != null && $invitations->count() > 0)
                                            <div>
                                                <div>
                                                    <i class="feather-users me-3"></i>
                                                    <span id="invitedContainer" class="position-relative">
                                                        {{ $invitations->count() }}
                                                        {{ __('texts.texts.people_invited.' . auth()->user()->lang) }}
                                                        @if ($invitations->count() > 0)
                                                            <div class="position-absolute bg-white p-3 rounded border"
                                                                style="top: 12px; right: 0; width: 320px;"
                                                                id="invitedPeople">
                                                                <ul style="max-height: 160px; overflow-y: scroll;">
                                                                    @foreach ($invitations as $invitation_)
                                                                        <li class="mb-2 member-li">
                                                                            <a
                                                                                href="/users/{{ $invitation_->targetUserInfo->uuid }}">
                                                                                <div class="d-flex flex-wrap align-items-center"
                                                                                    style="min-width: 200px;">
                                                                                    <div>
                                                                                        <img src="{{ $invitation_->targetUserInfo->avatar }}"
                                                                                            alt="{{ $invitation_->targetUserInfo->last_name }}_logo"
                                                                                            style="width: 32px; height: 32px; margin-right: 8px; border-radius: 100%; object-fit: cover;"
                                                                                            class="border">
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $invitation_->targetUserInfo->last_name . ' ' . $invitation_->targetUserInfo->first_name }}:
                                                                                        {{ $invitation_->status }}
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div>
                                            <div id="invitedToEventContainer">
                                                @if ($invitation != null)
                                                    @if ($invitation->status == \App\Models\EventInvitation::STATUS_NO_RESPONSE)
                                                        <b>{{ __('texts.texts.invited_to_event.' . auth()->user()->lang) }}</b>
                                                    @endif
                                                @endif
                                            </div>
                                            <button class="btn btn-success text-white" id="goingToEventButtonDetail">
                                                @if ($going)
                                                    <i class="feather-check-circle" id="goingSign"></i>
                                                @else
                                                    <i class="feather-check-circle d-none" id="goingSign"></i>
                                                @endif
                                                Going
                                            </button>
                                            <button class="btn btn-primary text-white" id="interestedInEventButtonDetail">
                                                @if ($interested)
                                                    <i class="feather-thumbs-up" id="likeSign"></i>
                                                @else
                                                    <i class="feather-thumbs-up d-none" id="likeSign"></i>
                                                @endif
                                                Interested
                                            </button>
                                            @if ($invitation != null)
                                                @if ($invitation->status == \App\Models\EventInvitation::STATUS_NO_RESPONSE)
                                                    <button class="btn btn-danger text-white" id="rejectEventButton">
                                                        <i class="feather-thumbs-down d-none" id="rejectSign"></i>
                                                        Reject
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="mt-2">
                                            <p>
                                                {{ $event->description }}
                                            </p>
                                        </div>
                                        <div class="row">
                                            @foreach (json_decode($event->files, true) as $file)
                                                <div class="col-md-3">
                                                    <img class="border cursor-pointer event-images"
                                                        style="width: 100%; aspect-ratio: 1; object-fit: cover;"
                                                        src="{{ config('aws_.aws_url.url') . '/' . config('aws_.event_images.path') . '/' . config('aws_.event_images.file_path') . '/' . $file['url'] }}"
                                                        alt="{{ $file['name'] }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
