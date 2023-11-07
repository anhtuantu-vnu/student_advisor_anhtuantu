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

        let goingToEventButtonDetail = document.getElementById("goingToEventButtonDetail");
        let interestedInEventButtonDetail = document.getElementById("interestedInEventButtonDetail");

        goingToEventButtonDetail.addEventListener("click", e => {
            handleGoingToEventDetail(e);
        });

        interestedInEventButtonDetail.addEventListener("click", e => {
            handleInterestedInEventDetail(e);
        });

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
                e.target.dataset.loading = "true";
                $.ajax({
                    type: "POST",
                    url: `/events/{{ $event->id }}/interested`,
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
                                @if (auth()->user()->uuid = $event->created_by)
                                    <div>
                                        <a href="#" class="ms-auto" id="dropdownUpdateMenuEvent_{{ $event->uuid }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
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
                                            <div class="card-body p-0 d-flex">
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
                                            {{ $event->start_hour . ':' . $event->start_minute }}
                                            - {{ $event->start_hour . ':' . $event->start_minute }}
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                                        @else
                                            {{ $event->start_hour . ':' . $event->start_minute }}
                                            - {{ $event->start_hour . ':' . $event->start_minute }}
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                                            - {{ $event->start_hour . ':' . $event->start_minute }}
                                            - {{ $event->start_hour . ':' . $event->start_minute }}
                                            {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                                        @endif
                                    </div>
                                    <div>
                                        <i class="feather-map-pin me-3"></i>
                                        {{ $event->location }}
                                    </div>
                                    <div>
                                        <i class="feather-info me-3"></i>
                                        <span id="goingCountSpan">{{ $goingCount }}</span> going, <span
                                            id="interestedCountSpan">{{ $interestedCount }}</span> interested
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div>
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
@endsection
