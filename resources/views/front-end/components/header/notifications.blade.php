<style>
    #headerNotificationsContainer .card:hover {
        background: rgb(0, 0, 0, 0.1);
    }
</style>

<div style="max-width: 320px;">
    <h4 class="fw-700 font-xss mb-4">
        {{ __('texts.texts.notifications.' . auth()->user()->lang) }}
    </h4>

    <div id="headerNotificationsContainer" style="max-height: 320px; overflow-y: scroll;"></div>
    <div class="text-center">
        <span class="text-primary d-none cursor-pointer" id="headerLoadMoreNotifications">
            {{ __('texts.texts.load_more.' . auth()->user()->lang) }}
        </span>
    </div>
</div>

<script src="https://js.pusher.com/4.4/pusher.min.js"></script>

<script>
    document.getElementById("headerNotificationsWrapper").addEventListener("click", e => {
        e.stopPropagation();
    });

    document.getElementById("dropdownNotificationsI").addEventListener("click", e => {
        document.getElementById("headerHasNotificationDot").classList.add("d-none");
    });
</script>

<script type="text/javascript">
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        encrypted: true,
        cluster: "ap1"
    });
    var channel = pusher.subscribe('NotificationEvent');
    channel.bind('notification-channel-{{ auth()->user()->uuid }}', function(data) {
        console.log('realtime', data);
        let notificationPreviewContainer = document.getElementById("notificationPreviewContainer");

        let originUser = data.origin_user_info;

        let noti_text = '';
        if (data.type == "{{ App\Models\Notification::EVENT_TYPES['GOING_TO_EVENT'] }}") {
            noti_text = "{{ __('texts.texts.going_to_event_notification.' . auth()->user()->lang) }}";
        }
        if (data.type == "{{ App\Models\Notification::EVENT_TYPES['INTERESTED_IN_EVENT'] }}") {
            noti_text = "{{ __('texts.texts.interested_in_event_notification.' . auth()->user()->lang) }}";
        }
        if (data.type == "{{ App\Models\Notification::EVENT_TYPES['INVITED_TO_EVENT'] }}") {
            noti_text = "{{ __('texts.texts.invited_to_event_notification.' . auth()->user()->lang) }}: " +
                `<b data-notification-id="${data.id}" data-url="${data.target_url}">${data.event.name}</b>`;
        }
        if (data.type ==
            "{{ App\Models\Notification::EVENT_TYPES['RESPONDED_TO_EVENT_GOING'] }}") {
            noti_text =
                "{{ __('texts.texts.responded_going_to_event_notification.' . auth()->user()->lang) }}" +
                `<br/><b data-notification-id="${data.id}" data-url="${data.target_url}">${data.event.name}</b>`;
        }
        if (data.type ==
            "{{ App\Models\Notification::EVENT_TYPES['RESPONDED_TO_EVENT_REJECTED'] }}") {
            noti_text = "{{ __('texts.texts.responded_reject_event_notification.' . auth()->user()->lang) }}" +
                `<br/><b data-notification-id="${data.id}" data-url="${data.target_url}">${data.event.name}</b>`;
        }
        if (data.type ==
            "{{ App\Models\Notification::EVENT_TYPES['CANCEL_EVENT'] }}") {
            noti_text = "{{ __('texts.texts.event_canceled_notification.' . auth()->user()->lang) }}" +
                `<br/><b data-notification-id="${data.id}" data-url="${data.target_url}">${data.event.name}</b>`;
        }

        notificationPreviewContainer.innerHTML = `
        <div class="preview-notification card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1"
            data-notification-id="${data.id}" data-url="${data.target_url}">
            <img src="${originUser.avatar}" alt="${originUser.last_name}_logo"
                class="border w40 position-absolute left-0"
                style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;"
                data-notification-id="${data.id}" data-url="${data.target_url}">
            <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${data.id}"
                data-url="${data.target_url}">
                ${originUser.last_name + " " + originUser.first_name}
                <span class="text-grey-600 font-xsssss fw-600 float-right mt-1"
                    data-notification-id="${data.id}" data-url="${data.target_url}">
                    ${formatRelativeTime(data.created_at)}</span>
            </h5>
            <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${data.id}"
                data-url="${data.target_url}">
                ${noti_text}
            </h6>
        </div>
        `;

        notificationPreviewContainer.classList.remove("d-none");
        setTimeout(() => {
            notificationPreviewContainer.classList.add("d-none");
        }, 3000);
        addNotificationClickPreviewEvent();
        prependHeaderNotification(data);
    });

    function addNotificationClickPreviewEvent() {
        let notificationCards = document.querySelectorAll('.preview-notification');
        Array.from(notificationCards).forEach(item => {
            item.addEventListener("click", e => {
                markNotificationAsRead(e.target.dataset.notificationId, e.target.dataset.url)
            });
        });
    }
