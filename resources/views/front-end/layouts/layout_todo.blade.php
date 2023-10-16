@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        // const draggable = document.querySelector('.draggable')
        //
        // function dragOver(e) {
        //     // thêm preventDefault vì theo mặc định thả vào bên trong 1 phần tử sẽ bị vô hiệu hóa => xem con trỏ chuột khi over để rõ hơn
        //     e.preventDefault()
        //     this.appendChild(draggable)
        // }
        //
        // function dragDrop() {
        //     this.appendChild(draggable)
        // }
        // const draggable = document.querySelector('.draggable')
        // const listBox = document.querySelectorAll('.box_draggable');
        //
        // draggable.addEventListener('dragstart', dragStart)
        // draggable.addEventListener('dragend', dragEnd)
        //
        // listBox.forEach((box) => {
        //     box.addEventListener('dragover', dragOver)
        //     box.addEventListener('drop', dragDrop)
        // })

        function dragStart() {
            console.log(33);
            // this.classList.add('dragging')
        }

        function dragEnd() {
            console.log(37);
            // this.classList.remove('dragging')
        }

        function dragOver(e) {
            // thêm preventDefault vì theo mặc định thả vào bên trong 1 phần tử sẽ bị vô hiệu hóa => xem con trỏ chuột khi over để rõ hơn
            e.preventDefault()
            console.log(45, e);
            this.appendChild(e.target)
        }

        function dragDrop(e) {
            console.log(50, e);
            this.appendChild(e.target)
        }
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active to_do">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-lg-6 col-xl-4 col-md-6 mb-2 mt-2 pe-2 box_draggable" ondragover="return dragOver(event)" ondrop="return dragDrop(event)">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="p-3 border-top-lg border-size-lg border-primary p-0">
                                        <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">To Do </span><a href="#" class="float-right btn-round-sm bg-greylight"  data-bs-toggle="modal" data-bs-target="#Modaltodo"><i class="feather-plus font-xss text-grey-900"></i></a></h4>
                                    </div>
                                    <div class="p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3 draggable" draggable="true" ondragstart="dragStart()" ondragend="dragEnd()">
                                        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">App Development</h4>
                                        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">Visit Home Depot to find out what is needed to rebuild backyard patio.</p>
                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>
                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>
                                        <ul class="memberlist mt-4 mb-2 ms-0">
                                            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user" class="w30 d-inline-block"></a></li>
                                            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user" class="w30 d-inline-block"></a></li>
                                            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user" class="w30 d-inline-block"></a></li>
                                            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user" class="w30 d-inline-block"></a></li>
                                            <li class="last-member"><a href="#" class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a></li>
                                            <li class="ps-4 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">Member</a></li>

                                        </ul>
                                    </div>

{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <img src="{{ asset("images/bg-2.png") }}" alt="image" class="img-fluid mb-3 p-2">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">Java Script Design</h4>--}}
{{--                                        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">Visit Home Depot to find out what is needed to rebuild backyard patio.</p>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                    </div>--}}

{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-3 mt-1 d-block">Frontend Developer</h4>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                        <ul class="memberlist mt-4 mb-2 ms-0">--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li class="last-member"><a href="#" class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a></li>--}}
{{--                                            <li class="ps-4 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">Member</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-4 col-md-6 mb-2 mt-2 pe-2 ps-2 box_draggable" ondragover="return dragOver(event)" ondrop="return dragDrop(event)">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="card-body p-3 border-top-lg border-size-lg border-warning p-0">
                                        <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">In progress </span><a href="#" class="float-right btn-round-sm bg-greylight"  data-bs-toggle="modal" data-bs-target="#Modaltodo"><i class="feather-plus font-xss text-grey-900"></i></a></h4>
                                    </div>
{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">Laravel Product Design</h4>--}}
{{--                                        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">Visit Home Depot to find out what is needed to rebuild backyard patio.</p>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                        <ul class="memberlist mt-4 mb-2 ms-0">--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li class="last-member"><a href="#" class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a></li>--}}
{{--                                            <li class="ps-4 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">Member</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}

{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-3 mt-1 d-block">Frontend Developer</h4>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                        <ul class="memberlist mt-4 mb-2 ms-0">--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li class="last-member"><a href="#" class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a></li>--}}
{{--                                            <li class="ps-4 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">Member</a></li>--}}
{{--                                        </ul>--}}
                                    </div>

{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <img src="images/bb-16.png" alt="image" class="img-fluid mb-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">UX Product Design</h4>--}}
{{--                                        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">Visit Home Depot to find out what is needed to rebuild backyard patio.</p>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>

                            <div class="col-lg-6 col-xl-4 col-md-6 mb-2 mt-2 ps-2 box_draggable" ondragover="return dragOver(event)" ondrop="return dragDrop(event)">
                                <div class="card p-0 bg-white rounded-3 shadow-xs border-0 draggable_item">
                                    <div class="card-body p-3 border-top-lg border-size-lg border-success p-0">
                                        <h4><span class="font-xsss fw-700 text-grey-900 mt-2 d-inline-block text-dark">Done </span><a href="#" class="float-right btn-round-sm bg-greylight"  data-bs-toggle="modal" data-bs-target="#Modaltodo"><i class="feather-plus font-xss text-grey-900"></i></a></h4>
                                    </div>
{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">Laravel Product Design</h4>--}}
{{--                                        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">Visit Home Depot to find out what is needed to rebuild backyard patio.</p>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                        <ul class="memberlist mt-4 mb-2 ms-0">--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li class="last-member"><a href="#" class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a></li>--}}
{{--                                            <li class="ps-4 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">Member</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}

{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-3 mt-1 d-block">Frontend Developer</h4>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                        <ul class="memberlist mt-4 mb-2 ms-0">--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user" class="w30 d-inline-block"></a></li>--}}
{{--                                            <li class="last-member"><a href="#" class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a></li>--}}
{{--                                            <li class="ps-4 w-auto"><a href="#" class="fw-500 text-grey-500 font-xssss">Member</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}

{{--                                    <div class="card-body p-3 bg-lightblue cart_task theme-dark-bg m-3 rounded-3">--}}
{{--                                        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">UX Product Design</h4>--}}
{{--                                        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">Visit Home Depot to find out what is needed to rebuild backyard patio.</p>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--                                        <span class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
