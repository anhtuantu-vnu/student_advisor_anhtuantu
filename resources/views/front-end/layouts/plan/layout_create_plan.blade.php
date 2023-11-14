@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        let listUserSelected = [];
        let idUserSelected = [];
        let listUser = [];

        //Get list member
        function getListMember() {
            let dataSearch = $("#list_member_search").val();
            if (dataSearch) {
                $.ajax({
                    url: '/list-member',
                    type: 'GET',
                    data: {"search": dataSearch, "member_selected" : JSON.stringify(listUserSelected)},
                    processData: true,
                    contentType: false,
                    beforeSend: function () {
                        document.getElementById("loadingSpinner").classList.remove("d-none");
                    },
                    success: function (data) {
                        let listUserUI = document.querySelector('#selected');
                        let members = '';
                        if (data.data.length) {
                            listUser = data.data;
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

                            listUserUI.innerHTML = members;
                            document.getElementById("loadingSpinner").classList.add("d-none");
                            clickSearchMember();
                        } else {
                            document.getElementById("loadingSpinner").classList.add("d-none");
                            showAlertNotFoundMember();
                        }
                    },
                    error: function (error) {
                        showProfileMessage("danger", error.statusText);
                    },
                });
            } else {
                clickOutSide()
            }
        }

        //call api create plan

        function showAlertNotFoundMember() {
            $('.alert_list_member').removeClass('d-none');
            setTimeout(() => {
                $('.alert_list_member').addClass('d-none');
            }, 2000)
        }

        function clickSearchMember() {
            let listUser = document.querySelector('.list_customer');
            return removeClassNone(listUser);
        }

        function clickOutSide() {
            let listUser = document.querySelector('.list_customer');
            return addClassNone(listUser);
        }

        function clickSelectedUser(userId) {
            let data = listUser.filter(user => user.uuid === userId)[0];
            let flagCheck = true;
            for (let i = 0; i < listUserSelected.length; i++) {
                if (listUserSelected[i].email === data['email']) {
                    listUserSelected.splice(i, 1);
                    idUserSelected.splice(i, 1);
                    flagCheck = false;
                    break; // Exit the loop when the condition is met
                }
            }

            if (flagCheck) {
                listUserSelected.push(data);
                idUserSelected.push(data.uuid);
            }
            $('#list_member').val(JSON.stringify(idUserSelected));
            $('#list_member_search').val("");
            clickOutSide();
            renderListMemberSelected();
            return true;
        }

        //function render UI
        function handleRemoveMember(idMember) {
            listUserSelected = listUserSelected.filter(member => {
                return member['id'] !== idMember;
            })
            renderListMemberSelected();
        }

        function renderListMemberSelected() {
            $('.list_member_selected').empty();
            let uiMember = "";
            listUserSelected.forEach(member => {
                let idMember = member['id'];
                uiMember += `
                <p class="m-0 fs-5 me-1 mt-1" style="border: 1px solid #c9cccd; padding: 2px 4px; border-radius: 4px; background-color: #ECF0F1; color: black; line-height: 30px">
                    ${member['email']} <span class="icon_remove_member" onclick="handleRemoveMember(${idMember})">x</span>
                </p>
                `;
            });
            $('.list_member_selected').append(uiMember);
        }

        function handleCreatePlan() {
            let name = $('.input_name').val();
            let description = $('#description').val();
            hideMessageValidate()
            console.log(name);
            if(!name) {
                console.log(130)
                $('#required_name').removeClass('d-none');
                return;
            }
            if(!description) {
                $('#required_description').removeClass('d-none');
                return;
            }
            let data = {
                'name' : name,
                'list_member' : $('#list_member').val(),
                'description': description
            };

            $.ajax({
                url: "/create-plan",
                method: "post",
                data: data,
                before: function() {
                  $('#loadingSpinner').removeClass('d-none');
                },
                success: function(res) {
                    console.log(res);
                },
                error: function(data) {
                    $('#loadingSpinner').addClass('d-none');
                    console.log(data);
                },
                complete: function() {
                    $('#loadingSpinner').addClass('d-none');
                }
            });
        }

        function hideMessageValidate() {
            $('.message_validate').addClass('d-none');
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
                            <h4 class="font-xs text-white fw-600 ms-4 mb-0 mt-2">{{ __('texts.texts.create_plan.' . auth()->user()->lang) }}</h4>
                        </div>
                        <div class="card-body p-lg-5 p-4 w-100 border-0 ">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class="mont-font fw-600 font-xsss">{{ __('texts.texts.name_plan.' . auth()->user()->lang) }}
                                            *</label>
                                        <input type="text" class="form-control input_name" name="name" required />
                                        <p class="alert-danger message_validate d-none" style="background: none !important;" id="required_name">{{ __('texts.texts.required_name_plan.' . auth()->user()->lang) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <div tabindex="0">
                                        <div class="form-group select_add_customer">
                                            <label class="mont-font fw-600 font-xsss">{{ __('texts.texts.add_member.' . auth()->user()->lang) }}</label>
                                            <div class="input_add_member form-control position-relative d-flex"
                                                 style="padding: 4px !important;">
                                                <div class="position-relative w-100 ms-1">
                                                    <input type="text" class="d-none" name="list_member" id="list_member" />
                                                    <input type="text"
                                                           class="list_member_search w-100 position-absolute top-0"
                                                           style="line-height: 40px"
                                                           name="list_member_search" id="list_member_search" />
                                                    <i class="feather-search font-xss fw-700 position-absolute"
                                                       style="margin-top: 6px; right: 26px"
                                                       onclick="getListMember()"></i>
                                                </div>
                                            </div>
                                            {{-- Show list member selected --}}
                                            <div class="list_member_selected w-100">
                                            </div>
                                        </div>
                                        <p class="alert_list_member m-0 d-none"
                                           style="color: red;"> {{ __('texts.texts.not_found_member.' . auth()->user()->lang) }}</p>
                                        {{-- Show list member --}}
                                        <div style="position: relative; z-index: 1"
                                             class="list_customer d-none">
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
                                            id="description"
                                            placeholder="{{ __('texts.texts.description_for_plan.' . auth()->user()->lang) }}"
                                            spellcheck="false" required></textarea>
                                    <p class="alert-danger message_validate d-none" id="required_description" style="background: none !important;">{{ __('texts.texts.required_des_plan.' . auth()->user()->lang) }}</p>
                                </div>
                            </div>
                            <button class="bg-current text-center text-white font-xsss fw-600 p-3 w175 rounded-3 d-inline-block border-0"
                            onclick="handleCreatePlan()">
                                {{ __('texts.texts.save.' . auth()->user()->lang) }}
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
