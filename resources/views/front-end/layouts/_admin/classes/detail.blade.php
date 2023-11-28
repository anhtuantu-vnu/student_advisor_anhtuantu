@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@push('js_page')
    <script>
        let removeTeachers = document.querySelectorAll('.remove-member');

        Array.from(removeTeachers).forEach(item => {
            item.addEventListener("click", e => {
                e.preventDefault();

                let removeConfirm = confirm(
                    "{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
                if (removeConfirm) {
                    let formData = "uuid=" + e.target.dataset.uuid;
                    $.ajax({
                        url: "/admin/classes/{{ $class_->uuid }}/remove-member",
                        type: "POST",
                        data: formData,
                        success: function(result) {
                            if (result.meta.success) {
                                let thisTeacherSection = document.getElementById(
                                    "teacher_section_" + e.target.dataset.uuid);
                                if (thisTeacherSection) {
                                    thisTeacherSection.classList.add("d-none");
                                }

                                let studentsTr = document.getElementById("students-tr-" + e
                                    .target.dataset.uuid);
                                if (studentsTr) {
                                    studentsTr.classList.add("d-none");
                                }
                                return;
                            } else {
                                alert(JSON.stringify(result));
                                return;
                            }
                        },
                        error: function(error) {
                            alert(error.responseJSON.errors.error_message);
                            return;
                        },
                    });
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
                                <a href="/admin/classes">
                                    {{ __('texts.texts.classes.' . auth()->user()->lang) }} /
                                </a>
                                {{ $class_->name }}
                            </h1>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <b>
                                            {{ __('texts.texts.teacher.' . auth()->user()->lang) }}
                                        </b>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="button" class="btn btn-success text-white" id="addTeacherButton"
                                            data-bs-toggle="modal" data-bs-target="#addTeacherClassModal">
                                            {{ __('texts.texts.add.' . auth()->user()->lang) }}
                                        </button>
                                    </div>
                                    @foreach ($classTeachers as $teacher)
                                        <div class="col-md-6 mt-2" id="teacher_section_{{ $teacher->uuid }}">
                                            <a href="/users/{{ $teacher->user->uuid }}">
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <div style="margin-right: 16px;">
                                                        <img src="{{ $teacher->user->avatar }}"
                                                            alt="{{ $teacher->user->last_name }}_avatar"
                                                            style="height: 56px; width: 56px; object-fit: cover; border-radius: 100%;"
                                                            class="border">
                                                    </div>
                                                    <div>
                                                        {{ $teacher->user->last_name . ' ' . $teacher->user->first_name }}
                                                        <br>
                                                        <small>
                                                            <a
                                                                href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a>
                                                        </small>
                                                        <br>
                                                        <span class="text-decoration-underline cusor-pointer remove-member"
                                                            data-uuid="{{ $teacher->uuid }}">
                                                            {{ __('texts.texts.remove.' . auth()->user()->lang) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-5">
                                <div>
                                    <b>
                                        {{ __('texts.texts.student.' . auth()->user()->lang) }}
                                    </b>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="button" class="btn btn-success text-white" id="addStudentButton"
                                        data-bs-toggle="modal" data-bs-target="#addStudentClassModal">
                                        {{ __('texts.texts.add.' . auth()->user()->lang) }}
                                    </button>
                                </div>
                                <div style="max-height: 50vh; overflow-y: scroll;" class="mt-2">
                                    <table class="table table-bordered table-sm">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">
                                                    #
                                                </th>
                                                <th scope="col">
                                                    {{ __('texts.texts.student.' . auth()->user()->lang) }}
                                                </th>
                                                <th scope="col">
                                                    {{ __('texts.texts.action.' . auth()->user()->lang) }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="intake-students-tbody">
                                            @foreach ($classStudents as $index => $student)
                                                <tr class="class-students-tr" id="students-tr-{{ $student->uuid }}"
                                                    data-student-name="{{ $student->user->last_name . ' ' . $student->user->first_name }}">
                                                    <td>
                                                        {{ $index + 1 }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-wrap align-items-center cursor-pointer student-info-tr"
                                                            data-student='{{ json_encode($student->user) }}'>
                                                            <div>
                                                                <img src="{{ $student->user->avatar }}"
                                                                    alt="{{ $student->user->last_name }}_avatar"
                                                                    class="border"
                                                                    style="width: 56px; height: 56px; object-fit: cover; border-radius: 100%; margin-right: 16px;">
                                                            </div>
                                                            <div>
                                                                <a href="/users/{{ $student->user->uuid }}">
                                                                    {{ $student->user->last_name . ' ' . $student->user->first_name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger remove-member"
                                                            data-uuid="{{ $student->uuid }}">
                                                            <i class="feather-x text-white"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('front-end.layouts._admin.classes.add_teacher_modal')
    @include('front-end.layouts._admin.classes.add_student_modal')
@endsection
