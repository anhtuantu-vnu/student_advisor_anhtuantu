@extends('front-end.layouts.index')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout_custom.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />
@endsection

@push('js_page')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function getEventsCalendar() {
                $.ajax({
                    url: $("#getPassengersByAdultChildrenInfantInput").val(),
                    type: "POST",
                    data: formData,
                    success: function(result) {
                        let passengerRes = JSON.parse(result);
                        if (passengerRes.status == "success") {
                            isPassengerInvalid = false;
                            $("#passenger-error").addClass("d-none");
                        } else {
                            isPassengerInvalid = true;
                            $("#passenger-error").removeClass("d-none");
                        }
                    }
                });
            }

            let formData = $("#portletHeaderNamespaceInput").val() + "adult=" + adult +
                "&" + $("#portletHeaderNamespaceInput").val() + "child=" + child +
                "&" + $("#portletHeaderNamespaceInput").val() + "infant=" + infant;


            var calendarEl = document.getElementById('huet_calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
            })

            calendar.setOption('locale', 'vn');
            calendar.on('dateClick', function(info) {
                console.log('info', info);
                console.log('clicked on ' + info.dateStr);
            });
            calendar.render();
        });
    </script>
@endpush

@section('content')
    <div class="main-content right-chat-active">
        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0">
                <div class="row">
                    <div class="col-12 p-5 bg-white">
                        <div class="plan_header">
                            <h1 class="fw-700 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 34px !important;">
                                {{ __('texts.texts.events') }}
                            </h1>
                            <div>
                                <div class="response"></div>
                                <div id='huet_calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
