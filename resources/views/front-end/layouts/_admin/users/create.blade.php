@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@push('js_page')
    <script>
        let updateUserForm = document.getElementById("updateUserForm");
        let saveFormUpdateButton = document.getElementById("saveFormUpdateButton");

        saveFormUpdateButton.addEventListener("click", e => {
            e.preventDefault();

            let last_name = document.getElementById("last_name").value;
            let first_name = document.getElementById("first_name").value;
            let email = document.getElementById("email").value;
            let phone = document.getElementById("phone").value;
            let date_of_birth = document.getElementById("date_of_birth").value;
            let gender = document.getElementById("gender").value;
            let unique_id = document.getElementById("unique_id").value;
            let role = document.getElementById("role").value;
            let department = document.getElementById("department").value;

            if (!last_name || !first_name) {
                alert("{{ __('texts.texts.name_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!email) {
                alert("{{ __('texts.texts.email_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!unique_id) {
                alert("{{ __('texts.texts.code_required.' . auth()->user()->lang) }} /");
                return;
            }

            let formData = "last_name=" + last_name +
                "&first_name=" + first_name +
                "&email=" + email +
                "&phone=" + phone +
                "&date_of_birth=" + date_of_birth +
                "&gender=" + gender +
                "&unique_id=" + unique_id +
                "&role=" + role +
                "&department=" + department;
            $.ajax({
                url: "/admin/users/create",
                type: "POST",
                data: formData,
                success: function(result) {
                    if (result.meta.success) {
                        alert("{{ __('texts.texts.update_success.' . auth()->user()->lang) }}");
                        window.location.href = `/admin/users/${result.data.user.uuid}/update`;
                        return;
                    } else {
                        alert(JSON.stringify(result));
                        return;
                    }
                },
                error: function(error) {
                    let msg = error.responseJSON.errors.error_message;
                    if (msg.toLowerCase().indexOf('duplicate') != -1) {
                        msg = 'Duplicate data';
                    }
                    alert(msg);
                    return;
                },
            });
        });

        updateUserForm.addEventListener("submit", e => {
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
                                <a href="/admin/users">
                                    {{ __('texts.texts.users.' . auth()->user()->lang) }} /
                                </a>
                                {{ __('texts.texts.add.' . auth()->user()->lang) }}
                            </h1>
                            <div class="mt-3">
                                <form class="row" id="updateUserForm" autocomplete="off">
                                    <div class="col-md-6 mt-2">
                                        <label for="last_name">
                                            {{ __('texts.texts.last_name.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="first_name">
                                            {{ __('texts.texts.first_name.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="first_name" name="first_name" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="email">
                                            {{ __('texts.texts.email.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="phone">
                                            {{ __('texts.texts.phone.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="phone" name="phone" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="date_of_birth">
                                            {{ __('texts.texts.date_of_birth.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="gender">
                                            {{ __('texts.texts.gender.' . auth()->user()->lang) }}
                                        </label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="1">
                                                {{ __('texts.texts.male.' . auth()->user()->lang) }}
                                            </option>
                                            <option value="2">
                                                {{ __('texts.texts.female.' . auth()->user()->lang) }}
                                            </option>
                                            <option value="3">
                                                {{ __('texts.texts.other.' . auth()->user()->lang) }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="unique_id">
                                            {{ __('texts.texts.unique_id.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="unique_id" name="unique_id" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="role">
                                            {{ __('texts.texts.role.' . auth()->user()->lang) }}
                                        </label>
                                        <select name="role" id="role" class="form-control" required>
                                            <option value="student">
                                                {{ __('texts.texts.student.' . auth()->user()->lang) }}
                                            </option>
                                            <option value="teacher">
                                                {{ __('texts.texts.teacher.' . auth()->user()->lang) }}
                                            </option>
                                            <option value="admin">
                                                {{ __('texts.texts.admin.' . auth()->user()->lang) }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="department">
                                            {{ __('texts.texts.department.' . auth()->user()->lang) }}
                                        </label>
                                        <select name="department" id="department" class="form-control" required>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->uuid }}">
                                                    {{ json_decode($department->name, true)[auth()->user()->lang] }}
                                                </option>
                                            @endforeach
                                        </select>
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
