@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/model_detail_task.css") }}"/>
@endsection

@push('js_page')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        //logic drag drop
        let currentTarget = null;

        function initDropDrag() {
            let boxDraggebles = document.querySelectorAll(".draggable_item");
            let targetList = document.querySelectorAll(".target");
            currentTarget = null;
            targetList.forEach(target => {
                target.addEventListener("dragstart", boxEnter);
                target.addEventListener("dragend", boxLeave);
            })

            boxDraggebles.forEach((box) => {
                box.addEventListener("dragenter", dragEnter);
                box.addEventListener("dragover", dragOver);
                box.addEventListener("drop", dropBox);
            })
            setHeightForDraggableItem();
        }

        function boxEnter() {
            this.classList.add("dragstart");
            this.classList.add("hide");
            currentTarget = this;
        }

        function boxLeave() {
            this.classList.remove("dragstart", "hide");
        }

        function dragEnter(event) {
            event.preventDefault();
        }

        function dragOver(event) {
            event.preventDefault();
        }

        function dropBox() {
            this.append(currentTarget);
        }

        //set height for draggable_item
        function setHeightForDraggableItem() {
            let heightDraggable = document.querySelector('.to_do_task').offsetHeight;
            document.querySelectorAll('.draggable_item').forEach(function (draggable) {
                draggable.style.minHeight = `${heightDraggable + 70}px`;
            })
            document.querySelector('.middle-sidebar-left').style.maxWidth = '1240px';
        }
        initDropDrag();
        //logic show hide create task
        function showInputCreateTask() {
            document.querySelector('.btn_create_task').setAttribute("disable", "");
            document.querySelector('.create_task').classList.remove('d-none');
            document.querySelector('.form_create_plan').focus();
        }

        function handleFocusoutTextarea() {
            document.querySelector('.create_task').classList.add('d-none');
            document.querySelector('#input_create_task').value = '';
            document.querySelector('.btn_create_task').removeAttribute("disable");
        }

        //Create task
        let url = new URL(window.location.href);
        let idPlan = url.searchParams.get("id");
        $("#input_create_task").keydown(function(e) {
            if(e.keyCode == 13) {
                let data = {
                    _token: '{{csrf_token()}}',
                    task: {
                        'plan_id': idPlan,
                        'name': $("#input_create_task").val()
                    }
                };

                $.ajax({
                    url: "/to-do",
                    method: "post",
                    data: data
                }).done(function(res) {
                    let data = res.data;
                    let taskHtml = `<div
                                class="p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3 target"
                                data-bs-toggle="modal" data-bs-target="#ModelTask"
                                draggable="true">
                                <div class="d-flex justify-content-between align-content-center">
                                    <h4 class="font-xsss fw-700 text-grey-900 mb-2 d-block">${data.name}</h4>
                                    <i class="feather-trash-2" style="font-size: 18px"></i>
                                </div>
                                ${data.description ? `<p class="font-xssss lh-24 fw-500 text-grey-500 mt-2 d-block mb-3">${data.description}</p>` : ''}
                                <span
                                    class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block me-1"
                                    style="color: white; background-color: #1e74fd"
                                >${data.type}</span>
                                ${data.user_assign ? `
                                    <ul class="memberlist mt-4 mb-2 ms-0">
                                        <li><a href="#"><img src="${data.user_assign.avatar}" alt="user" class="d-inline-block" style="border-radius: 50%; width: 24px; height: 24px"></a></li>
                                        <li class="ps-2 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">${data.user_assign.first_name + data.user_assign.last_name} assigned</a></li>
                                    </ul>
                                ` : ''}
                            </div>`;
                    $(".to_do_task").append(taskHtml);
                    $(".alert_create_task").addClass('d-none')
                    handleFocusoutTextarea();
                    initDropDrag();
                }).fail(function(xhr, status, error) {
                    console.log(error);
                });
            }
        });

    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active to_do">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            {{--LAYOUT TO DO--}}
                            <div class="col-lg-3 col-xl-3 col-md-6 mb-2 mt-2 box_draggable">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="p-3 border-top-lg border-size-lg border-primary p-0">
                                        <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">{{ __('texts.texts.to_do.' . auth()->user()->lang) }}</span>
                                            <button class="float-right btn-round-sm bg-greylight btn_create_task"
                                               onclick="showInputCreateTask()" style="border: none"><i class="feather-plus font-xss text-grey-900"></i></button></h4>
                                    </div>
                                    <div class="to_do_task">
                                        @if(count($tasks['tasks_to_do']))
                                            @include('front-end.layouts.task.task', ['dataTask' => $tasks['tasks_to_do'], 'key' => 'tasks_to_do', 'type' => 'Task To Do', 'backgroundTag' => "#1e74fd"])
                                        @endif
                                    </div>
                                    <div class="rounded-3 create_task pb-3 ps-3 pe-3 d-none">
                                        <div class="input_create_task rounded-3">
{{--                                            <button class="btn_post_task"><i class="feather-paperclip icon_create_plan"></i></button>--}}
                                            <textarea
                                                id="input_create_task" class="form_create_plan mb-0 rounded-3 p-2"
                                                placeholder="{{ __('texts.texts.what_needs_to_be_done.' . auth()->user()->lang)}}"
                                                onfocusout="handleFocusoutTextarea()"
                                                maxlength="255"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--LAYOUT IN PROGRESS--}}
                            <div class="col-lg-3 col-xl-3 col-md-6 mb-2 mt-2 box_draggable">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="p-3 border-top-lg border-size-lg border-warning p-0">
                                        <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">{{ __('texts.texts.in_process.' . auth()->user()->lang) }}</span>
                                        </h4>
                                    </div>
                                    @if(count($tasks['tasks_in_process']))
                                        @include('front-end.layouts.task.task', ['dataTask' => $tasks['tasks_in_process'], 'key' => 'tasks_in_process', 'type' => 'Task In Process', 'backgroundTag' => "#fe9431"])
                                    @endif
                                </div>
                            </div>

                            {{--LAYOUT REVIEW--}}
                            <div class="col-lg-3 col-xl-3 col-md-6 mb-2 mt-2 box_draggable">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="p-3 border-top-lg border-size-lg border-secondary p-0">
                                        <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">{{ __('texts.texts.review.' . auth()->user()->lang) }}</span>
                                        </h4>
                                    </div>
                                    @if(count($tasks['task_review']))
                                        @include('front-end.layouts.task.task', ['dataTask' => $tasks['task_review'], 'key' => 'task_review', 'type' => 'Task Review', 'backgroundTag' => "#673bb7"])
                                    @endif
                                </div>
                            </div>

                            {{--LAYOUT DONE--}}
                            <div class="col-lg-3 col-xl-3 col-md-6 mb-2 mt-2 box_draggable">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="p-3 border-top-lg border-size-lg border-success p-0">
                                        <h4>
                                            <span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">{{ __('texts.texts.done.' . auth()->user()->lang) }}</span>
                                        </h4>
                                    </div>
                                    @if(count($tasks['task_done']))
                                        @include('front-end.layouts.task.task', ['dataTask' => $tasks['task_done'], 'key' => 'task_done', 'type' => 'Task Done', 'backgroundTag' => '#10d876'])
                                    @endif
                                </div>
                            </div>

                            {{--PAGE EMPTY TASK--}}
                            @if(!count($tasks['tasks_to_do']) && !count($tasks['tasks_in_process'])
                                && !count($tasks['task_review']) && !count($tasks['task_done']))
                                <div class="alert_create_task" style="height: 76vh;
                                        display: flex; text-align: center; justify-content: center;
                                        align-items: center; flex-direction: column;
                                        width: fit-content; margin: auto">
                                    <div class="icon_create_task">
                                        <svg width="310" height="231" viewBox="0 0 310 231" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M148.788 0.0189601C172.49 -0.260808 194.916 2.53741 217.424 9.61882C250.006 19.8696 297.683 19.1546 308.349 50.2196C319.008 81.2657 275.15 104.694 260.151 134.068C248.087 157.695 249.709 187.472 229.263 205.107C207.822 223.599 177.386 234.241 148.788 230.116C121.577 226.191 105.975 200.307 84.2662 184.193C63.5657 168.827 38.0471 159.531 24.702 138.006C9.10671 112.852 -7.96708 81.6841 4.04815 54.8064C16.0762 27.9 52.846 22.0244 81.3775 11.2246C103.107 2.99965 125.408 0.294929 148.788 0.0189601Z"
                                                  fill="#2684FF"></path>
                                            <g filter="url(#empty-board-shadow)">
                                                <rect x="24" y="20" width="262" height="178" rx="7"
                                                      fill="var(--ds-surface, white)"
                                                      stroke="var(--ds-background-accent-gray-subtlest, #EBECF0)"></rect>
                                            </g>
                                            <rect x="39" y="41" width="64" height="8" rx="4"
                                                  fill="var(--ds-background-accent-gray-subtler, #EBECF0)"></rect>
                                            <rect x="189" y="41" width="24" height="8" rx="4"
                                                  fill="var(--ds-background-accent-gray-subtler, #EBECF0)"></rect>
                                            <rect x="217" y="41" width="24" height="8" rx="4"
                                                  fill="var(--ds-background-accent-gray-subtler, #EBECF0)"></rect>
                                            <rect x="245" y="41" width="24" height="8" rx="4"
                                                  fill="var(--ds-background-accent-gray-subtler, #EBECF0)"></rect>
                                            <rect x="39" y="63" width="72" height="84" rx="4"
                                                  fill="var(--ds-surface-sunken, #EBECF0)"></rect>
                                            <rect x="43" y="67" width="22" height="4" rx="2" fill="#C1C7D0"></rect>
                                            <rect x="43" y="75" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="49" y="81" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="49" y="89" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="91" y="91" width="12" height="12" rx="6" fill="#0065FF"></rect>
                                            <rect x="43" y="111" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="49" y="117" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="49" y="125" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="91" y="127" width="12" height="12" rx="6" fill="#6554C0"></rect>
                                            <rect x="119" y="63" width="72" height="120" rx="4"
                                                  fill="var(--ds-surface-sunken, #EBECF0)"></rect>
                                            <rect x="123" y="67" width="22" height="4" rx="2" fill="#4C9AFF"></rect>
                                            <rect x="123" y="75" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="129" y="81" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="129" y="89" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="171" y="91" width="12" height="12" rx="6" fill="#36B37E"></rect>
                                            <rect x="123" y="111" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="129" y="117" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="129" y="125" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="171" y="127" width="12" height="12" rx="6" fill="#FFAB00"></rect>
                                            <rect x="123" y="147" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="129" y="153" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="129" y="161" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="171" y="163" width="12" height="12" rx="6" fill="#0065FF"></rect>
                                            <rect x="199" y="63" width="72" height="120" rx="4"
                                                  fill="var(--ds-surface-sunken, #EBECF0)"></rect>
                                            <rect x="203" y="67" width="22" height="4" rx="2" fill="#36B37E"></rect>
                                            <rect x="203" y="75" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="209" y="81" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="209" y="89" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="251" y="91" width="12" height="12" rx="6" fill="#6554C0"></rect>
                                            <rect x="203" y="111" width="64" height="32" rx="4" fill="#36B37E"></rect>
                                            <path
                                                d="M229.735 127.322C229.554 127.13 229.305 127.017 229.042 127.008C228.779 126.998 228.522 127.093 228.328 127.272C228.135 127.451 228.019 127.699 228.007 127.962C227.996 128.225 228.088 128.483 228.265 128.678L231.877 132.597C232.414 133.123 233.214 133.123 233.711 132.627L234.075 132.268C235.39 130.975 236.703 129.681 238.014 128.385L238.054 128.345C239.28 127.137 240.499 125.923 241.712 124.702C241.893 124.512 241.993 124.259 241.989 123.996C241.985 123.734 241.878 123.483 241.691 123.299C241.504 123.115 241.252 123.012 240.99 123.011C240.727 123.011 240.475 123.114 240.288 123.298C239.081 124.512 237.867 125.721 236.648 126.923L236.608 126.963C235.351 128.205 234.093 129.446 232.833 130.685L229.735 127.322V127.322Z"
                                                fill="white"></path>
                                            <rect x="203" y="147" width="64" height="32" rx="4"
                                                  fill="var(--ds-surface-raised, white)"></rect>
                                            <rect x="209" y="153" width="52" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="209" y="161" width="24" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="251" y="163" width="12" height="12" rx="6" fill="#36B37E"></rect>
                                            <rect x="160" y="102" width="62" height="30" rx="3"
                                                  fill="var(--ds-surface-raised, white)"
                                                  stroke="var(--ds-background-accent-gray-subtlest, #EBECF0)"
                                                  stroke-width="2"></rect>
                                            <rect x="165.5" y="107.5" width="51" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="165.5" y="115.5" width="23" height="2" rx="1"
                                                  fill="var(--ds-background-accent-gray-subtler, #DFE1E6)"></rect>
                                            <rect x="208" y="118" width="10" height="10" rx="5" fill="#FFAB00"></rect>
                                            <filter id="empty-board-shadow">
                                                <feDropShadow dx="2" dy="2" stdDeviation="8" flood-color="#000"
                                                              flood-opacity="0.12"></feDropShadow>
                                            </filter>
                                        </svg>
                                    </div>
                                    <div class="text_create_task">
                                        <h2>{{ __('texts.texts.visualize.' . auth()->user()->lang) }}</h2>
                                        <p>{{ __('texts.texts.track_organize.' . auth()->user()->lang) }}</p>
                                        <button onclick="showInputCreateTask()">{{ __('texts.texts.create_an_task.' . auth()->user()->lang) }}</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="position-relative card">
        @include('front-end.layouts.task.modal_detail_task', ['listMember' => $tasks['members'], "tasks" => $tasks])
    </div>
@endsection

{{--@section('modal')--}}
{{--    @include('front-end.layouts.task.modal_detail_task', ['listMember' => $tasks['members'], "tasks" => $tasks])--}}
{{--@endsection--}}
