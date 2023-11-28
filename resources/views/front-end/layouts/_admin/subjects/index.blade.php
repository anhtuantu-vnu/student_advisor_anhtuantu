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
                                {{ __('texts.texts.subjects.' . auth()->user()->lang) }}
                            </h1>
                            <div>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">
                                                #
                                            </th>
                                            <th scope="col">
                                                {{ __('texts.texts.name.' . auth()->user()->lang) }}
                                            </th>
                                            <th scope="col">
                                                {{ __('texts.texts.action.' . auth()->user()->lang) }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="departments-tbody">
                                        @foreach ($subjects as $index => $subject)
                                            <tr>
                                                <td>
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>
                                                    {{ json_decode($subject->name, true)[auth()->user()->lang] }}
                                                </td>
                                                <td>
                                                    <a href="/admin/subjects/{{ $subject->uuid }}/update">
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
                                    {!! $subjects->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
