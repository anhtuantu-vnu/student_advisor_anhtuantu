@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@push('js_page')
    <script>
        let updateIntakeForm = document.getElementById("updateIntakeForm");
        let saveFormUpdateButton = document.getElementById("saveFormUpdateButton");

        saveFormUpdateButton.addEventListener("click", e => {
            e.preventDefault();

            let code = document.getElementById("code").value;
            let subject = document.getElementById("subject").value;
            let start_date = document.getElementById("start_date").value;
            let end_date = document.getElementById("end_date").value;
            let start_time = document.getElementById("start_time").value;
            let end_time = document.getElementById("end_time").value;
            let location = document.getElementById("location").value;

            let weekDays = Array.from(document.querySelectorAll(".week-day-check-boxes")).filter(item => {
                return item.checked == true;
            }).map(item => item.value);

            if (!code) {
                alert("{{ __('texts.texts.code_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!subject) {
                alert("{{ __('texts.texts.subject_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!start_date || !end_date || !start_time || !end_time || !weekDays.length) {
                alert("{{ __('texts.texts.time_information_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!location) {
                alert("{{ __('texts.texts.location_required.' . auth()->user()->lang) }} /");
                return;
            }

            if (!start_date || !end_date || !start_time || !end_time) {
                alert("{{ __('texts.texts.time_information_required.' . auth()->user()->lang) }} /");
                return;
            }

            let formData = "code=" + code +
                "&subject=" + subject +
                "&start_date=" + start_date +
                "&end_date=" + end_date +
                "&start_hour=" + parseInt(start_time.split(":")[0]) +
                "&start_minute=" + parseInt(start_time.split(":")[1]) +
                "&end_hour=" + parseInt(end_time.split(":")[0]) +
                "&end_minute=" + parseInt(end_time.split(":")[1]) +
                "&location=" + location +
                "&weekDays=" + weekDays.join(',');
            $.ajax({
                url: "/admin/intakes/{{ $intake->uuid }}/update",
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

        updateIntakeForm.addEventListener("submit", e => {
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
                                <a href="/admin/intakes">
                                    {{ __('texts.texts.intakes.' . auth()->user()->lang) }} /
                                </a>
                                {{ $intake->code }}
                            </h1>
                            <div class="mt-3">
                                <form class="row" id="updateIntakeForm" autocomplete="off">
                                    <div class="col-md-6 mt-2">
                                        <label for="code">
                                            {{ __('texts.texts.code.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="code" name="code" class="form-control"
                                            value="{{ $intake->code }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="subject">
                                            {{ __('texts.texts.subject_.' . auth()->user()->lang) }}
                                        </label>
                                        <select name="subject" id="subject" class="form-control">
                                            <option value="">
                                                {{ __('texts.texts.choose_an_option.' . auth()->user()->lang) }}
                                            </option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->uuid }}"
                                                    <?php if($intake->subject_id == $subject->uuid) { ?>selected<?php } ?>>
                                                    {{ json_decode($subject->name, true)[auth()->user()->lang] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="start_date">
                                            {{ __('texts.texts.start_date.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            value="{{ $intake->start_date }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="end_date">
                                            {{ __('texts.texts.end_date.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="date" id="end_date" name="end_date" class="form-control"
                                            value="{{ $intake->end_date }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="start_time">
                                            {{ __('texts.texts.start_time.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="time" id="start_time" name="start_time" class="form-control"
                                            value="{{ ($intake->start_hour >= 10 ? $intake->start_hour : '0' . $intake->start_hour) . ':' . ($intake->start_minute >= 10 ? $intake->start_minute : '0' . $intake->start_minute) }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="end_time">
                                            {{ __('texts.texts.end_time.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="time" id="end_time" name="end_time" class="form-control"
                                            value="{{ ($intake->end_hour >= 10 ? $intake->end_hour : '0' . $intake->end_hour) . ':' . ($intake->end_minute >= 10 ? $intake->end_minute : '0' . $intake->end_minute) }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="location">
                                            {{ __('texts.texts.location.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="location" name="location" class="form-control"
                                            value="{{ $intake->location }}">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="week_days">
                                            {{ __('texts.texts.week_days.' . auth()->user()->lang) }}
                                        </label>
                                        <br>
                                        <?php
                                        $weekDaysMap = auth()->user()->lang == 'vi' ? \App\Models\Intake::WEEKDAYS_MAP_VI : \App\Models\Intake::WEEKDAYS_MAP;
                                        ?>
                                        @foreach ($weekDaysMap as $key => $weekDay)
                                            <div>
                                                <input type="checkbox" class="week-day-check-boxes"
                                                    <?php if(str_contains($intake->week_days, $key)) { ?>checked<?php } ?>
                                                    value="{{ $key }}" name="week_days"> {{ $weekDay }}
                                            </div>
                                        @endforeach
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
