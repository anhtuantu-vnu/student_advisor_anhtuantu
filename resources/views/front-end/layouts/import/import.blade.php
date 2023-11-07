@extends('front-end.layouts.index')

@section('style_page')
    <style>
        .download_example_template:hover{
            cursor: pointer;
        }
        .disable_btn{
            opacity: 0.5;
            pointer-events: none !important;
        }
        .disable_btn:hover {
            cursor: unset !important;
        }
        .card_import {
            margin-top: 30px;
            border: none;
            box-shadow: 1px 1px 1px 1px #e1e5e1;
        }
    </style>
@endsection

@push('js_page')
    <script>
        function showProfileMessage(type, message) {
            $('#loadingSpinner').addClass("d-none");
            let errorProfile = document.getElementById("error-profile");
            errorProfile.classList.remove("d-none");
            errorProfile.classList.add("alert-" + type);
            errorProfile.innerHTML = `${message}`;
            setTimeout(() => {
                errorProfile.classList.add('d-none');
            }, 5000)
        }
        function handleUploadFile() {
            $('#uploadFileStudent').removeClass('disable_btn');
        }

        function resetDataInput() {
            $('#loadingSpinner').classList.add("d-none");
            $('#fileStudent').value('');
            $('#uploadFileStudent').addClass('disable_btn');
        }

        $('.download_example_template').click(function() {
            window.location = '/export';
        })

        $('#uploadFileStudent').click(function() {
            let fileInput = $('#fileStudent')[0].files[0]; // Lấy ra file từ input
            let formData = new FormData(); // Tạo đối tượng FormData

            formData.append('file', fileInput); // Thêm file vào FormData
            $.ajax({
                url: '/import',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    document.getElementById("loadingSpinner").classList.remove("d-none");
                },
                success: function() {
                    resetDataInput();
                },
                error: function(error) {
                    showProfileMessage("danger", error.statusText);
                },
                complete: function() {
                    $('#loadingSpinner').addClass("d-none");
                },
            });
        });
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                {{--Import student--}}
                <div class="row card rounded card_import">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <h2 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 28px !important;">
                                {{ __('texts.texts.title_page_import.' . auth()->user()->lang) }}
                            </h2>
                            <div class="row">
                                <div class="col-12">
                                    <div id="error-profile" class="alert d-none" role="alert">
                                    </div>
                                </div>
                                <div class="col-12">
                                        <span class="hiddenFileInput">
                                            @if(auth()->user()->lang === "vi")
                                                <p> Tải xuống mẫu<a class="download_example_template"> .csv</a> để xem ví dụ về định dạng được yêu cầu.</p>
                                            @else
                                                <p> Download our <a class="download_example_template"> .csv</a> template to see an example of the required format.</p>
                                            @endif
                                            <input type="file" name="file" accept="xlsx" id="fileStudent" onchange="handleUploadFile()"/>
                                        </span>
                                    <div class="mt-4">
                                        <button class="btn btn-primary disable_btn text-white ps-3 pe-3" type="button"
                                                id="uploadFileStudent">
                                            {{ __('texts.texts.upload_file.' . auth()->user()->lang) }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Import schedule study--}}
                <div class="row card rounded card_import" style="margin-top: 30px">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <h2 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 28px !important;">
                                {{ __('texts.texts.title_page_import_schedule.' . auth()->user()->lang) }}
                            </h2>
                            <div class="row">
                                <div class="col-12">
                                    <div id="error-profile" class="alert d-none" role="alert">
                                    </div>
                                </div>
                                <div class="col-12">
                                        <span class="hiddenFileInput">
                                            @if(auth()->user()->lang === "vi")
                                                <p> Tải xuống mẫu<a class="download_example_template"> .csv</a> để xem ví dụ về định dạng được yêu cầu.</p>
                                            @else
                                                <p> Download our <a class="download_example_template"> .csv</a> template to see an example of the required format.</p>
                                            @endif
                                            <input type="file" name="file" accept="xlsx" id="fileStudent" onchange="handleUploadFile()"/>
                                        </span>
                                    <div class="mt-4">
                                        <button class="btn btn-primary disable_btn text-white ps-3 pe-3" type="button"
                                                id="uploadFileStudent">
                                            {{ __('texts.texts.upload_file.' . auth()->user()->lang) }}
                                        </button>
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
