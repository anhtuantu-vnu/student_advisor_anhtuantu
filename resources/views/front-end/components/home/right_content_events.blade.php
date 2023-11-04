<div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
    <div class="card-body d-flex align-items-center p-4">
        <h4 class="fw-700 mb-0 font-xssss text-grey-900">
            {{ __('texts.texts.events.' . auth()->user()->lang) }}
        </h4>
        <a href="default-event.html" class="fw-600 ms-auto font-xssss text-primary">
            {{ __('texts.texts.see_all.' . auth()->user()->lang) }}
        </a>
    </div>
    <div id="rightSideBarEventsContainer" style="max-height: 160px; overflow-y: scroll;">
    </div>
</div>

<script>
    function loadEventsRightSideBar() {
        if (homeEvents && homeEvents.length) {
            rightSideBarEventsContainer.innerHTML = '';
            var firstThreeEvents = homeEvents.slice(0, 3);
            firstThreeEvents.forEach(event => {
                rightSideBarEventsContainer.innerHTML += `
                <div class="card-body d-flex pt-0 ps-4 pe-4 pb-3 overflow-hidden">
                  <div class="bg-success me-2 p-3 rounded-xxl">
                    <h4 class="fw-700 font-lg ls-3 lh-1 text-white mb-0">
                      <span class="ls-1 d-block font-xsss text-white fw-600">
                        FEB
                      </span>22
                    </h4>
                  </div>
                  <h4 class="fw-700 text-grey-900 font-xssss mt-2">
                    ${event.name}
                    <span class="d-block font-xsssss fw-500 mt-1 lh-4 text-grey-500">
                      ${event.location}
                    </span>
                  </h4>
                </div>
                `;
            });
        } else {
            rightSideBarEventsContainer.innerHTML = `
            <div class="card-body d-flex align-items-center p-4">
              <h4 class="fw-700 text-grey-900 font-xssss mt-2">
                <span
                  {{ __('texts.texts.no_events_found.' . auth()->user()->lang) }}
                </span>
              </h4>
            </div>
            `;
        }
    }
</script>
