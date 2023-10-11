@extends("front-end.layouts.index")

@section('style_page')
    <link rel="stylesheet" href="{{ asset("css/bootstrap-datetimepicker.css") }}">
    <link rel="stylesheet" href="{{ asset("css/layout_custom.css") }}"/>
@endsection

@push('js_page')
    <script>
        //set % for process
        let setPerCentForProcess = setInterval(() => {
            const progress = document.querySelector('.progress-done');
            if (progress) {
                progress.style.width = progress.getAttribute('data-done') + '%';
                progress.style.opacity = 1;
                clearInterval(setPerCentForProcess);
            }
        })

    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                            <div class="card-body d-flex align-items-center p-0 mb-3">
                                <div class="plan_header">
                                    <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                        Plan</h1>
                                    <div class="d-flex justify-content-between">
                                        <div class="item-status">
                                            <span class="status-number">45</span>
                                            <span class="status-type">In Progress</span>
                                        </div>
                                        <div class="item-status">
                                            <span class="status-number">24</span>
                                            <span class="status-type">Upcoming</span>
                                        </div>
                                        <div class="item-status">
                                            <span class="status-number">62</span>
                                            <span class="status-type">Total Projects</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="search-form-2 ms-auto">
                                    <i class="ti-search font-xss"></i>
                                    <input type="text"
                                           class="form-control text-grey-500 mb-0 bg-greylight theme-dark-bg border-0"
                                           placeholder="Search here.">
                                </div>
                                <a href="#" class="btn-round-md ms-2 bg-greylight theme-dark-bg rounded-3"><i
                                        class="feather-filter font-xss text-grey-500"></i></a>
                                <a href="{{ route("ui_create_plan") }}"
                                   style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"
                                   class="ms-2 bg-current theme-dark-bg rounded-3">Create plan</a>
                            </div>

                            {{--UI list plan--}}
                            <div class="row ps-2 pe-1">
                                @foreach($dataPlan['list_plan'] as $plan)
                                    <div class="projects-section plan mt-3">
                                        <div class="project-boxes jsListView">
                                            <div class="project-box-wrapper">
                                                <div class="project-box"
                                                     style="background-color: {{ $plan['settings']['background_color'] }}">
                                                    <div class="project-box-content-header me-5">
                                                        <span class="box-content-header" data-max-width="20vw"
                                                              data-tooltip-title="{{ $plan['name'] }}">
                                                            <p class="overflow-hidden" style="text-overflow:ellipsis; white-space: nowrap">{{ $plan['name'] }}</p>
                                                        </span>
                                                        <p class="box-content-subheader">{{ $plan['status'] }}</p>
                                                        <p style="font-size: 12px; line-height: 12px">{{ $plan['date_created'] }}</p>
                                                    </div>
                                                    <div class="box-progress-wrapper me-5">
                                                        <p class="box-progress-header">Progress</p>
                                                        <div class="box-progress-bar">
                                                            <span
                                                                class="box-progress"
                                                                style="width: {{ $plan['percent'] }}%; background-color: {{ $plan['settings']['color'] }}"
                                                            ></span>
                                                        </div>
                                                        <p class="box-progress-percentage">{{ $plan['percent'] }}%</p>
                                                    </div>
                                                    <div class="project-box-footer">
                                                        <div class="participants">
                                                            <img
                                                                src="https://lh3.googleusercontent.com/a/ACg8ocIpKC5-v6W_3y0DNt4lw7nIzeWdOPVcrsPOecHe8PwE=s96-c"
                                                                alt="participant"
                                                            />
                                                            <img
                                                                src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?ixid=MXwxMjA3fDB8MHxzZWFyY2h8MTB8fG1hbnxlbnwwfHwwfA%3D%3D&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=900&amp;q=60"
                                                                alt="participant"
                                                            />
                                                            <button class="add-participant"
                                                                    style="color: {{ $plan['settings']['color'] }}">+
                                                            </button>
                                                        </div>
                                                        <div class="days-left"
                                                             style="color: {{ $plan['settings']['color'] }}">
                                                            @if($plan['count_date'])
                                                                {{ $plan['count_date'] }} left
                                                            @else
                                                                Today
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


