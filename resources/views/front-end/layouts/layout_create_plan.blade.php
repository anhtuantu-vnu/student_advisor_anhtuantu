@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection
@push('js_page')
    <script>
        import removeClassNone from "{{ public_path("js/app_custom.js") }}";
        function clickSearchMember() {
            let listResult = document.querySelector(".list_customer");
            console.log(listResult);
            removeClassNone(listResult);
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
                            <a href="default-settings.html" class="d-inline-block mt-2"><i class="ti-arrow-left font-sm text-white"></i></a>
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
                                        <div class="form-group select_add_customer">
                                            <label class="mont-font fw-600 font-xsss">Add Member</label>
                                            <input type="text" class="form-control" name="member" onclick="clickSearchMember()">
                                            <i class="feather-search font-xss fw-700" style="color: var(--theme-color) !important;"></i>
                                        </div>
                                        <div style="position: relative" class="mt-1 d-none list_customer">
                                            <ul id="selected">
                                                <li><a href="#">Adele</a></li>
                                                <li><a href="#">Agnes</a></li>

                                                <li><a href="#">Billy</a></li>
                                                <li><a href="#">Bob</a></li>

                                                <li><a href="#">Calvin</a></li>
                                                <li><a href="#">Christina</a></li>
                                                <li><a href="#">Cindy</a></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="mont-font fw-600 font-xsss">Description</label>
                                        <textarea class="form-control mb-0 p-3 h200 bg-greylight lh-16" rows="5" placeholder="Write your message..." spellcheck="false"></textarea>
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
