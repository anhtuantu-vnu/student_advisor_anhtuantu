<div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
    <div class="card-body d-flex align-items-center p-4">
        <h4 class="fw-700 mb-0 font-xssss text-grey-900">
            {{ __('texts.texts.intakes.' . auth()->user()->lang) }}
        </h4>
        <a href="/calendar?view_mode=timeGridDay" class="fw-600 ms-auto font-xssss text-primary">
            {{ __('texts.texts.see_all.' . auth()->user()->lang) }}
        </a>
    </div>
    <div id="rightSideBarIntakesContainer" style="max-height: 160px; overflow-y: scroll;">
    </div>
</div>

<script>
    let rightSideBarIntakesContainer = document.getElementById("rightSideBarIntakesContainer");
    let rightSidebarIntakes = [];
    let rightSidebarIntakeLimit = 3;
    let rightSidebarIntakePage = 1;

    function populateRightSidebarIntakes() {
        let todayDate = new Date();
        $.ajax({
            url: `/api/student-intakes?limit=${rightSidebarIntakeLimit}&page=${rightSidebarIntakePage}&weekDay=${todayDate.getDay() + 1}`,
            type: "GET",
            headers: {
                "Authorization": "Bearer " + localStorage.getItem("jwtToken"),
            },
            success: function(result) {
                if (result.meta.success) {
                    rightSidebarIntakes = result.data.intakeMembers;
                }
                initRightSidebarIntakes();
            }
        });
    }

    populateRightSidebarIntakes();

    function initRightSidebarIntakes() {
        if (rightSidebarIntakes && rightSidebarIntakes.length) {
            rightSideBarIntakesContainer.innerHTML = '';
            rightSidebarIntakes.forEach(intake => {
                rightSideBarIntakesContainer.innerHTML += `
              <div class="card-body d-flex pt-0 ps-4 pe-4 pb-3 overflow-hidden">
                <div class="me-2 p-3 rounded-xxl bg-success w-100">
                  <h4 class="fw-700 font-md ls-3 lh-1 text-white mb-0">
                    <a href="/intakes/${intake.intake.uuid}">
                      <span class="ls-1 d-block font-xsss text-white fw-600">
                        ${JSON.parse(intake.intake.subject.name)["{{ auth()->user()->lang }}"]}
                      </span>  
                    </a>${intake.intake.start_hour}:${intake.intake.start_minute == 0 ? '00': intake.intake.start_minute} - ${intake.intake.end_hour}:${intake.intake.end_minute == 0 ? '00': intake.intake.end_minute}
                  </h4>
                </div>
              </div>
              `;
            });
        } else {
            rightSideBarIntakesContainer.innerHTML = `
            <div class="card-body">
              <h4 class="fw-700 text-grey-900 font-xssss">
                {{ __('texts.texts.no_intakes_found.' . auth()->user()->lang) }}
              </h4>
            </div>
            `;
        }
    }
</script>
