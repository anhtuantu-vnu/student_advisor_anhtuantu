<div data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal" class="d-none" id="openScheduleMeetingModalButton"></div>
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    {{ __('texts.texts.schedule_meeting.' . auth()->user()->lang) }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                <form action="">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div id="chosenStudentNamesModal" class="d-flex flex-wrap"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="text" class="form-control" id="meetingName"
                                placeholder="{{ __('texts.texts.meeting_name.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="datetime-local" class="form-control" id="meetingStartTime"
                                placeholder="{{ __('texts.texts.event_start_time.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="datetime-local" class="form-control" id="meetingEndTime"
                                placeholder="{{ __('texts.texts.event_end_time.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="text" class="form-control" id="meetingLocation"
                                placeholder="{{ __('texts.texts.location.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="input-color-container">
                                <input type="color" class="input-color" id="meetingColor" name="eventColor"
                                    value="#9DA9E1">
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <textarea id="meetingDescription" class="bor-0 w-100 rounded-xxl p-2 text-grey-600 fw-500 border-light-md theme-dark-bg"
                                cols="30" rows="10" placeholder="{{ __('texts.texts.description.' . auth()->user()->lang) }}"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <button class="btn btn-success text-white" type="button" id="saveMeetingButton">
                                <div class="d-flex align-items-center">
                                    <div style="margin-right: 8px;">
                                        {{ __('texts.texts.save.' . auth()->user()->lang) }}
                                    </div>
                                    <div class="text-center d-none" id="scheduleMeetingLoading">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
