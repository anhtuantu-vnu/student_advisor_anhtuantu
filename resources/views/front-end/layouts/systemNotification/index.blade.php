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
    <script>
        let loadMoreSystemNotifications = document.getElementById("loadMoreSystemNotifications");
        let systemNotificationsContainer = document.getElementById("systemNotificationsContainer");
        let systemNotificationLimit = 10;
        let systemCurrentPage = 1;
        let systemNotifications = [];

        loadMoreSystemNotifications.addEventListener("click", e => {
            systemCurrentPage++;
            getSystemNotifications();
        });

        function getSystemNotifications() {
            $.ajax({
                url: `/get-system-notifications?page=${systemCurrentPage}&limit=${systemNotificationLimit}&active=1`,
                type: "GET",
                success: function(result) {
                    if (result.meta.success) {
                        if (!result.data.systemNotifications.length) {
                            loadMoreSystemNotifications.classList.add("d-none");
                        } else {
                            populateSystemNotifications(result.data.systemNotifications);
                        }
                    } else {

                    }
                },
                error: function(error) {
                    console.log('get system notification error', error);
                },
            });
        }

        getSystemNotifications();

        function populateSystemNotifications(data) {
            let currentHtml = systemNotificationsContainer.innerHTML;

            data.forEach(item => {
                currentHtml += `
                <div class="col-md-4 p-1" data-id="${item.id}" data-title="${item.title}">
                  <div class="bg-white p-3 rounded border cursor-pointer notification-item" data-id="${item.id}" data-title="${item.title}" id="notification_${item.id}">
                    <div data-id="${item.id}" data-title="${item.title}">
                      <b data-id="${item.id}" data-title="${item.title}">${item.title}</b>
                    </div>
                    <div class="d-none" data-id="${item.id}" data-title="${item.title}" id="notification_content_${item.id}">
                      ${"{{ auth()->user()->lang }}" == "vi" ? item.content: item.content_en}
                    </div>
                  </div>
                </div>
                `;
            });

            systemNotificationsContainer.innerHTML = currentHtml;
            addNotificationItemEvent();
        }

        function addNotificationItemEvent() {
            let notificationItems = document.querySelectorAll(".notification-item");

            Array.from(notificationItems).forEach(item => {
                item.addEventListener("click", e => {
                    let notiId = e.target.dataset.id;
                    let notiTitle = e.target.dataset.title;
                    document.getElementById("notificationDetailModalOpener").click();

                    document.getElementById("notificationDetailModalTitle").innerText = notiTitle;
                    document.getElementById("notificationItemContent").innerHTML = document.getElementById(
                        "notification_content_" + notiId).innerHTML;
                });
            });
        }
    </script>
@endpush

@section('modal')
    <div data-bs-toggle="modal" data-bs-target="#notificationDetailModal" class="d-none" id="notificationDetailModalOpener">
    </div>

    <div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="notificationDetailModalTitle"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                    <div id="notificationItemContent">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ __('texts.texts.system_notifications.' . auth()->user()->lang) }}
                            </h1>
                            <div class="mt-3">
                                <div class="row" id="systemNotificationsContainer">
                                </div>
                                <div class="mt-2">
                                    <span class="cursor-pointer text-decoration-underline" id="loadMoreSystemNotifications">
                                        {{ __('texts.texts.load_more.' . auth()->user()->lang) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
