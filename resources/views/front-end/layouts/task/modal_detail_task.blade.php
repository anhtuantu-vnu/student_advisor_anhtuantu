<div class="modal_customer" tabindex="-1">
    <div class="modal-content pt-3 ps-3 pe-3">
        <div class="rounded-0 text-left">
            {{--title--}}
            <div class="title_task d-flex">
                <i class="feather-bookmark text-grey-900"
                   style="margin-top: 2px; font-size: 20px;"></i>
                <div class="title_task_text ms-2">
{{--                    <h2 style="margin-bottom: 0">{{$dataTask['name']}}</h2>--}}
{{--                    <p class="mb-3" style="font-size: 14px">in list {{ __("texts.texts.$key." . auth()->user()->lang) }}</p>--}}
                        <h2 style="margin-bottom: 0">TEST 1</h2>
                        <p class="mb-3" style="font-size: 14px">in list TODO</p>
                </div>
            </div>

            {{--Member--}}
            <div class="assign_to mt-1">
                <div class="assign_to_text d-flex">
                    <i class="feather-share text-grey-900"
                       style="margin-top: 2px; font-size: 20px;"></i>
                    <h2 class="ms-2">{{ __("texts.texts.assign_to." . auth()->user()->lang) }}</h2>
                </div>
                <div class="list_member_modal_task">
                    <select class="p-2 rounded">
{{--                        @foreach($listMember as $member)--}}
{{--                            <option--}}
{{--                                value={{$member['email']}}>{{ sprintf("%s %s", $member['first_name'], $member['last_name']) }}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
            </div>

            {{--DESCRIPTION--}}
            <div class="description mt-4">
                <div class="description_text d-flex">
                    <i class="feather-book-open text-grey-900"
                       style="margin-top: 2px; font-size: 20px;"></i>
                    <h2 class="ms-2">{{ __("texts.texts.description." . auth()->user()->lang) }}</h2>
                </div>
                <textarea class="description_modal p-3 lh-16" name="description" rows="5">
                                                                        {{"Add a more detailed description..."}}
                                                                    </textarea>
            </div>
            {{--Activity--}}
            <div class="activity mt-3">
                <div class="activity_text d-flex">
                    <i class="feather-activity text-grey-900"
                       style="margin-top: 2px; font-size: 20px;"></i>
                    <h2 class="ms-2">{{ __("texts.texts.activity." . auth()->user()->lang) }}</h2>
                </div>
                <div class="activity_comment">
                    <img src="https://cdn.chanhtuoi.com/uploads/2022/01/hinh-avatar-nam-deo-kinh.jpg"/>
                    <div class="activity_comment_input">
                        <input type="text" class="input_comment" name="comment" row="5"
                               placeholder="{{ __("texts.texts.write_a_comment." . auth()->user()->lang) }}">
                    </div>
                </div>
            </div>
            <div class="list_comment">

            </div>

            {{--Footer modal--}}
            <div class="modal-footer" style="border:none; padding: 0.75rem 0 !important;">
                <button type="button" id="btn_close_modal" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("texts.texts.close." . auth()->user()->lang) }}</button>
                <button type="button" class="btn btn-success" style="margin-right: 0 !important;">{{ __("texts.texts.update." . auth()->user()->lang) }}</button>
            </div>
        </div>
    </div>
</div>
