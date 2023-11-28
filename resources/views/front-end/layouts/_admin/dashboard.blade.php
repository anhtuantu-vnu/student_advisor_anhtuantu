@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <style>
        .dashboard-row .card:hover {
            background: #d6d6d6 !important;
        }
    </style>
@endsection

@push('js_page')
    <script></script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row dashboard-row">
                    <div class="col-md-6 p-3">
                        <div class="card bg-white rounded p-3">
                            <div class="plan_header">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div>
                                        <i class="feather-users me-3"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <a href="/admin/users?type=teacher">
                                                {{ __('texts.texts.teachers.' . auth()->user()->lang) }}
                                            </a>
                                        </div>
                                        {{ $teachersCount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="card bg-white rounded p-3">
                            <div class="plan_header">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div>
                                        <i class="feather-user-check me-3"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <a href="/admin/users?type=student">
                                                {{ __('texts.texts.students.' . auth()->user()->lang) }}
                                            </a>
                                        </div>
                                        {{ $studentsCount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="card bg-white rounded p-3">
                            <div class="plan_header">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div>
                                        <i class="feather-home me-3"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <a href="/admin/departments">
                                                {{ __('texts.texts.departments.' . auth()->user()->lang) }}
                                            </a>
                                        </div>
                                        {{ $departmentsCount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="card bg-white rounded p-3">
                            <div class="plan_header">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div>
                                        <i class="feather-hash me-3"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <a href="/admin/subjects">
                                                {{ __('texts.texts.subjects.' . auth()->user()->lang) }}
                                            </a>
                                        </div>
                                        {{ $subjectsCount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="card bg-white rounded p-3">
                            <div class="plan_header">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div>
                                        <i class="feather-tag me-3"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <a href="/admin/classes">
                                                {{ __('texts.texts.classes.' . auth()->user()->lang) }}
                                            </a>
                                        </div>
                                        {{ $classesCount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="card bg-white rounded p-3">
                            <div class="plan_header">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div>
                                        <i class="feather-bookmark me-3"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <a href="/admin/intakes">
                                                {{ __('texts.texts.intakes.' . auth()->user()->lang) }}
                                            </a>
                                        </div>
                                        {{ $intakesCount }}
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
