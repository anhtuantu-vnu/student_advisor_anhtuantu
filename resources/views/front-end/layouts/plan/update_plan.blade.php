@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        let listMember = [];
        //render list member
        function getDataPlan() {
            let idPlan = window.location.search.slice(4);
            $.ajax({
                url: "/get-data-plan",
                type: 'GET',
                data: {"id": idPlan},
                processData: true,
                contentType: false,
                beforeSend: function () {
                    $('#loadingSpinner').removeClass("d-none");
                },
                success: function (data) {
                    let dataShow = data.data;
                    listMember = dataShow['listMember'];
                    $('.input_name').val(dataShow['name']);
                    renderListMember(dataShow['listMember']);
                    $('.input_description').val(dataShow['description'] ?? "{{__('texts.texts.description_for_plan.' . auth()->user()->lang)}}");
                },
                error: function (error) {
                    showProfileMessage("danger", error.statusText);
                },
                complete: function () {
                    $('#loadingSpinner').addClass("d-none");
                }
            })
        }
        getDataPlan();

        //Get list member
        function getListMember() {
            let dataSearch = $("#list_member_search").val();
            if (dataSearch) {
                $.ajax({
                    url: '/list-member',
                    type: 'GET',
                    data: {"search": dataSearch, "member_selected" : listMember},
                    processData: true,
                    contentType: false,
                    beforeSend: function () {
                        $('#loadingSpinner').removeClass('d-none')
                    },
                    success: function (data) {
                        if (data.data.length) {
                            listUser = data.data;
                            let members = '';
                            data.data.forEach((user) => {
                                let uuid = user.uuid;
                                members += `
                                    <li class="list-item">
                                        <input type="checkbox" class="hidden-box member_item" id="${uuid}" />
                                        <label class="check-label" for="${uuid}" onclick="clickSelectedUser('${uuid}')">
                                            <span class="check-label-box"></span>
                                            <span class="check-label-text">${user.email}</span>
                                        </label>
                                    </li>
                                `;
                            });

                            $('#selected').append(members);
                            document.getElementById("loadingSpinner").classList.add("d-none");
                            clickSearchMember();
                        } else {
                            document.getElementById("loadingSpinner").classList.add("d-none");
                            showError();
                        }
                    },
                    error: function (error) {
                        console.log(error)
                    },
                    complete: function() {
                        $('#loadingSpinner').addClass('d-none')
                    }
                });
            }
        }
        function showError() {
            $("#error-profile").removeClass("d-none");
        }

        function handleRemoveMember(idMember) {
            listMember = listMember.filter(member => {
                return member['id'] !== idMember;
            })
            renderListMember(listMember);
        }

        function renderListMember(members) {
            $('.list_member_selected').empty();
            let uiMember = "";
            members.forEach(member => {
                let idMember = member['id'];
                uiMember += `
                <p class="m-0 fs-5 me-1 mt-1" style="border: 1px solid #c9cccd; padding: 2px 4px; border-radius: 4px; background-color: #ECF0F1; color: black; line-height: 30px">
                    ${member['email']} <span class="icon_remove_member" onclick="handleRemoveMember(${idMember})">x</span>
                </p>
                `;
            });
            $('.list_member_selected').append(uiMember);
        }

        function handleClickUpdate() {
            const formData = new FormData();
            formData.append('name', $('.input_name').val());
            formData.append('description', $('.input_description').val());
            formData.append('list_member', JSON.stringify(listMember));
            formData.append('id_plan', window.location.search.slice(4));
            $.ajax({
                url: '/update-plan',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#loadingSpinner').removeClass('d-none')
                },
                success: function (data) {
                    getListMember();
                },
                error: function (error) {
                    console.log(error);
                },
                complete: function() {
                    $('#loadingSpinner').addClass('d-none')
                }
            });
        }

        function handleChangeInputMember() {
            $("#error-profile").addClass("d-none");
        }
    </script>
@endpush

@section('content')
    <div class="main-content bg-lightblue theme-dark-bg right-chat-active layout_add_plan">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left">
                <div class="middle-wrap">
                    <div class="card w-100 border-0 bg-white shadow-xs p-0 mb-4">
                        <div class="card-body p-4 w-100 bg-current border-0 d-flex rounded-3">
                            <a href=" {{ route("plan") }} " class="d-inline-block mt-2"><i
                                        class="ti-arrow-left font-sm text-white"></i></a>
                            <h4 class="font-xs text-white fw-600 ms-4 mb-0 mt-2">{{ __('texts.texts.update_plan.' . auth()->user()->lang) }}</h4>
                        </div>
                        <div class="card-body p-lg-5 p-4 w-100 border-0 ">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class="mont-font fw-600 font-xsss">{{ __('texts.texts.name_plan.' . auth()->user()->lang) }}
                                            *</label>
                                        <input type="text" class="form-control input_name" name="name" value="" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <div tabindex="0">
                                        <div class="form-group select_add_customer">
                                            <label class="mont-font fw-600 font-xsss">{{ __('texts.texts.add_member.' . auth()->user()->lang) }}</label>
                                            <div class="input_add_member form-control position-relative d-flex"
                                                 style="padding: 4px !important; ">
                                                <div class="position-relative w-100 ms-1">
                                                    <input type="text"
                                                           class="list_member_search w-100 position-absolute top-0"
                                                           style="line-height: 40px"
                                                           onchange="handleChangeInputMember()"
                                                           name="list_member_search" id="list_member_search" />
                                                    <i class="feather-search font-xss fw-700 position-absolute"
                                                       style="margin-top: 6px; right: 26px"
                                                       onclick="getListMember()"></i>
                                                </div>
                                            </div>
                                            {{-- Show list member selected --}}
                                            <div class="col-12">
                                                <div id="error-profile" class="alert_custom d-none" role="alert">
                                                    {{ __('texts.texts.not_found_member.' . auth()->user()->lang) }}
                                                </div>
                                            </div>
                                            <div class="list_member_selected w-100">
                                            </div>
                                        </div>
                                        {{-- Show list member --}}
                                        <div style="position: relative; z-index: 1"
                                             class="mt-1 list_customer d-none">
                                            <ul id="selected" class="list">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="mont-font fw-600 font-xsss">{{ __('texts.texts.description.' . auth()->user()->lang) }}
                                        *</label>
                                    <textarea
                                            class="form-control input_description mb-0 p-3 h200 bg-greylight lh-16"
                                            name="description" rows="5"
                                            spellcheck="false" required>{{__('texts.texts.description_for_plan.' . auth()->user()->lang)}}</textarea>
                                </div>
                            </div>
                            <button onclick="handleClickUpdate()" class="bg-current text-center text-white font-xsss fw-600 p-3 w175 rounded-3 d-inline-block border-0">
                                {{ __('texts.texts.update.' . auth()->user()->lang) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
