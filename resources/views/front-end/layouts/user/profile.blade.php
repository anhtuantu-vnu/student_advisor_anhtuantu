@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <style>
        .hiddenFileInput>input {
            height: 100%;
            width: 100;
            opacity: 0;
            cursor: pointer;
        }

        .hiddenFileInput {
            border: 1px solid #ccc;
            width: 96px;
            height: 96px;
            display: inline-block;
            overflow: hidden;
            cursor: pointer;
            border-radius: 100%;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-image: url({{ auth()->user()->avatar }});
        }
    </style>
@endsection

@push('js_page')
    <script>
        let saveAvaButton = document.getElementById("saveAvaButton");
        let fileAvatar = document.getElementById("fileAvatar");
        let hiddenFileInput = document.querySelector(".hiddenFileInput");
        let avaFile;

        fileAvatar.addEventListener("change", e => {
            let files = e.target.files;
            if (!files || !files.length) {
                saveAvaButton.classList.add("d-none");
            } else {
                saveAvaButton.classList.remove("d-none");
            }

            let file = files[0];
            avaFile = file;
            let imgTmp = URL.createObjectURL(file);
            hiddenFileInput.style.backgroundImage = "url('" + imgTmp + "')";
        });

        function showProfileMessage(type, message) {
            let errorProfile = document.getElementById("error-profile");
            errorProfile.classList.remove("d-none");
            errorProfile.classList.add("alert-" + type);
            errorProfile.innerHTML = `${message}`;

            setTimeout(() => {
                errorProfile.classList.add("d-none");
            }, 2000);
        }

        function removeProfileMessage() {
            let errorProfile = document.getElementById("error-profile");
            errorProfile.classList.add("d-none");
            errorProfile.innerHTML = "";
        }

        saveAvaButton.addEventListener("click", e => {
            e.preventDefault();
            removeProfileMessage();

            let formData = new FormData();
            formData.append("file", avaFile, avaFile.name);

            $.ajax({
                type: "POST",
                url: "/update-avatar",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    document.getElementById("loadingSpinner").classList.remove("d-none");
                },
                success: function(data) {
                    let newAvatar = data.data;
                    let headerUserAvatar = document.getElementById("headerUserAvatar");
                    headerUserAvatar.setAttribute("src", newAvatar);

                    let lcUser = JSON.parse(localStorage.getItem("user"));
                    lcUser.avatar = newAvatar;
                    localStorage.setItem("user", JSON.stringify(lcUser))
                    showProfileMessage("success", data.meta.message);
                },
                error: function(error) {
                    showProfileMessage("danger", error.statusText);
                },
                complete: function(data) {
                    document.getElementById("loadingSpinner").classList.add("d-none");
                }
            });
        });
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ __('texts.texts.profile.' . auth()->user()->lang) }}
                            </h1>
                            <div>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="error-profile" class="alert d-none" role="alert">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="hiddenFileInput">
                                            <input type="file" name="file" accept="image/*" id="fileAvatar" />
                                        </span>
                                        <div class="mt-2">
                                            <button class="btn btn-primary text-white d-none" type="button"
                                                id="saveAvaButton">
                                                {{ __('texts.texts.save.' . auth()->user()->lang) }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.full_name.' . $thisUser->lang) }}</b>:
                                                {{ $thisUser->last_name . ' ' . $thisUser->first_name }}
                                            </div>
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.role.' . $thisUser->lang) }}</b>:
                                                {{ $thisUser->role }}
                                            </div>
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.department.' . $thisUser->lang) }}</b>:
                                                {{ json_decode($thisUser->department->name, true)[$thisUser->lang] }}
                                            </div>
                                            @if (auth()->user()->role == App\Http\Controllers\_CONST::STUDENT_ROLE)
                                                <div class="col-md-6">
                                                    <b>{{ __('texts.texts.class.' . $thisUser->lang) }}</b>:
                                                    @if ($class_ != null)
                                                        {{ $class_->name }}
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.email.' . $thisUser->lang) }}</b>:
                                                {{ $thisUser->email }}
                                            </div>
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.phone.' . $thisUser->lang) }}</b>:
                                                {{ $thisUser->phone }}
                                            </div>
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.gender.' . $thisUser->lang) }}</b>:
                                                {{ $genderMap[$thisUser->gender] }}
                                            </div>
                                            <div class="col-md-6">
                                                <b>{{ __('texts.texts.date_of_birth.' . $thisUser->lang) }}</b>:
                                                {{ \Carbon\Carbon::parse($thisUser->date_of_birth)->format('d/m/Y') }}
                                            </div>
                                        </div>
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
