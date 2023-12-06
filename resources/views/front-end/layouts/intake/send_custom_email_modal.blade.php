<div data-bs-toggle="modal" data-bs-target="#customEmailModal" class="d-none" id="openCustomEmailModalButton"></div>
<div class="modal fade" id="customEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="sendCustomEmailModalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                <form action="">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <input type="email" class="form-control" id="customEmailToStudent" disabled
                                placeholder="{{ __('texts.texts.full_name.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="email" class="form-control" id="customEmailSubject"
                                placeholder="{{ __('texts.texts.subject.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="email" class="form-control" id="customEmailToEmail"
                                placeholder="{{ __('texts.texts.email.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <input type="email" class="form-control" id="customEmailCcEmail"
                                placeholder="{{ __('texts.texts.cc_email.' . auth()->user()->lang) }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <textarea id="customEmailContent" class="bor-0 w-100 rounded-xxl p-2 text-grey-600 fw-500 border-light-md theme-dark-bg"
                                cols="30" rows="10" placeholder="{{ __('texts.texts.content.' . auth()->user()->lang) }}"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <button class="btn btn-success text-white" type="button" id="sendCustomEmailButton">
                                <div class="d-flex align-items-center">
                                    <div style="margin-right: 8px;">
                                        {{ __('texts.texts.send.' . auth()->user()->lang) }}
                                    </div>
                                    <div class="text-center d-none" id="sendEmailLoading">
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
