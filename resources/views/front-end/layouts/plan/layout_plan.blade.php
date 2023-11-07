@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/model_detail_task.css") }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@push('js_page')
    <script>
        let idDelete = '';
        //set % for process
        let setPerCentForProcess = setInterval(() => {
            const progress = document.querySelector('.progress-done');
            if (progress) {
                progress.style.width = progress.getAttribute('data-done') + '%';
                progress.style.opacity = 1;
                clearInterval(setPerCentForProcess);
            }
        })

        function handleClickEditPlan(idPlan) {
            location.replace(window.location.origin + `/plan/${idPlan}`);
        }

        function setDataTotal(key, total) {
            $(`#${key}`).text(total);
        }
        function getDataPlan() {
            $('#list_plan').empty();
            $.ajax({
                url: `/get-plan`,
                method: "GET",
                beforeSend: function (e) {
                    document.getElementById("loadingSpinner").classList.remove("d-none");
                },
                success: function(data) {
                    let dataUser = data.data;
                    setDataTotal('in_progress', dataUser.data.in_progress || 0);
                    setDataTotal('in_active', dataUser.data.in_active || 0);
                    setDataTotal('complete', dataUser.data.complete || 0);
                    setDataTotal('total_plan', dataUser.data.total_plan || 0);
                    if(dataUser.list_plan) {
                        let uiPlan = '';
                        dataUser.list_plan.forEach(plan => {
                            uiPlan += `
                            <div class="projects-section plan mt-3">
                                <div class="project-boxes jsListView card_plan">
                                    <div class="project-box-wrapper">
                                        <div class="project-box"
                                             style="background-color: ${plan['settings']['background_color']}">
                                            <a href="{{ route('show_task') }}?id=${plan['uuid']}">
                                                <div class="project-box-content-header me-5">
                                                    <span class="box-content-header" data-max-width="20vw"
                                                  data-tooltip-title="${plan['name']}">
                                                <p class="overflow-hidden"
                                                   style="text-overflow:ellipsis; white-space: nowrap">${plan['name']}</p>
                                            </span>
                                                    <p class="box-content-subheader status_plan">${plan['status']}</p>
                                                    <p style="font-size: 12px; line-height: 12px">${plan['date_created']}</p>
                                                </div>
                                            </a>
                                            <div class="box-progress-wrapper me-5">
                                                <p class="box-progress-header">Progress</p>
                                                <div class="box-progress-bar">
                                            <span
                                                class="box-progress"
                                                style="width: ${plan['percent']}%; background-color: ${plan['settings']['color']}"
                                            ></span>
                                                </div>
                                                <p class="box-progress-percentage">${plan['percent']}%</p>
                                            </div>
                                            <div class="project-box-footer">
                            `;
                            if(plan['list_member']) {
                                uiPlan += `<div class="participants">`;
                                plan.list_member.forEach(member => {
                                    uiPlan += `<img src="${member['avatar']}" alt="participant" />`;
                                })
                            }

                            uiPlan += `<div class="days-left ms-1" style="color: ${plan['settings']['color']}">`;
                            if(plan.count_date) {
                                uiPlan += `${plan['count_date']} day left`;
                            } else {
                                uiPlan += 'Today'
                            }
                            uiPlan += `</div>
                                            </div>`
                            if("{{auth()->user()->uuid}}" == plan['create_by']) {
                                uiPlan += `<div class="action_plan">
                                    <a class="feather-edit text-grey-900 icon_edit_plan" onclick="handleClickEditPlan('${plan['uuid']}')" style="margin-top: 2px; font-size: 20px;"></a>
                                    <i class="feather-trash-2 text-grey-900 icon_delete_plan" onclick="handleClickDeleteTask('${plan['name']}', '${plan['uuid']}')" style="margin-top: 2px; font-size: 20px;"></i>
                                </div>`;
                            }
                            uiPlan += ` </div>
                                    </div>
                                </div>
                            </div>`;
                        })
                        $('#list_plan').append(uiPlan);
                    }
                },
                error: function(error) {
                    console.log(error);
                },
                complete: function(data) {
                    document.getElementById("loadingSpinner").classList.add("d-none");
                }
            });
        }
        getDataPlan();

        async function handleClickDeleteTask(name, idPlan) {
            idDelete = idPlan;
            $('#name_plan_delete').text(name);
            showModal();
        }

        function handleClickRemovePlan() {
            $.ajax({
                url: `/plan/${idDelete}`,
                method: "DELETE",
                beforeSend: function (e) {
                    showLoadingBtn()
                },
                success: function() {
                    getDataPlan();
                },
                error: function(error) {
                    console.log(error);
                },
                complete: function(data) {
                    setTimeout(() => {
                        removeLoadingBtn()
                        removeClassModal()
                    }, 1000)
                }
            });
        }

        function showModal() {
            let modal = $(`.ui_modal_detail_task`);
            modal.removeClass('d-none');
            $('.main-wrapper').addClass('disable_ui');
        }

        function removeClassModal() {
            let modal = $(`.ui_modal_detail_task`);
            modal.addClass('d-none');
            $('.main-wrapper').removeClass('disable_ui');
        }

        function showLoadingBtn() {
            $(`#loading_btn_modal`).removeClass("d-none");
            $(`#btn_cancel`).addClass('disable_ui');
            $(`#btn_save`).addClass('disable_ui');
        }

        function removeLoadingBtn() {
            setTimeout(() => {
                $(`#loading_btn_modal`).addClass("d-none");
                $(`#btn_cancel`).removeClass('disable_ui');
                $(`#btn_save`).removeClass('disable_ui');
                hideModal(idTask)
            }, 1000)
        }
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                            <div class="card-body d-flex align-items-center p-0 mb-3">
                                <div class="plan_header">
                                    <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                        {{ __('texts.texts.plans.' . auth()->user()->lang) }}
                                    </h1>
                                    <div class="d-flex justify-content-between">
                                        <div class="item-status">
                                            <span class="status-number" id="in_progress"></span>
                                            <span class="status-type">{{ __('texts.texts.active.' . auth()->user()->lang) }}</span>
                                        </div>
                                        <div class="item-status">
                                            <span class="status-number" id="in_active"></span>
                                            <span class="status-type">{{ __('texts.texts.in_active.' . auth()->user()->lang) }}</span>
                                        </div>
                                        <div class="item-status">
                                            <span class="status-number" id="complete"></span>
                                            <span class="status-type">{{ __('texts.texts.complete.' . auth()->user()->lang) }}</span>
                                        </div>
                                        <div class="item-status">
                                            <span class="status-number" id="total_plan"></span>
                                            <span class="status-type">{{ __('texts.texts.total_plan.' . auth()->user()->lang) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="search-form-2 ms-auto">
                                    <a href="{{ route("ui_create_plan") }}"
                                       style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"
                                       class="ms-2 bg-current theme-dark-bg rounded-3">{{ __('texts.texts.create_plan.' . auth()->user()->lang) }}</a>
                                </div>
                            </div>

                            {{--UI list plan--}}
                            <div class="row ps-2 pe-1 pb-2" style="max-height: 60vh; overflow-y: scroll;" id="list_plan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="ui_modal_plan">
        <div class="ui_modal_detail_task ui_modal_detail_task_show d-none">
            <div class="modal_customer" tabindex="-1">
                <div class="modal-content pt-4 ps-4 pe-4 pb-2">
                    <div class="rounded-0 text-left">
                        <div class="title_task">
                            <div class="title_task_text ms-2">
                                <h3 style="margin-bottom: 0">{{ __("texts.texts.delete_plan." . auth()->user()->lang) }} <b id="name_plan_delete"></b> ?</h3>
                            </div>
                            <div class="modal-footer" style="border:none; padding: 0.75rem 0 !important;">
                                <button type="button" id="btn_cancel"
                                        class="btn" onclick="removeClassModal()"
                                        data-bs-dismiss="modal">{{ __('texts.texts.close.' . auth()->user()->lang) }}</button>
                                <button type="button" class="btn btn-danger" id="btn_save" onclick="handleClickRemovePlan()"
                                        style="margin-right: 0 !important; color: white">
                                    <i class="fa fa-circle-o-notch fa-spin d-none" id="loading_btn_modal" style="margin-right: 4px"></i>
                                    {{ __('texts.texts.delete.' . auth()->user()->lang) }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

