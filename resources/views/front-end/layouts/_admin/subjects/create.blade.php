@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@push('js_page')
    <script>
        let updateSubjectForm = document.getElementById("updateSubjectForm");
        let saveFormUpdateButton = document.getElementById("saveFormUpdateButton");

        saveFormUpdateButton.addEventListener("click", e => {
            e.preventDefault();

            let vi = document.getElementById("vi").value;
            let en = document.getElementById("en").value;
            let code = document.getElementById("code").value;
            let color = document.getElementById("color").value;
            let department = document.getElementById("department").value;
            let description = document.getElementById("description").value;

            if (!vi || !en) {
                alert("{{ __('texts.texts.name_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!code) {
                alert("{{ __('texts.texts.code_required.' . auth()->user()->lang) }} /");
                return;
            }

            let formData = "vi=" + vi +
                "&en=" + en +
                "&code=" + code +
                "&color=" + color +
                "&department=" + department +
                "&description=" + description;
            $.ajax({
                url: "/admin/subjects/create",
                type: "POST",
                data: formData,
                success: function(result) {
                    if (result.meta.success) {
                        alert("{{ __('texts.texts.update_success.' . auth()->user()->lang) }}");
                        window.location.href =
                            `/admin/subjects/${result.data.subject.uuid}/update`;
                        return;
                    } else {
                        alert(JSON.stringify(result));
                        return;
                    }
                },
                error: function(error) {
                    alert(error.responseJSON.message);
                    return;
                },
            });
        });

        updateSubjectForm.addEventListener("submit", e => {
            e.preventDefault();
            saveFormUpdateButton.click();
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
                                <a href="/admin/subjects">
                                    {{ __('texts.texts.subjects.' . auth()->user()->lang) }} /
                                </a>
                                {{ __('texts.texts.add.' . auth()->user()->lang) }}
                            </h1>
                            <div class="mt-3">
                                <form class="row" id="updateSubjectForm" autocomplete="off">
                                    <div class="col-md-6 mt-2">
                                        <label for="vi">
                                            {{ __('texts.texts.vi.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="vi" name="vi" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="en">
                                            {{ __('texts.texts.en.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="en" name="en" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="code">
                                            {{ __('texts.texts.code.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="code" name="code" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="color">
                                            {{ __('texts.texts.color.' . auth()->user()->lang) }}
                                        </label>
                                        <div class="input-color-container">
                                            <input type="color" class="input-color" id="color" name="color">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="department">
                                            {{ __('texts.texts.department.' . auth()->user()->lang) }}
                                        </label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="">
                                                {{ __('texts.texts.choose_an_option.' . auth()->user()->lang) }}
                                            </option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->uuid }}">
                                                    {{ json_decode($department->name, true)[auth()->user()->lang] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="description">
                                            {{ __('texts.texts.description.' . auth()->user()->lang) }}
                                        </label>
                                        <textarea id="description" name="description" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <button class="btn btn-primary text-white" type="button" id="saveFormUpdateButton">
                                            {{ __('texts.texts.save.' . auth()->user()->lang) }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
