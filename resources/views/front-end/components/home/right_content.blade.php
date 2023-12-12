<div class="col-xl-4 col-xxl-3 col-lg-4 ps-lg-0" style="mex-height: 64vh; overflow-y: scroll;">
    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
        <div class="card-body d-flex align-items-center p-4">
            <h4 class="fw-700 mb-0 font-xssss text-grey-900">
                {{ __('texts.texts.plans.' . auth()->user()->lang) }}
            </h4>
            <a href="/plan" class="fw-600 ms-auto font-xssss text-primary">
                {{ __('texts.texts.see_all.' . auth()->user()->lang) }}
            </a>
        </div>
        <div class="card-body pt-0 pb-3 overflow-hidden plan_list">
        </div>
    </div>

    {{-- events preview --}}
    @include('front-end.components.home.right_content_events')

    {{-- intakes preview --}}
    @include('front-end.components.home.right_content_intakes')
</div>

<script>
    $.ajax({
        url: '/get-plan-home',
        type: 'GET',
        processData: true,
        contentType: false,
        success: function(data) {
            let uiPlanList = '';
            let listTask = data.data;
            if (Object.keys(listTask).length) {
                for (const plan of listTask) {
                    uiPlanList += `
                        <div class="project-box w-100 p-2 mb-2 rounded"
                             style="background-color: ${plan['settings']['background_color']}">
                            <a href="{{ route('show_task') }}?id=${plan['uuid']}" style="color: black">
                                <div class="project-box-content-header">
                                    <span class="box-content-header" data-max-width="20vw"
                                  data-tooltip-title="${plan['name']}">
                                <p class="mb-1">${plan['name']}</p>
                            </span>
                                    <p class="mb-1" style="font-size: 12px; line-height: 12px">${plan['updated_at_fomat']}</p>
                                </div>
                            </a>
                        </div>
                    `;
                }
                $('.plan_list').append(uiPlanList)
            } else {
                $('.plan_list').append(`
                <div class="card-body d-flex align-items-center">
                    <h4 class="fw-700 text-grey-900 font-xssss">
                        {{ __('texts.texts.no_plans_found.' . auth()->user()->lang) }}
                    </h4>
                </div>
                `);
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
</script>
