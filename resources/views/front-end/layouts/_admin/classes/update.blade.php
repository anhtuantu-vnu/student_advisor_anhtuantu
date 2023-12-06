@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@push('js_page')
    <script>
        let updateClassForm = document.getElementById("updateClassForm");
        let saveFormUpdateButton = document.getElementById("saveFormUpdateButton");

        saveFormUpdateButton.addEventListener("click", e => {
            e.preventDefault();

            let name = document.getElementById("name").value;
            let code = document.getElementById("code").value;
            let department = document.getElementById("department").value;
            let start_year = document.getElementById("start_year").value;
            let end_year = document.getElementById("end_year").value;

            if (!name) {
                alert("{{ __('texts.texts.name_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!code) {
                alert("{{ __('texts.texts.code_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!start_year || !end_year) {
                alert("{{ __('texts.texts.department_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!department) {
                alert("{{ __('texts.texts.time_information_required.' . auth()->user()->lang) }} /");
                return;
            }

            let formData = "name=" + name +
                "&code=" + code +
                "&department=" + department +
                "&start_year=" + start_year +
                "&end_year=" + end_year;
            $.ajax({
                url: "/admin/classes/{{ $class_->uuid }}/update",
                type: "POST",
                data: formData,
                success: function(result) {
                    if (result.meta.success) {
                        alert("{{ __('texts.texts.update_success.' . auth()->user()->lang) }}");
                        window.location.reload();
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

        updateClassForm.addEventListener("submit", e => {
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
                                <a href="/admin/classes">
                                    {{ __('texts.texts.classes.' . auth()->user()->lang) }} /
                                </a>
                                {{ $class_->name }}
                            </h1>
                            <div class="mt-3">
                                <form class="row" id="updateClassForm" autocomplete="off">
                                    <div class="col-md-6 mt-2">
                                        <label for="name">
                                            {{ __('texts.texts.name.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ $class_->name }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="code">
                                            {{ __('texts.texts.code.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="code" name="code" class="form-control"
                                            value="{{ $class_->code }}">
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
                                                <option value="{{ $department->uuid }}"
                                                    <?php if($class_->department_id == $department->uuid) { ?>selected<?php } ?>>
                                                    {{ json_decode($department->name, true)[auth()->user()->lang] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="start_year">
                                            {{ __('texts.texts.start_year.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="start_year" name="start_year" class="form-control"
                                            value="{{ $class_->start_year }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="end_year">
                                            {{ __('texts.texts.end_year.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="end_year" name="end_year" class="form-control"
                                            value="{{ $class_->end_year }}">
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
