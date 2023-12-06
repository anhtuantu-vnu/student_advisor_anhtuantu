@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />
@endsection

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white rounded-xxl">
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ __('texts.texts.user_info.' . auth()->user()->lang) }}
                            </h1>
                            <div>
                                @if (auth()->user()->role != App\Http\Controllers\_CONST::STUDENT_ROLE ||
                                        $thisUser->allow_search_by_teacher_only == false ||
                                        $thisUser->role == App\Http\Controllers\_CONST::TEACHER_ROLE ||
                                        $thisUser->uuid == auth()->user()->uuid)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img src="{{ $thisUser->avatar }}" alt="{{ $thisUser->last_name }}_logo"
                                                class="border"
                                                style="width: 96px; height: 96px; object-fit: cover; border-radius: 100%;">
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
                                                    @if ($thisUser->department != null)
                                                        {{ json_decode($thisUser->department->name, true)[$thisUser->lang] }}
                                                    @endif
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
                                                    <a href="mailto:{{ $thisUser->email }}">{{ $thisUser->email }}</a>
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
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>{{ __('texts.texts.private_account.' . $thisUser->lang) }}</b>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if (auth()->user()->role != App\Http\Controllers\_CONST::STUDENT_ROLE)
                                <div class="mt-3">
                                    <a href="/admin/users/{{ $thisUser->uuid }}/update">
                                        <button class="btn btn-primary text-white" type="button">
                                            {{ __('texts.texts.update.' . $thisUser->lang) }}
                                        </button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
