@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        const listUserSelected = [];
        const listUser = {!! json_encode($listUser) !!};

        function clickSearchMember() {
            renderListSelect();
        }

        function handleSelectCustomer(user) {
            user = JSON.parse(user);
            let userId = 'user-' + user.id;
            let userSelected = document.getElementById(userId);
            userSelected.remove();
            listUserSelected.push(user.email);
            renderTagUserSelected(listUserSelected);
        }

        function clickOutSide() {
            // Code here
        }

        function renderListSelect() {
            let listSelect = document.querySelector('.list_customer');
            removeAllElementChild(listSelect);

            let ul = document.createElement('ul');
            ul.id = 'selected';

            listUser.forEach(function(user) {
                let li = createListItem(user);
                ul.appendChild(li);
            });

            listSelect.appendChild(ul);
        }

        function createListItem(user) {
            let li = document.createElement('li');
            li.id = 'user-' + user.id;
            li.className = 'item_user';
            li.onclick = function() {
                handleSelectCustomer(JSON.stringify(user));
            };

            let a = document.createElement('a');
            a.href = '#';
            a.textContent = user.email;

            li.appendChild(a);
            return li;
        }

        function renderTagUserSelected(emails) {
            let selectedUser = document.querySelector('.selected_user');
            removeAllElementChild(selectedUser);

            emails.forEach(function(email) {
                let container = createTagContainer(email);
                selectedUser.appendChild(container);
            });
        }

        function createTagContainer(email) {
            let container = document.createElement('div');
            container.classList.add('tag', 'me-1');

            let tag = document.createElement('div');
            tag.textContent = email;

            let svg = createDeleteIcon();

            container.appendChild(tag);
            container.appendChild(svg);
            container.addEventListener('click', function() {
                removeUserSelected(email);
            });

            return container;
        }

        function createDeleteIcon() {
            let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            svg.setAttribute('width', '18');
            svg.setAttribute('height', '18');
            svg.setAttribute('viewBox', '0 0 40 40');
            svg.classList.add('icon_delete');

            let path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('fill', 'currentColor');
            path.setAttribute('d', 'M21.499 19.994L32.755 8.727a1.064 1.064 0 0 0-.001-1.502c-.398-.396-1.099-.398-1.501.002L20 18.494L8.743 7.224c-.4-.395-1.101-.393-1.499.002a1.05 1.05 0 0 0-.309.751c0 .284.11.55.309.747L18.5 19.993L7.245 31.263a1.064 1.064 0 0 0 .003 1.503c.193.191.466.301.748.301h.006c.283-.001.556-.112.745-.305L20 21.495l11.257 11.27c.199.198.465.308.747.308a1.058 1.058 0 0 0 1.061-1.061c0-.283-.11-.55-.31-.747L21.499 19.994z');

            svg.appendChild(path);
            return svg;
        }

        function removeUserSelected(email) {
            let index = listUserSelected.indexOf(email);
            if (index !== -1) {
                listUserSelected.splice(index, 1);
            }
            renderTagUserSelected(listUserSelected);
        }

        function removeAllElementChild(element) {
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }
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
                            <form action="#">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-group">
                                            <label class="mont-font fw-600 font-xsss">Name Plan</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <div tabindex="0" onblur="clickOutSide()">
                                            <div class="form-group select_add_customer">
                                                <label class="mont-font fw-600 font-xsss">Add Member</label>
                                                <input type="text" class="form-control" name="member" onclick="clickSearchMember()" >
                                                <i class="feather-search font-xss fw-700" style="color: let(--theme-color) !important;"></i>
                                            </div>
                                            <div style="position: relative; z-index: 1" class="mt-1 list_customer">
{{--                                                <ul id="selected">--}}
{{--                                                    @foreach ($listUser as $user)--}}
{{--                                                        <li id="user-{{ $user['id'] }} item_user" onclick="handleSelectCustomer('{{ json_encode($user) }}')"><a href="#">{{ $user['email']}}</a></li>--}}
{{--                                                    @endforeach--}}
{{--                                                </ul>--}}
                                            </div>
                                        </div>

                                        {{-- List customer selected --}}
{{--                                        <div class="selected_user"></div>--}}
                                        <div class="selected_user"><div class="tag me-1"><div>namtv27072001@gmail.com</div><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 40 40" class="icon_delete"><path fill="currentColor" d="M21.499 19.994L32.755 8.727a1.064 1.064 0 0 0-.001-1.502c-.398-.396-1.099-.398-1.501.002L20 18.494L8.743 7.224c-.4-.395-1.101-.393-1.499.002a1.05 1.05 0 0 0-.309.751c0 .284.11.55.309.747L18.5 19.993L7.245 31.263a1.064 1.064 0 0 0 .003 1.503c.193.191.466.301.748.301h.006c.283-.001.556-.112.745-.305L20 21.495l11.257 11.27c.199.198.465.308.747.308a1.058 1.058 0 0 0 1.061-1.061c0-.283-.11-.55-.31-.747L21.499 19.994z"></path></svg></div><div class="tag me-1"><div>b@gmail.com</div><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 40 40" class="icon_delete"><path fill="currentColor" d="M21.499 19.994L32.755 8.727a1.064 1.064 0 0 0-.001-1.502c-.398-.396-1.099-.398-1.501.002L20 18.494L8.743 7.224c-.4-.395-1.101-.393-1.499.002a1.05 1.05 0 0 0-.309.751c0 .284.11.55.309.747L18.5 19.993L7.245 31.263a1.064 1.064 0 0 0 .003 1.503c.193.191.466.301.748.301h.006c.283-.001.556-.112.745-.305L20 21.495l11.257 11.27c.199.198.465.308.747.308a1.058 1.058 0 0 0 1.061-1.061c0-.283-.11-.55-.31-.747L21.499 19.994z"></path></svg></div></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="mont-font fw-600 font-xsss">Description</label>
                                        <textarea class="form-control mb-0 p-3 h200 bg-greylight lh-16" rows="5" placeholder="Description for plan..." spellcheck="false"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <a href="#" class="bg-current text-center text-white font-xsss fw-600 p-3 w175 rounded-3 d-inline-block">Save</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
