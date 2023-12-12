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
                        url: "/admin/intakes/{{ $intake->uuid }}/remove-member",
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

        let updateIntakes = document.querySelectorAll(".update-intake");
        Array.from(updateIntakes).forEach(item => {
            item.addEventListener("click", e => {
                document.getElementById("updateStudentIntakeModalOpener").click();
                let intake = JSON.parse(e.target.dataset.intake);
                document.getElementById("updateIntakeModalTitle").innerText = intake.user.last_name + ' ' +
                    intake.user.first_name;

                document.getElementById("attendance_points").value = intake.attendance_points;
                document.getElementById("mid_term_points").value = intake.mid_term_points;
                document.getElementById("last_term_points").value = intake.last_term_points;

                document.getElementById("updateStudentIntake").dataset.intake = JSON.stringify(intake);

            });
        });

        document.getElementById("updateStudentIntake").addEventListener("click", e => {
            let intake = JSON.parse(e.target.dataset.intake);
            let formData = "intake_member=" + intake.uuid +
                "&attendance_points=" + document.getElementById("attendance_points").value +
                "&mid_term_points=" + document.getElementById("mid_term_points").value +
                "&last_term_points=" + document.getElementById("last_term_points").value;
            $.ajax({
                url: "/admin/intakes/{{ $intake->uuid }}/update-member",
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
                    alert(error.responseJSON.errors.error_message);
                    return;
                },
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
                                <a href="/admin/intakes">
                                    {{ __('texts.texts.intakes.' . auth()->user()->lang) }} /
                                </a>
                                {{ $intake->code }}
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
                                            data-bs-toggle="modal" data-bs-target="#addTeacherIntakeModal">
                                            {{ __('texts.texts.add.' . auth()->user()->lang) }}
                                        </button>
                                    </div>
                                    @foreach ($intakeTeachers as $teacher)
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
                                        data-bs-toggle="modal" data-bs-target="#addStudentIntakeModal">
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
                                                    {{ __('texts.texts.information.' . auth()->user()->lang) }}
                                                </th>
                                                <th scope="col">
                                                    {{ __('texts.texts.action.' . auth()->user()->lang) }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="intake-students-tbody">
                                            @foreach ($intakeStudents as $index => $student)
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
                                                        <ul style="list-style-type: circle;">
                                                            <li>
                                                                <b>
                                                                    {{ __('texts.texts.attendance_points.' . auth()->user()->lang) }}:
                                                                </b>
                                                                <span
                                                                    class="<?php if($student->attendance_points <= 6) { ?>text-danger<?php } ?>">
                                                                    {{ $student->attendance_points }}
                                                                </span>
                                                            </li>
                                                            <li>
                                                                <b>
                                                                    {{ __('texts.texts.mid_term_points.' . auth()->user()->lang) }}:
                                                                </b>
                                                                <span
                                                                    class="<?php if($student->mid_term_points <= 6) { ?>text-danger<?php } ?>">
                                                                    {{ $student->mid_term_points }}
                                                                </span>
                                                            </li>
                                                            <li>
                                                                <b>
                                                                    {{ __('texts.texts.last_term_points.' . auth()->user()->lang) }}:
                                                                </b>
                                                                <span
                                                                    class="<?php if($student->last_term_points <= 6) { ?>text-danger<?php } ?>">
                                                                    {{ $student->last_term_points }}
                                                                </span>
                                                            </li>
                                                            <li>
                                                                <b>
                                                                    {{ __('texts.texts.ave_points.' . auth()->user()->lang) }}:
                                                                </b>
                                                                <?php
                                                                $avePoints = '';
                                                                if ($student->attendance_points >= 0 && $student->mid_term_points >= 0 && $student->last_term_points >= 0) {
                                                                    $avePoints = $student->attendance_points * 0.1 + $student->mid_term_points * 0.3 + $student->last_term_points * 0.6;
                                                                }
                                                                ?>
                                                                @if ($avePoints <= 6)
                                                                    <span class="text-danger">
                                                                        {{ $avePoints }}
                                                                    </span>
                                                                @else
                                                                    <span>
                                                                        {{ $avePoints }}
                                                                    </span>
                                                                @endif
                                                            </li>
                                                            <li>
                                                                <b>
                                                                    {{ __('texts.texts.gpa.' . auth()->user()->lang) }}:
                                                                </b>
                                                                <?php
                                                                $gpa = 0;
                                                                $count = 0;
                                                                foreach ($student->user->intakeMembers as $iMember) {
                                                                    if ($iMember->attendance_points >= 0 && $iMember->mid_term_points >= 0 && $iMember->last_term_points >= 0) {
                                                                        $gpa += $iMember->attendance_points * 0.1 + $iMember->mid_term_points * 0.3 + $iMember->last_term_points * 0.6;
                                                                        $count++;
                                                                    }
                                                                }
                                                                ?>
                                                                @if ($gpa / $count <= 6)
                                                                    <span class="text-danger">
                                                                        {{ number_format($gpa / $count, 2, '.', '') }}
                                                                    </span>
                                                                @else
                                                                    <span>
                                                                        {{ number_format($gpa / $count, 2, '.', '') }}
                                                                    </span>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary update-intake"
                                                            data-intake="{{ json_encode($student) }}">
                                                            <i class="feather-info text-white"
                                                                data-intake="{{ json_encode($student) }}"></i>
                                                        </button>
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
    @include('front-end.layouts._admin.intakes.add_teacher_modal')
    @include('front-end.layouts._admin.intakes.add_student_modal')
    @include('front-end.layouts._admin.intakes.update_student_intake_member_modal')
@endsection
