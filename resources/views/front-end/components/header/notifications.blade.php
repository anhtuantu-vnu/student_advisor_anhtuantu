<style>
    #headerNotificationsContainer .card:hover {
        background: rgb(0, 0, 0, 0.1);
    }
</style>

<div>
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

<script>
    document.getElementById("headerNotificationsWrapper").addEventListener("click", e => {
        e.stopPropagation();
    });

    document.getElementById("dropdownNotificationsI").addEventListener("click", e => {
        document.getElementById("headerHasNotificationDot").classList.add("d-none");
    });
</script>

<script>
    let notificationsLimit = 1,
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
                console.log('data', data);
                if (data.meta.success) {
                    headerNotifications = headerNotifications.concat(data.data.notifications);
                    hasUnreadNotification = headerNotifications.find(item => item.read === 0) != null;

                    if (data.data.notifications.length) {
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

    function populateHeaderNotifications() {
        if (!headerNotifications.length) {
            headerNotificationsContainer.innerHTML = `
            <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3">
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
