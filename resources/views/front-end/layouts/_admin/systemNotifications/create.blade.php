@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
@endsection

@push('js_page')
    <script>
        let content = CKEDITOR.replace('content');
        let content_en = CKEDITOR.replace('content_en');
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
                                <a href="/admin/notifications">
                                    {{ __('texts.texts.system_notifications.' . auth()->user()->lang) }} /
                                </a>
                                {{ __('texts.texts.add.' . auth()->user()->lang) }}
                            </h1>
                            <div class="mt-3">
                                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                    @if (session($msg))
                                        <div>
                                            <div class="alert alert-{{ $msg }}">
                                                {{ session($msg) }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <form action="/admin/notifications/create" method="POST" class="row" autocomplete="off">
                                    @csrf
                                    <div class="col-md-12 mt-2">
                                        <label for="title">
                                            {{ __('texts.texts.title.' . auth()->user()->lang) }}
                                        </label>
                                        <input type="text" id="title" name="title" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="content">
                                            {{ __('texts.texts.content.' . auth()->user()->lang) }}
                                        </label>
                                        <textarea id="content" name="content" class="form-control" required></textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="content_en">
                                            {{ __('texts.texts.content_en.' . auth()->user()->lang) }}
                                        </label>
                                        <textarea id="content_en" name="content_en" class="form-control" required></textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <button class="btn btn-primary text-white" type="submit">
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
