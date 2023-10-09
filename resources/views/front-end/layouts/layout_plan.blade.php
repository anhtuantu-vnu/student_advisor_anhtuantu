@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        //set % for process
        let setPerCentForProcess = setInterval(() => {
            const progress = document.querySelector('.progress-done');
            if(progress) {
                progress.style.width = progress.getAttribute('data-done') + '%';
                progress.style.opacity = 1;
                clearInterval(setPerCentForProcess);
            }
        })

    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                            <div class="card-body d-flex align-items-center p-0">
                                <h2 class="fw-700 mb-0 mt-0 font-md text-grey-900">Plan</h2>
                                <div class="search-form-2 ms-auto">
                                    <i class="ti-search font-xss"></i>
                                    <input type="text" class="form-control text-grey-500 mb-0 bg-greylight theme-dark-bg border-0" placeholder="Search here.">
                                </div>
                                <a href="#" class="btn-round-md ms-2 bg-greylight theme-dark-bg rounded-3"><i class="feather-filter font-xss text-grey-500"></i></a>
                                <a href="{{ route("ui_create_plan") }}"
                                   style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"
                                   class="ms-2 bg-current theme-dark-bg rounded-3">Create plan</a>
                            </div>
                        </div>
                        <div class="row ps-2 pe-1">
                            {{--List plan--}}
                            <div class="col-sm-12 ps-2 plan">
                                <div class="plan_wrapper d-flex justify-content-between p-3">
                                    <div class="plan_information">
                                        <p style="font-weight: bold; font-size: 15px">Web design</p>
                                        <p style="font-size: 13px">Pending</p>
                                        <p style="color: #4A4A4A; opacity: 0.7; font-size: 13px">December 10, 2020</p>
                                    </div>
                                    <div class="plan_progress box-progress-wrapper">
                                        <p class="box-progress-header" style="font-weight: bold; font-size: 13px">Progress</p>
                                        <div class="progress">
                                            <div class="progress-done" style="width: 450px" data-done="70">
                                                70%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="plan_member">
                                        <div class="avt_members d-flex justify-content-between">
                                            <div class="avt_member position-relative ">
                                                <img src="https://lh3.googleusercontent.com/a/ACg8ocIpKC5-v6W_3y0DNt4lw7nIzeWdOPVcrsPOecHe8PwE=s96-c" alt="avatar" />
                                                <img src="https://lh3.googleusercontent.com/a/ACg8ocIpKC5-v6W_3y0DNt4lw7nIzeWdOPVcrsPOecHe8PwE=s96-c" alt="avatar" />
                                            </div>
                                            <button class="btn_plus">+</button>
                                        </div>
                                        <div class="time_create">2 day left</div>
                                    </div>
                                </div>
                            </div>
                            @foreach($listPlan as $plan)
{{--                                    <div class="col-md-6 col-sm-6 pe-2 ps-2">--}}
{{--                                        <div class="card d-block border-0 shadow-xss rounded-3 overflow-hidden mb-3">--}}
{{--                                            <div class="card-body position-relative h100 bg-image-cover bg-image-center" style="background-image: url({{ asset("images/bb-16.png") }});"></div>--}}
{{--                                            <div class="card-body d-block w-100 pe-4 pb-4 pt-0 text-left position-relative">--}}
{{--                                                --}}{{--                                        <figure class="avatar position-absolute w75 z-index-1" style="top:-40px; left: 15px;"><img src="{{ asset("images/user-12.png") }}" alt="image" class="float-right p-1 bg-white rounded-circle w-100"></figure>--}}
{{--                                                <div class="clearfix"></div>--}}
{{--                                                <h4 class="fw-700 font-xsss mt-3 mb-1">Victor Exrixon</h4>--}}
{{--                                                <p class="fw-500 font-xsssss text-grey-500 mt-0 mb-3">support@gmail.com</p>--}}
{{--                                                <span class="position-absolute right-15 top-0 d-flex align-items-center">--}}
{{--                                                    <a href="#" class="d-lg-block d-none"><i class="feather-video btn-round-md font-md bg-primary-gradiant text-white"></i></a>--}}
{{--                                                    <a href="#" class="text-center p-2 lh-24 w100 ms-1 ls-3 d-inline-block rounded-xl bg-current font-xsssss fw-700 ls-lg text-white">FOLLOW</a>--}}
{{--                                            </span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


