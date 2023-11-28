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
                                @if ($type == 'teacher')
                                    {{ __('texts.texts.teachers.' . auth()->user()->lang) }}
                                @else
                                    {{ __('texts.texts.students.' . auth()->user()->lang) }}
                                @endif
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
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <div style="margin-right: 8px;">
                                                            <img src="{{ $user->avatar }}"
                                                                alt="{{ $user->last_name }}_avatar"
                                                                style="width: 56px; height: 56px; object-fit: cover; border-radius: 100%;"
                                                                class="border">
                                                        </div>
                                                        <div>
                                                            {{ $user->last_name . ' ' . $user->first_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="/users/{{ $user->uuid }}">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="feather-info text-white font-lg"></i>
                                                        </button>
                                                    </a>
                                                    <a href="/admin/users/{{ $user->uuid }}/update">
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
                                    {!! $users->appends($_GET)->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
