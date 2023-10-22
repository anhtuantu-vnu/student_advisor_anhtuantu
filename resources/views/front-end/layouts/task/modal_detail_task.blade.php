<div class="modal bottom fade" style="overflow-y: scroll;" id="ModelTask" tabindex="-1" role="dialog">
{{--<div class="modal bottom fade show bg-black" style="overflow-y: scroll; display: block;" id="ModelTask" tabindex="-1"--}}
{{--     aria-modal="true" role="dialog">--}}
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-body d-flex align-items-center bg-none">
                <div class="card shadow-none rounded-0 border-0 w-100">
                    <div class="rounded-0 text-left">
                        {{--title--}}
                        <div class="title_task d-flex">
                            <i class="feather-bookmark text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                            <div class="title_task_text ms-2">
                                <h2>App development</h2>
                                <p>in list To Do</p>
                            </div>
                        </div>
                        {{--Member--}}
                        <div class="assign_to mt-1">
                            <div class="assign_to_text d-flex">
                                <i class="feather-share text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                <h2 class="ms-2">Assign to</h2>
                            </div>
                            <div class="list_member">
                                <select class="p-2 rounded">
                                    <option value="0">Unassigned</option>
                                    <option value="1">Nguyễn Quốc Nam</option>
                                    <option value="2">Nguyễn Văn A</option>
                                </select>
                            </div>
                        </div>
                        {{--DESCRIPTION--}}
                        <div class="description mt-4">
                            <div class="description_text d-flex">
                                <i class="feather-book-open text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                <h2 class="ms-2">Description</h2>
                            </div>
                            <textarea class="form-control input_description mb-0 p-3 h100 bg-greylight lh-16" name="description" rows="5" placeholder="Add a more detailed description..." spellcheck="false" required></textarea>
                        </div>
                        {{--Activity--}}
                        <div class="activity mt-4">
                            <div class="activity_text d-flex">
                                <i class="feather-activity text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                <h2 class="ms-2">Activity</h2>
                            </div>
                            <div class="activity_comment">
                                <img src="https://cdn.chanhtuoi.com/uploads/2022/01/hinh-avatar-nam-deo-kinh.jpg" class="" style="height: 20px; width: 20px"/>
                                <div class="activity_comment_input">

                                </div>
                            </div>
                        </div>
                        <div class="list_comment">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
