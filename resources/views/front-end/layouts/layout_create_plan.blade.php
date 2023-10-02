@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        const listUserSelected = [];
        const idUserSelected = [];
        const listUser = {!! json_encode($listUser) !!};
        const listUserShow = listUser;
        let isShowListMember = false;

        function clickSearchMember() {
            let listUser = document.querySelector('.list_customer');
            return removeClassNone(listUser);
        }

        function clickOutSide() {
            let listUser = document.querySelector('.list_customer');
            return addClassNone(listUser);
        };
        function clickSelectedUser(user) {
            let flagCheck = true;
            let inputCheckbox = document.querySelector('.list_member');
            for (let i = 0; i < listUserSelected.length; i++) {
                if (listUserSelected[i].email === user['email']) {
                    listUserSelected.splice(i, 1);
                    idUserSelected.splice(i, 1);
                    flagCheck = false;
                    break; // Exit the loop when the condition is met
                }
            }

            if (flagCheck) {
                listUserSelected.push(user);
                idUserSelected.push(user.uuid);
            }
            inputCheckbox.value = JSON.stringify(idUserSelected);
            renderListMemberSelected();
            return true;
        }

        //function render UI
        function renderListMemberSelected() {
            let listSelect = document.querySelector('.list_member_selected');
            removeAllElementChild(listSelect);
            listUserSelected.forEach(function(user) {
                let itemUser = initElement('p');
                itemUser.classList = 'm-0 fs-5 ms-1';
                itemUser.style.cssText = "border: 1px solid #c9cccd; padding: 4px; border-radius: 4px; background-color: #ECF0F1; color: black";
                itemUser.innerText = user.email;
                listSelect.appendChild(itemUser);
            });
            return listSelect;
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
                            <a href=" {{ route("app.plan") }} " class="d-inline-block mt-2"><i class="ti-arrow-left font-sm text-white"></i></a>
                            <h4 class="font-xs text-white fw-600 ms-4 mb-0 mt-2">Create Plan</h4>
                        </div>
                        <div class="card-body p-lg-5 p-4 w-100 border-0 ">
                            <form method="POST" action="/create-plan">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="mont-font fw-600 font-xsss">Name Plan *</label>
                                            <input type="text" class="form-control input_name" name="name" required>
{{--                                            @error('name')--}}
{{--                                                <div class="alert alert-danger">{{ $message }}</div>--}}
{{--                                            @enderror--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <div tabindex="0" onblur="clickOutSide()">
                                            <div class="form-group select_add_customer">
                                                <label class="mont-font fw-600 font-xsss">Add Member</label>
                                                <div class="input_add_member position-relative" onclick="clickSearchMember()">
                                                    <input type="text" class="form-control list_member" style="color:transparent" name="list_member">
                                                    {{-- Show list member selected --}}
                                                    <div class="list_member_selected position-absolute top-0">
                                                    </div>
                                                </div>
                                                <i class="feather-search font-xss fw-700" style="color: let(--theme-color) !important;" onclick="clickSearchMember()"></i>
                                            </div>

                                            {{-- Show list member --}}
                                            <div style="position: relative; z-index: 1" class="mt-1 list_customer d-none">
                                                <ul id = "selected" class="list">
                                                    @foreach($listUser as $user)
                                                        <li class="list-item">
                                                            <input type="checkbox" class="hidden-box" id="{{ $user['id'] }}" />
                                                            <label class="check-label" for="{{ $user['id'] }}" onclick="clickSelectedUser({{ json_encode($user) }})">
                                                                <span class="check-label-box"></span>
                                                                <span class="check-label-text">{{ $user['email'] }}</span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="mont-font fw-600 font-xsss">Description *</label>
{{--                                        @error('name')--}}
{{--                                            <div class="alert alert-danger">{{ $message }}</div>--}}
{{--                                        @enderror--}}
                                        <textarea class="form-control input_description mb-0 p-3 h200 bg-greylight lh-16" name="description" rows="5" placeholder="Description for plan..." spellcheck="false" required></textarea>
                                    </div>
                                </div>
                                <input type="submit" value="Save" class="bg-current text-center text-white font-xsss fw-600 p-3 w175 rounded-3 d-inline-block border-0"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