</script>

<script>
    let notificationsLimit = 10,
        notificationsCurrenPage = 1;
    let headerNotifications = [];
    let hasUnreadNotification = false;

    let headerNotificationsContainer = document.getElementById("headerNotificationsContainer");
    let headerLoadMoreNotifications = document.getElementById("headerLoadMoreNotifications");

    headerLoadMoreNotifications.addEventListener("click", e => {
        notificationsCurrenPage++;
        getUserNotifications();
    });

    function getUserNotifications() {
        $.ajax({
            type: "GET",
            url: `/api/user-notifications?page=${notificationsCurrenPage}&limit=${notificationsLimit}`,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('jwtToken'),
            },
            error: function(err) {
                console.log('cannot get header notifications', err);
            },
            success: function(data) {
                if (data.meta.success) {
                    headerNotifications = headerNotifications.concat(data.data.notifications);
                    hasUnreadNotification = headerNotifications.find(item => item.read === 0) != null;

                    if (data.data.notifications.length && data.data.notifications.length >=
                        notificationsLimit) {
                        headerLoadMoreNotifications.classList.remove("d-none");
                    } else {
                        headerLoadMoreNotifications.classList.add("d-none");
                    }

                    if (notificationsCurrenPage == 1 && hasUnreadNotification) {
                        document.getElementById("headerHasNotificationDot").classList.remove("d-none");
                    } else {
                        document.getElementById("headerHasNotificationDot").classList.add("d-none");
                    }

                    populateHeaderNotifications();
                }
            },
        });
    }

    getUserNotifications();

    function prependHeaderNotification(notification) {
        let oldNotiHtml = headerNotificationsContainer.innerHTML;
        if (notification.type == "{{ App\Models\Notification::EVENT_TYPES['GOING_TO_EVENT'] }}") {
            oldNotiHtml = getNotificationTypeGoingToEvent(notification) + oldNotiHtml;
        }
        if (notification.type == "{{ App\Models\Notification::EVENT_TYPES['INTERESTED_IN_EVENT'] }}") {
            oldNotiHtml = getNotificationTypeInterestedInEvent(
                notification) + oldNotiHtml;
        }
        if (notification.type == "{{ App\Models\Notification::EVENT_TYPES['INVITED_TO_EVENT'] }}") {
            oldNotiHtml = getNotificationTypeInvitedToEvent(
                notification) + oldNotiHtml;
        }
        if (notification.type ==
            "{{ App\Models\Notification::EVENT_TYPES['RESPONDED_TO_EVENT_GOING'] }}") {
            oldNotiHtml = getNotificationTypeRespondedGoingToEvent(
                notification) + oldNotiHtml;
        }
        if (notification.type ==
            "{{ App\Models\Notification::EVENT_TYPES['RESPONDED_TO_EVENT_REJECTED'] }}") {
            oldNotiHtml = getNotificationTypeRespondedRejectedEvent(
                notification) + oldNotiHtml;
        }
        if (notification.type ==
            "{{ App\Models\Notification::EVENT_TYPES['CANCEL_EVENT'] }}") {
            oldNotiHtml = getNotificationTypeCanceledEvent(
                notification) + oldNotiHtml;
        }

        headerNotificationsContainer.innerHTML = oldNotiHtml;
        document.getElementById("headerHasNotificationDot").classList.remove("d-none");
        addNotificationClickEvent();

    }

    function populateHeaderNotifications() {
        if (!headerNotifications.length) {
            headerNotificationsContainer.innerHTML = `
            <div class="card bg-transparent-card w-100 border-0 mb-3">
                <h6 class="text-grey-500 fw-500 font-xssss lh-4">
                    {{ __('texts.texts.no_notifications_found.' . auth()->user()->lang) }}
                </h6>
            </div>
            `;
            return;
        }

        headerNotifications.forEach(notification => {
            if (!notification.populated) {
                notification.populated = true;
                if (notification.type == "{{ App\Models\Notification::EVENT_TYPES['GOING_TO_EVENT'] }}") {
                    headerNotificationsContainer.innerHTML += getNotificationTypeGoingToEvent(notification);
                }
                if (notification.type == "{{ App\Models\Notification::EVENT_TYPES['INTERESTED_IN_EVENT'] }}") {
                    headerNotificationsContainer.innerHTML += getNotificationTypeInterestedInEvent(
                        notification);
                }
                if (notification.type == "{{ App\Models\Notification::EVENT_TYPES['INVITED_TO_EVENT'] }}") {
                    headerNotificationsContainer.innerHTML += getNotificationTypeInvitedToEvent(
                        notification);
                }
                if (notification.type ==
                    "{{ App\Models\Notification::EVENT_TYPES['RESPONDED_TO_EVENT_GOING'] }}") {
                    headerNotificationsContainer.innerHTML += getNotificationTypeRespondedGoingToEvent(
                        notification);
                }
                if (notification.type ==
                    "{{ App\Models\Notification::EVENT_TYPES['RESPONDED_TO_EVENT_REJECTED'] }}") {
                    headerNotificationsContainer.innerHTML += getNotificationTypeRespondedRejectedEvent(
                        notification);
                }
                if (notification.type ==
                    "{{ App\Models\Notification::EVENT_TYPES['CANCEL_EVENT'] }}") {
                    headerNotificationsContainer.innerHTML += getNotificationTypeCanceledEvent(
                        notification);
                }
            }
        });

        addNotificationClickEvent();
    }

    function addNotificationClickEvent() {
        let notificationCards = document.querySelectorAll('#headerNotificationsContainer .card');
        Array.from(notificationCards).forEach(item => {
            item.addEventListener("click", e => {
                markNotificationAsRead(e.target.dataset.notificationId, e.target.dataset.url)
            });
        });
    }

    function getNotificationTypeGoingToEvent(notification) {
        let originUser = notification.origin_user_info;
        let unreadStyle = "";
        if (!notification.read) {
            unreadStyle = 'style="background: rgb(0, 0, 0, 0.1);"';
        }
        return `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1" data-notification-id="${notification.id}" data-url="${notification.target_url}" ${unreadStyle}>
                <img src="${originUser.avatar}" alt="${originUser.last_name}_logo" class="border w40 position-absolute left-0" style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    ${originUser.last_name + " " + originUser.first_name}
                    <span class="text-grey-600 font-xsssss fw-600 float-right mt-1" data-notification-id="${notification.id}" data-url="${notification.target_url}"> ${formatRelativeTime(notification.created_at)}</span></h5>
                <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    {{ __('texts.texts.going_to_event_notification.' . auth()->user()->lang) }}
                </h6>
            </div>
            `;
    }

    function getNotificationTypeInterestedInEvent(notification) {
        let originUser = notification.origin_user_info;
        let unreadStyle = "";
        if (!notification.read) {
            unreadStyle = 'style="background: rgb(0, 0, 0, 0.1);"';
        }
        return `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1" data-notification-id="${notification.id}" data-url="${notification.target_url}" ${unreadStyle}>
                <img src="${originUser.avatar}" alt="${originUser.last_name}_logo" class="border w40 position-absolute left-0" style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    ${originUser.last_name + " " + originUser.first_name}
                    <span class="text-grey-600 font-xsssss fw-600 float-right mt-1" data-notification-id="${notification.id}" data-url="${notification.target_url}"> ${formatRelativeTime(notification.created_at)}</span></h5>
                <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    {{ __('texts.texts.interested_in_event_notification.' . auth()->user()->lang) }}
                </h6>
            </div>
            `;
    }

    function getNotificationTypeInvitedToEvent(notification) {
        let originUser = notification.origin_user_info;
        let event = notification.event;
        let unreadStyle = "";
        if (!notification.read) {
            unreadStyle = 'style="background: rgb(0, 0, 0, 0.1);"';
        }
        return `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1" data-notification-id="${notification.id}" data-url="${notification.target_url}" ${unreadStyle}>
                <img src="${originUser.avatar}" alt="${originUser.last_name}_logo" class="border w40 position-absolute left-0" style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    ${originUser.last_name + " " + originUser.first_name}
                    <span class="text-grey-600 font-xsssss fw-600 float-right mt-1" data-notification-id="${notification.id}" data-url="${notification.target_url}"> ${formatRelativeTime(notification.created_at)}</span></h5>
                <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    {{ __('texts.texts.invited_to_event_notification.' . auth()->user()->lang) }}: <b data-notification-id="${notification.id}" data-url="${notification.target_url}">${event.name}</b>
                </h6>
            </div>
            `;
    }

    function getNotificationTypeRespondedGoingToEvent(notification) {
        let originUser = notification.origin_user_info;
        let event = notification.event;
        let unreadStyle = "";
        if (!notification.read) {
            unreadStyle = 'style="background: rgb(0, 0, 0, 0.1);"';
        }
        return `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1" data-notification-id="${notification.id}" data-url="${notification.target_url}" ${unreadStyle}>
                <img src="${originUser.avatar}" alt="${originUser.last_name}_logo" class="border w40 position-absolute left-0" style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    ${originUser.last_name + " " + originUser.first_name}
                    <span class="text-grey-600 font-xsssss fw-600 float-right mt-1" data-notification-id="${notification.id}" data-url="${notification.target_url}"> ${formatRelativeTime(notification.created_at)}</span></h5>
                <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    {{ __('texts.texts.responded_going_to_event_notification.' . auth()->user()->lang) }} <br/>
                    <b data-notification-id="${notification.id}" data-url="${notification.target_url}">${event.name}</b>
                </h6>
            </div>
            `;
    }

    function getNotificationTypeRespondedRejectedEvent(notification) {
        let originUser = notification.origin_user_info;
        let event = notification.event;
        let unreadStyle = "";
        if (!notification.read) {
            unreadStyle = 'style="background: rgb(0, 0, 0, 0.1);"';
        }
        return `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1" data-notification-id="${notification.id}" data-url="${notification.target_url}" ${unreadStyle}>
                <img src="${originUser.avatar}" alt="${originUser.last_name}_logo" class="border w40 position-absolute left-0" style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    ${originUser.last_name + " " + originUser.first_name}
                    <span class="text-grey-600 font-xsssss fw-600 float-right mt-1" data-notification-id="${notification.id}" data-url="${notification.target_url}"> ${formatRelativeTime(notification.created_at)}</span></h5>
                <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    {{ __('texts.texts.responded_reject_event_notification.' . auth()->user()->lang) }} <br/>
                    <b data-notification-id="${notification.id}" data-url="${notification.target_url}">${event.name}</b>
                </h6>
            </div>
            `;
    }

    function getNotificationTypeCanceledEvent(notification) {
        let originUser = notification.origin_user_info;
        let event = notification.event;
        let unreadStyle = "";
        if (!notification.read) {
            unreadStyle = 'style="background: rgb(0, 0, 0, 0.1);"';
        }
        return `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3 cursor-pointer p-1" data-notification-id="${notification.id}" data-url="${notification.target_url}" ${unreadStyle}>
                <img src="${originUser.avatar}" alt="${originUser.last_name}_logo" class="border w40 position-absolute left-0" style="aspect-ratio: 1; object-fit: cover; border-radius: 100%;" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                <h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    ${originUser.last_name + " " + originUser.first_name}
                    <span class="text-grey-600 font-xsssss fw-600 float-right mt-1" data-notification-id="${notification.id}" data-url="${notification.target_url}"> ${formatRelativeTime(notification.created_at)}</span></h5>
                <h6 class="text-grey-700 fw-500 font-xssss lh-4" data-notification-id="${notification.id}" data-url="${notification.target_url}">
                    {{ __('texts.texts.event_canceled_notification.' . auth()->user()->lang) }} <br/>
                    <b data-notification-id="${notification.id}" data-url="${notification.target_url}">${event.name}</b>
                </h6>
            </div>
            `;
    }

    function markNotificationAsRead(notificationId, targetUrl) {
        $.ajax({
            type: "POST",
            url: `/api/notifications/${notificationId}/read`,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('jwtToken'),
            },
            error: function(err) {
                console.log('cannot mark read notification', err);
            },
            success: function(data) {
                console.log('marked read notification')
            },
            complete: function() {
                window.location.href = targetUrl;
            }
        });
    }
</script>
