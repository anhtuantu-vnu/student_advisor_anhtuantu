<div class="mt-5">
    <h3 class="fw-500 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 24px !important;">
        {{ __('texts.texts.events.' . auth()->user()->lang) }}
    </h3>
    <div class="row" id="event-results-container">
    </div>
    <div class="mt-2">
        <span class="text-primary text-decoration-underline" id="loadMoreSearchEvents">
            <small class="cursor-pointer">
                {{ __('texts.texts.load_more.' . auth()->user()->lang) }}
            </small>
        </span>
    </div>
</div>

<script>
    let loadMoreSearchEvents = document.getElementById("loadMoreSearchEvents");
    let eventResultsContainer = document.getElementById("event-results-container");

    let eventSearchCurrentPage = 1;
    let eventSearchLimit = 6;
    let foundSearchEvents = [];

    loadMoreSearchEvents.addEventListener("click", e => {
        eventSearchCurrentPage++;
        searchEvents();
    });

    function searchEvents() {
        let params = new URL(window.location.href);
        let search = params.searchParams.get("search");

        $.ajax({
            type: "GET",
            url: `/search/events?page=${eventSearchCurrentPage}&limit=${eventSearchLimit}&search=${search}`,
            success: function(data) {
                if (data.meta.success) {
                    foundSearchEvents = foundSearchEvents.concat(data.data.foundEvents);
                    if (data.data.foundEvents.length < eventSearchLimit) {
                        loadMoreSearchEvents.classList.add("d-none");
                    } else {
                        loadMoreSearchEvents.classList.remove("d-none");
                    }

                    if (!foundSearchEvents.length) {
                        loadMoreSearchEvents.classList.add("d-none");
                        eventResultsContainer.innerHTML = `
                        <div class="col-12 bg-light rounded">
                            <div class="card-body">
                              {{ __('texts.texts.no_events_found.' . auth()->user()->lang) }}
                            </div>
                        </div>
                        `;
                    } else {
                        populateFoundEvents();
                    }
                }
            },
        });
    }

    searchEvents();

    function getEventMonthSearch(event) {
        let eventDate = new Date(event.start_date);
        let monthsArr = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        return monthsArr[eventDate.getMonth()];
    }

    function getEventDateSearch(event) {
        let eventDate = new Date(event.start_date);
        return eventDate.getDate();
    }

    function populateFoundEvents() {
        foundSearchEvents.forEach(item => {
            if (!item.shown) {
                item.shown = true;

                eventResultsContainer.innerHTML += `
                <div class="col-md-4">
                  <div class="card-body d-flex pt-0 ps-4 pe-4 pb-3 overflow-hidden">
                    <div class="me-2 p-3 rounded-xxl" style="background-color: ${item.color};">
                      <h4 class="fw-700 font-lg ls-3 lh-1 text-white mb-0">
                        <span class="ls-1 d-block font-xsss text-white fw-600 text-uppercase">
                          ${getEventMonthSearch(item)}
                        </span>${getEventDateSearch(item)}
                      </h4>
                    </div>
                    <a href="/events/${item.uuid}">
                      <div>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-2 ${item.active == 0 ? 'text-decoration-line-through': ''}">
                          ${item.name}
                          <span class="d-block font-xsssss fw-500 mt-1 lh-4 text-grey-500">
                            ${item.location}
                          </span>
                        </h4>
                      </div>
                    </a>
                  </div>
                </div>
                `;
            }
        });
    }
</script>
