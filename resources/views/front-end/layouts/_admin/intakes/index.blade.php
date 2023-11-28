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
                                {{ __('texts.texts.intakes.' . auth()->user()->lang) }}
                            </h1>
                            <div>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">
                                                #
                                            </th>
                                            <th scope="col">
                                                {{ __('texts.texts.code.' . auth()->user()->lang) }}
                                            </th>
                                            <th scope="col">
                                                {{ __('texts.texts.subject_.' . auth()->user()->lang) }}
                                            </th>
                                            <th scope="col">
                                                {{ __('texts.texts.information.' . auth()->user()->lang) }}
                                            </th>
                                            <th scope="col">
                                                {{ __('texts.texts.action.' . auth()->user()->lang) }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="departments-tbody">
                                        @foreach ($intakes as $index => $intake)
                                            <tr>
                                                <td>
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>
                                                    {{ $intake->code }}
                                                </td>
                                                <td>
                                                    <a href="/admin/subjects/{{ $intake->subject_id }}/update">
                                                        {{ json_decode($intake->subject->name, true)[auth()->user()->lang] }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul style="list-style-type: circle;">
                                                        <li>
                                                            <b>
                                                                {{ __('texts.texts.week_days.' . auth()->user()->lang) }}:
                                                            </b>
                                                            {{ format_intake_week_days(auth()->user()->lang, $intake->week_days) }}
                                                        </li>
                                                        <li>
                                                            <b>
                                                                {{ __('texts.texts.intake_time.' . auth()->user()->lang) }}:
                                                            </b>
                                                            {{ \Carbon\Carbon::parse($intake->start_date)->format('d/m/Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($intake->end_date)->format('d/m/Y') }}
                                                        </li>
                                                        <li>
                                                            <b>
                                                                {{ __('texts.texts.intake_time_hour.' . auth()->user()->lang) }}:
                                                            </b>
                                                            {{ ($intake->start_hour >= 10 ? $intake->start_hour : '0' . $intake->start_hour) . ':' . ($intake->start_minute >= 10 ? $intake->start_minute : '0' . $intake->start_minute) }}
                                                            -
                                                            {{ ($intake->end_hour >= 10 ? $intake->end_hour : '0' . $intake->end_hour) . ':' . ($intake->end_minute >= 10 ? $intake->end_minute : '0' . $intake->end_minute) }}
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <a href="/admin/intakes/{{ $intake->uuid }}/detail">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="feather-info text-white font-lg"></i>
                                                        </button>
                                                    </a>
                                                    <a href="/admin/intakes/{{ $intake->uuid }}/update">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="feather-edit text-white font-lg"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex-justify-content-center">
                                    {!! $intakes->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
