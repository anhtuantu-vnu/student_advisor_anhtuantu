@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

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
                                <a href="#" class="btn-round-md ms-2 bg-current theme-dark-bg rounded-3" data-bs-toggle="modal" data-bs-target="#ModalCreatePlan"><i class="feather-plus font-xss fw-700" style="color: white !important;"></i></a>
                            </div>
                        </div>

                        <div class="row ps-2 pe-1">
                            <div class="col-md-6 col-sm-6 pe-2 ps-2">
                                <div class="card d-block border-0 shadow-xss rounded-3 overflow-hidden mb-3">
                                    <div class="card-body position-relative h100 bg-image-cover bg-image-center" style="background-image: url({{ asset("images/bb-16.png") }});"></div>
                                    <div class="card-body d-block w-100 pe-4 pb-4 pt-0 text-left position-relative">
{{--                                        <figure class="avatar position-absolute w75 z-index-1" style="top:-40px; left: 15px;"><img src="{{ asset("images/user-12.png") }}" alt="image" class="float-right p-1 bg-white rounded-circle w-100"></figure>--}}
                                        <div class="clearfix"></div>
                                        <h4 class="fw-700 font-xsss mt-3 mb-1">Victor Exrixon</h4>
                                        <p class="fw-500 font-xsssss text-grey-500 mt-0 mb-3">support@gmail.com</p>
                                        <span class="position-absolute right-15 top-0 d-flex align-items-center">
                                                <a href="#" class="d-lg-block d-none"><i class="feather-video btn-round-md font-md bg-primary-gradiant text-white"></i></a>
                                                <a href="#" class="text-center p-2 lh-24 w100 ms-1 ls-3 d-inline-block rounded-xl bg-current font-xsssss fw-700 ls-lg text-white">FOLLOW</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal_page')
    <!-- Modal create plan -->
    <div class="modal bottom fade" style="overflow-y: scroll;" id="ModalCreatePlan" tabindex="1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <button type="button" class="close close-btn-modal-plan" data-dismiss="modal" aria-label="Close"><i class="ti-close text-grey-500"></i></button>
                <div class="modal-body p-3 d-flex align-items-center bg-none">
                    <div class="card shadow-none rounded-0 w-100 p-2 pt-3 border-0">
                        <div class="card-body rounded-0 text-left p-3">
                            <h2 class="fw-700 display1-size display2-md-size mb-4">Create your plan</h2>
                            <form>
                                <div class="form-gorup">
                                    <label class="mont-font fw-600 font-xsss">Name plan</label>
                                    <input type="text" name="comment-name" class="form-control">
                                </div>
                                <div class="form-gorup mt-2">
                                    <label class="mont-font fw-600 font-xsss">Description</label>
                                    <textarea class="form-control mb-0 p-3 h200 lh-16" rows="5" placeholder="Write your message..." spellcheck="false"></textarea>
                                </div>
                            </form>
                            <div class="col-sm-12 p-0 text-left mt-5">
                                <div class="form-group mb-1"><a href="{{ route('app.to_do') }}" class="form-control text-center style2-input text-white fw-600 bg-current border-0 p-0 ">Create</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_page')

@endsection
