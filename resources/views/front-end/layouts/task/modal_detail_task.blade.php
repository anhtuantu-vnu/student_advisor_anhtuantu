@foreach($tasks as $key => $task)
    @php
    $configLayout = [
        'tasks_to_do' => "To Do",
        'task_in_process' => "In Process",
        'task_done' => 'Done',
        'task_review' => 'Review'
    ];
    @endphp
    @if(in_array($key, ['tasks_to_do' , 'tasks_in_process', 'task_done', 'task_review']))
        @foreach($task as $index => $dataTask)
            <div class="modal bottom fade modal_detail_task"  style="overflow-y: scroll;" id="{{$key}}_{{$index}}" tabindex="-1" role="dialog">
                {{--<div class="modal bottom fade show bg-black" style="overflow-y: scroll; display: block;" id="ModelTask" tabindex="-1"--}}
                aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-body d-flex align-items-center bg-none">
                            <div class="card shadow-none rounded-0 border-0 w-100">
                                <div class="rounded-0 text-left">
                                    {{--title--}}
                                    <div class="title_task d-flex">
                                        <i class="feather-bookmark text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                        <div class="title_task_text ms-2">
                                            <h2 style="margin-bottom: 0">{{$dataTask['name']}}</h2>
                                            <p class="mb-3" style="font-size: 14px">in list {{$configLayout[$key]}}</p>
                                        </div>
                                    </div>

                                    {{--Member--}}
                                    <div class="assign_to mt-1">
                                        <div class="assign_to_text d-flex">
                                            <i class="feather-share text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                            <h2 class="ms-2">Assign to</h2>
                                        </div>
                                        <div class="list_member_modal_task">
                                            <select class="p-2 rounded">
                                                @foreach($listMember as $member)
                                                    <option value={{$member['email']}}>{{ sprintf("%s %s", $member['first_name'], $member['last_name']) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--DESCRIPTION--}}
                                    <div class="description mt-4">
                                        <div class="description_text d-flex">
                                            <i class="feather-book-open text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>
                                            <h2 class="ms-2">Description</h2>
                                        </div>
                                        <textarea class="description_modal p-3 lh-16" name="description" rows="5">
                                            {{$dataTask['description'] ?? "Add a more detailed description..."}}
                                        </textarea>
                                    </div>
                                    {{--Activity--}}
{{--                                    <div class="activity mt-3">--}}
{{--                                        <div class="activity_text d-flex">--}}
{{--                                            <i class="feather-activity text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>--}}
{{--                                            <h2 class="ms-2">Activity</h2>--}}
{{--                                        </div>--}}
{{--                                        <div class="activity_comment">--}}
{{--                                            <img src="https://cdn.chanhtuoi.com/uploads/2022/01/hinh-avatar-nam-deo-kinh.jpg" />--}}
{{--                                            <div class="activity_comment_input">--}}
{{--                                                <input type="text" class="input_comment" name="comment" row="5" placeholder="Write a comment">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="list_comment">--}}

{{--                                    </div>--}}

                                    <div class="d-flex mt-4 justify-content-end">
                                        <a
                                           style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"
                                           class="btn ms-2 bg-danger color-theme-red rounded-3"
                                            id="btn_cancel_modal_task"
                                        >Cancel</a>
                                        <a
                                           style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"
                                           class="btn ms-2 bg-current theme-dark-bg rounded-3">Update</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endforeach
