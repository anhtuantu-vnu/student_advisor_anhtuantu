@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/model_detail_task.css") }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@push('js_page')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        //get list task by plan
        function getDataTask() {
            let idPlan = window.location.search.slice(4);
            $('.list_task').empty()
            $.ajax({
                type: "GET",
                url: "/task",
                data: {idPlan},
                cache: false,
                contentType: false,
                processData: true,
                success: function (data) {
                    for (const [key, listTaskByType] of Object.entries(data.data)) {
                        if (['is_task' , 'author'].includes(key)) continue;
                        let typeTask = `
                        <div class="col-lg-3 col-xl-3 col-md-6 mb-2 mt-2 box_draggable">
                            <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item" id="${key}">
                                <div class="p-3 border-top-lg border-size-lg p-0 ${listTaskByType['config']['border']}">
                                    <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">${listTaskByType.config.type}</span>
                                        ${key == "tasks_to_do" ? `<button class="float-right btn-round-sm bg-greylight btn_create_task"
                                                onclick="showInputCreateTask()" style="border: none"><i class="feather-plus font-xss text-grey-900"></i></button>` : ''}
                                    </h4>
                                </div>`;

                        if (data.data.is_task) {
                            typeTask += `<div class="${key}">`;
                            // append ui task
                            for (const [keyTask, task] of Object.entries(listTaskByType)) {
                                if (Object.keys(task).length && keyTask !== 'config') {
                                    typeTask += `
                                        <div
                                            class="p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3 target" draggable="true"
                                            onclick="handleClickTask(${task["id"]})" id='${task["id"]}'>
                                            <div class="d-flex justify-content-between align-content-center">
                                                <h4 class="font-xsss fw-700 text-grey-900 mb-2 d-block">${task['name']}</h4>
                                                <i class="feather-trash-2" onclick="handleClickIconDelete(event, ${task['id']})" style="font-size: 18px"></i>
                                            </div>`;

                                    if (task['description']) {
                                        typeTask += `<p class="description_task font-xssss lh-24 fw-500 text-grey-500 mt-2 d-block mb-3">${task['description']}</p>`;
                                    }

                                    typeTask += `<span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block me-1"
                                                    style="color: white; background-color: ${listTaskByType['config']['backgroundTag']}"
                                            >{{ __("texts.texts.task." . auth()->user()->lang) }} ${listTaskByType['config']['type']}</span>
                                    `;
                                    if (task['user_assign'] && Object.keys(task['user_assign']).length) {
                                        typeTask += `<ul class="memberlist mt-4 mb-2 ms-0">
                                                                <li><a href="#"><img src="${task['user_assign']['avatar']}" alt="user"
                                                                 class="d-inline-block" style="border-radius: 50%; width: 24px; height: 24px"></a></li>
                                            <li class="ps-2 w-auto"><a href="#"
                                                                       class="fw-500 text-grey-500 font-xssss">${task['user_assign']['first_name']} {{ __("texts.texts.assigned." . auth()->user()->lang) }}</a>
                                            </li>
                                        </ul>`
                                    }
                                    typeTask += '</div>';

                                    //init ui task modal
                                    renderUiModal(task, listTaskByType.config.type, data);
                                    renderUiModalDeleteTask(task);
                                }
                            }
                            typeTask += '</div>';
                        }
                        if(key === "tasks_to_do") {
                            typeTask += `<div class="rounded-3 create_task pb-3 ps-3 pe-3 d-none">
                                        <div class="input_create_task rounded-3">
                                        <textarea
                                            id="input_create_task" class="form_create_plan mb-0 rounded-3 p-2"
                                            placeholder="{{ __('texts.texts.what_needs_to_be_done.' . auth()->user()->lang)}}"
                                            onfocusout="handleFocusoutTextarea()"
                                            maxlength="255"></textarea>
                                        </div>
                                </div>`;
                        }
                        $('.list_task').append(typeTask);
                    }

                    // UI not found task
                    if (!data.data.is_task) {
                        let uiEmptyTask = `
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
                                </div>`;
                        $('.list_task').append(uiEmptyTask);
                    }
                },
                complete: function (data) {
                    //init drop drag
                    initDropDrag();
                    document.getElementById("loadingSpinner").classList.add("d-none");
                }
            });
        }

        getDataTask()

        //logic drag drop
        let currentTarget = null;
        let currentBox = null;

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
            if(['task_done' , 'task_review', 'tasks_in_process', 'tasks_to_do'].includes(event.target.id)) {
                currentBox = event.target.id;
            }
            event.preventDefault();
        }

        function dragOver(event) {
            event.preventDefault();
        }

        function dropBox() {
            updateStatusTask(currentBox, currentTarget.id)
            this.append(currentTarget);
        }

        function updateStatusTask(status, idTask) {
            $.ajax({
                url: "/task/update-status",
                method: "put",
                data: {status, idTask},
                beforeSend: function() {
                    showLoadingBtnModalDelete(idTask)
                },
                success: async function(data) {
                    await getDataTask();
                },
                error: function(error) {
                    console.log(error)
                },
                complete: function(data) {
                    removeLoadingBtnModalDelete(idTask)
                }
            })
        }

        //set height for draggable_item
        function setHeightForDraggableItem() {
            if(document.querySelector('.tasks_to_do')) {
                let heightDraggable = 0;
                document.querySelectorAll('.box_draggable').forEach(function (box) {
                    if(box.offsetHeight > heightDraggable) {
                        heightDraggable = box.offsetHeight;
                    }
                })
                document.querySelectorAll('.draggable_item').forEach(function (draggable) {
                    draggable.style.minHeight = `${heightDraggable + 70}px`;
                })
                document.querySelector('.middle-sidebar-left').style.maxWidth = '1240px';
            }
        }

        //logic show hide create task
        function showInputCreateTask() {
            initFunctionCreateTask();
            document.querySelector('.btn_create_task').setAttribute("disable", "");
            document.querySelector('.create_task').classList.remove('d-none');
            document.querySelector('.form_create_plan').focus();
        }

        function handleFocusoutTextarea() {
            document.querySelector('.create_task').classList.add('d-none');
            document.querySelector('.btn_create_task').removeAttribute("disable");
        }

        //Create task
        let url = new URL(window.location.href);
        let idPlan = url.searchParams.get("id");
        function initFunctionCreateTask() {
            $("#input_create_task").keydown(function (e) {
                if (e.keyCode == 13) {
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
                        data: data,
                        success: function(res) {
                            getDataTask()
                            $(".alert_create_task").addClass('d-none')
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                }
            });
        }

        //init event click in task
        function showModal(modal) {
            modal.removeClass('d-none');
            modal.addClass('ui_modal_detail_task_show');
            $('.main-wrapper').addClass('disable_ui');
        }
        function removeClassModal(modal) {
            modal.addClass('d-none');
            modal.removeClass('ui_modal_detail_task_show');
            $('.main-wrapper').removeClass('disable_ui');
        }

        function hideModal(taskId) {
            let modal = $(`#modal_task_${taskId}`);
            removeClassModal(modal);
        }

        function handleClickTask(taskId) {
            let modal = $(`#modal_task_${taskId}`);
            showModal(modal);
        }

        function handleClickIconDelete(e, taskId) {
            e.stopPropagation();
            let modal = $(`#modal_task_delete_${taskId}`);
            showModal(modal);
        }
        function hideModalDelete(taskId) {
            let modal = $(`#modal_task_delete_${taskId}`);
            removeClassModal(modal);
        }

        function renderUiModal(task, typeTask, data) {
            let UIModal = `<div class="ui_modal_detail_task d-none" id="modal_task_${task['id']}">
                            <div class="modal_customer" tabindex="-1">
                                <div class="modal-content pt-3 ps-3 pe-3">
                                    <div class="rounded-0 text-left">
                                        <div class="title_task d-flex">
                                            <i class="feather-bookmark text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                            <div class="title_task_text ms-2">
                                                <h2 style="margin-bottom: 0">${task['name']}</h2>
                                                <p class="mb-3" style="font-size: 14px">{{ __('texts.texts.in_list.' . auth()->user()->lang) }}
                                                    ${typeTask}
                                            </div>
                                        </div>
                                        <div class="assign_to mt-1">
                                            <div class="assign_to_text d-flex">
                                                <i class="feather-share text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                                <h2 class="ms-2">{{ __('texts.texts.assign_to.' . auth()->user()->lang) }}</h2>
                                            </div>
                                            <div class="list_member_modal_task">
                                                <select class="p-2 rounded" id="selected_${task['id']}" value=${task['assigned_to']}>`;
            if (data.data_attach.length) {
                UIModal += `${!task['assigned_to'] ? "<option value='' selected}>{{ __('texts.texts.select_default.' . auth()->user()->lang) }}</option>" : ""}`;
                data.data_attach.forEach(member => {
                    UIModal += `<option value="${member['uuid']}" ${member['uuid'] === task['assigned_to'] ? 'selected' : ''}>${member['first_name']} ${member['last_name']}</option>`;
                })
            }
            UIModal += `</select>
                            </div>
                        </div>
                        <div class="description mt-4">
                        <div class="description_text d-flex">
                            <i class="feather-book-open text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                            <h2 class="ms-2">{{ __('texts.texts.description.' . auth()->user()->lang) }}</h2>
                        </div>`;
            if("{{auth()->user()->uuid}}" == data.data['author']) {
                UIModal += `<textarea id="description_${task['id']}" class="description_modal p-3 lh-16" name="description" rows="5">${task['description'] || "{{ __('texts.texts.description_task.' . auth()->user()->lang)}}"}</textarea>`
            } else {
                UIModal += `<p id="description_${task['id']}" >${task['description'] || "{{ __('texts.texts.description_member.' . auth()->user()->lang)}}"}</p>`
            }

            UIModal +=  `</div>
                        <div class="modal-footer" style="border:none; padding: 0.75rem 0 !important;">
                            <button type="button" onclick="hideModal(${task["id"]})" id="btn_cancel_${task['id']}" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('texts.texts.close.' . auth()->user()->lang) }}</button>
                            <button type="button" class="btn btn-success" id="btn_save_${task['id']}" style="color: white" onclick="updateDataTask(${task['id']})"
                                style="margin-right: 0 !important;"> <i class="fa fa-circle-o-notch fa-spin d-none" id="loading_btn_modal_${task['id']}" style="margin-right: 4px"></i>
                                {{ __('texts.texts.update.' . auth()->user()->lang) }}</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>`;
            $('#ui_modal').append(UIModal);
        }

        function renderUiModalDeleteTask(task) {
            let UIModal = `<div class="ui_modal_detail_task d-none" id="modal_task_delete_${task['id']}">
                        <div class="modal_customer" tabindex="-1">
                            <div class="modal-content pt-4 ps-4 pe-4 pb-2">
                                <div class="rounded-0 text-left">
                                    <div class="title_task">
                                        <div class="title_task_text ms-2">
                                            <h3 style="margin-bottom: 0">{{ __("texts.texts.delete_task." . auth()->user()->lang) }} <b>${task['name']}</b> ?</h3>
                                        </div>
                                        <div class="modal-footer" style="border:none; padding: 0.75rem 0 !important;">
                                            <button type="button" onclick="hideModalDelete(${task["id"]})" id="btn_cancel_delete_${task['id']}"
                                                class="btn"
                                                data-bs-dismiss="modal">{{ __('texts.texts.close.' . auth()->user()->lang) }}</button>
                                            <button type="button" class="btn btn-danger" id="btn_save_delete_${task['id']}" style="color: white"
                                                onclick="deleteTask(${task['id']})" style="margin-right: 0 !important;"> <i
                                                    class="fa fa-circle-o-notch fa-spin d-none" id="loading_btn_modal_delete_${task['id']}"
                                                    style="margin-right: 4px"></i>
                                                {{ __('texts.texts.delete.' . auth()->user()->lang) }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
            $('#ui_modal').append(UIModal);
        }

        //Logic update data task
        function showLoadingBtn(idTask) {
            $(`#loading_btn_modal_${idTask}`).removeClass("d-none");
            $(`#btn_cancel_${idTask}`).addClass('disable_ui');
            $(`#btn_save_${idTask}`).addClass('disable_ui');
        }

        function removeLoadingBtn(idTask) {
            setTimeout(() => {
                $(`#loading_btn_modal_${idTask}`).addClass("d-none");
                $(`#btn_cancel_${idTask}`).removeClass('disable_ui');
                $(`#btn_save_${idTask}`).removeClass('disable_ui');
                hideModal(idTask)
            }, 1000)
        }

        function updateDataTask(idTask) {
            let data = {
                _token: '{{csrf_token()}}',
                task: {
                    'id': idTask,
                    'member_selected': $(`#selected_${idTask} option:selected`).val(),
                    'description': $(`#description_${idTask}`).val(),
                }
            };
            $.ajax({
                url: "/task",
                method: "post",
                data: data,
                beforeSend: function() {
                    showLoadingBtn(idTask)
                },
                success: async function(data) {
                    await getDataTask();
                },
                error: function(error) {
                    console.log(error)
                },
                complete: function(data) {
                    removeLoadingBtn(idTask)
                }
            })
        }


        function showLoadingBtnModalDelete(idTask) {
            $(`#loading_btn_modal_delete_${idTask}`).removeClass("d-none");
            $(`#btn_cancel_delete_${idTask}`).addClass('disable_ui');
            $(`#btn_save_delete_${idTask}`).addClass('disable_ui');
        }

        function removeLoadingBtnModalDelete(idTask) {
            setTimeout(() => {
                $(`#loading_btn_modal_delete_${idTask}`).addClass("d-none");
                $(`#btn_cancel_delete_${idTask}`).removeClass('disable_ui');
                $(`#btn_save_${idTask}`).removeClass('disable_ui');
                hideModalDelete(idTask)
            }, 1000)
        }
        function deleteTask(idTask) {
            $.ajax({
                url: "/task",
                method: "delete",
                data: {id : idTask},
                beforeSend: function() {
                    showLoadingBtnModalDelete(idTask)
                },
                success: async function(data) {
                    await getDataTask();
                },
                error: function(error) {
                    console.log(error)
                },
                complete: function(data) {
                    removeLoadingBtnModalDelete(idTask)
                }
            })
        }
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active to_do">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row list_task">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="ui_modal">
    </div>
@endsection
