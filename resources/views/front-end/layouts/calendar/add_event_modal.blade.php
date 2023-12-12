<div class="modal fade" id="addEventCalendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    {{ __('texts.texts.add.' . auth()->user()->lang) }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeAddEventModal"></button>
            </div>
            <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                <form action="" id="addEventForm">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <input type="text" class="form-control" id="addEventName"
                                placeholder="{{ __('texts.texts.event_name.' . auth()->user()->lang) . ' ' }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="text" class="form-control" id="addEventLocation"
                                placeholder="{{ __('texts.texts.location.' . auth()->user()->lang) . ' ' }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="addEventStartTime">
                                {{ __('texts.texts.event_start_time.' . auth()->user()->lang) . ' ' }}
                            </label>
                            <input type="datetime-local" class="form-control" id="addEventStartTime"
                                placeholder="{{ __('texts.texts.start_time.' . auth()->user()->lang) . ' ' }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="addEventEndTime">
                                {{ __('texts.texts.event_end_time.' . auth()->user()->lang) . ' ' }}
                            </label>
                            <input type="datetime-local" class="form-control" id="addEventEndTime"
                                placeholder="{{ __('texts.texts.end_time.' . auth()->user()->lang) . ' ' }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="input-color-container">
                                <input type="color" class="input-color" id="addEventColor"
                                    placeholder="{{ __('texts.texts.color.' . auth()->user()->lang) . ' ' }}">
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <textarea id="addEventDescription"
                                class="bor-0 w-100 rounded-xxl p-2 text-grey-600 fw-500 border-light-md theme-dark-bg" cols="30" rows="10"
                                placeholder="{{ __('texts.texts.event_description.' . auth()->user()->lang) }}"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary text-white" type="button" id="saveAddEventButton">
                                {{ __('texts.texts.save.' . auth()->user()->lang) . ' ' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

