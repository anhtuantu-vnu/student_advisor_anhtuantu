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
                {{--            <div class="modal bottom fade show bg-black modal_window" style="overflow-y: scroll; display: block;"--}}
                {{--                 id="{{$dataTask['id']}}" tabindex="-1"--}}
                {{--                 aria-modal="true" role="dialog">--}}
                {{--                <div class="modal-dialog modal-dialog-centered" role="document">--}}
                {{--                    <div class="modal-content border-0">--}}
                {{--                        <div class="modal-body d-flex align-items-center bg-none">--}}
                {{--                            <div class="card shadow-none rounded-0 border-0 w-100">--}}
                {{--                                --}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                            <div class="rounded-0 text-left">--}}
                {{--                                --}}{{--title--}}
                {{--                                <div class="title_task d-flex">--}}
                {{--                                    <i class="feather-bookmark text-grey-900"--}}
                {{--                                       style="margin-top: 2px; font-size: 20px;"></i>--}}
                {{--                                    <div class="title_task_text ms-2">--}}
                {{--                                        <h2 style="margin-bottom: 0">{{$dataTask['name']}}</h2>--}}
                {{--                                        <p class="mb-3" style="font-size: 14px">in list {{$configLayout[$key]}}</p>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}

                {{--                                --}}{{--Member--}}
                {{--                                <div class="assign_to mt-1">--}}
                {{--                                    <div class="assign_to_text d-flex">--}}
                {{--                                        <i class="feather-share text-grey-900"--}}
                {{--                                           style="margin-top: 2px; font-size: 20px;"></i>--}}
                {{--                                        <h2 class="ms-2">Assign to</h2>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="list_member_modal_task">--}}
                {{--                                        <select class="p-2 rounded">--}}
                {{--                                            @foreach($listMember as $member)--}}
                {{--                                                <option--}}
                {{--                                                    value={{$member['email']}}>{{ sprintf("%s %s", $member['first_name'], $member['last_name']) }}</option>--}}
                {{--                                            @endforeach--}}
                {{--                                        </select>--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}

                {{--                                --}}{{--DESCRIPTION--}}
                {{--                                <div class="description mt-4">--}}
                {{--                                    <div class="description_text d-flex">--}}
                {{--                                        <i class="feather-book-open text-grey-900"--}}
                {{--                                           style="margin-top: 2px; font-size: 20px;"></i>--}}
                {{--                                        <h2 class="ms-2">Description</h2>--}}
                {{--                                    </div>--}}
                {{--                                    <textarea class="description_modal p-3 lh-16" name="description" rows="5">--}}
                {{--                                            {{$dataTask['description'] ?? "Add a more detailed description..."}}--}}
                {{--                                        </textarea>--}}
                {{--                                </div>--}}
                {{--                                --}}{{--Activity--}}
                {{--                                --}}{{--                                    <div class="activity mt-3">--}}
                {{--                                --}}{{--                                        <div class="activity_text d-flex">--}}
                {{--                                --}}{{--                                            <i class="feather-activity text-grey-900" style="margin-top: 2px; font-size: 20px;"></i>--}}
                {{--                                --}}{{--                                            <h2 class="ms-2">Activity</h2>--}}
                {{--                                --}}{{--                                        </div>--}}
                {{--                                --}}{{--                                        <div class="activity_comment">--}}
                {{--                                --}}{{--                                            <img src="https://cdn.chanhtuoi.com/uploads/2022/01/hinh-avatar-nam-deo-kinh.jpg" />--}}
                {{--                                --}}{{--                                            <div class="activity_comment_input">--}}
                {{--                                --}}{{--                                                <input type="text" class="input_comment" name="comment" row="5" placeholder="Write a comment">--}}
                {{--                                --}}{{--                                            </div>--}}
                {{--                                --}}{{--                                        </div>--}}
                {{--                                --}}{{--                                    </div>--}}
                {{--                                --}}{{--                                    <div class="list_comment">--}}

                {{--                                --}}{{--                                    </div>--}}

                {{--                                <div class="d-flex mt-4 justify-content-end">--}}
                {{--                                    <a--}}
                {{--                                        style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"--}}
                {{--                                        class="btn ms-2 bg-danger color-theme-red rounded-3"--}}
                {{--                                        id="btn_cancel_modal_task"--}}
                {{--                                    >Cancel</a>--}}
                {{--                                    <a--}}
                {{--                                        style="padding: 10px; color: white; display:flex; align-items: center; font-size: 14px; font-weight: 500"--}}
                {{--                                        class="btn ms-2 bg-current theme-dark-bg rounded-3">Update</a>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}

                <div id="#{{$key}}_{{$index}}" class="modal_window">
                    <div>
                        <a href="#" title="Close" class="modal-close">Close</a>
                        <h1>Voilà!</h1>
                        <div>A CSS-only modal based on the :target pseudo-class. Hope you find it helpful.</div>
                        <br>
                        <div><small>Check out 👇</small></div>
                        <svg class="logo" width="244" height="52" viewBox="0 0 244 52" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M25.6002 0.000182857V25.6002H51.2002C51.2002 18.8105 48.503 12.2992 43.7019 7.49824C38.9012 2.69714 32.3895 0 25.6 0L25.6002 0.000182857Z"
                                fill="#F6BAC1"/>
                            <path
                                d="M0.000182757 0.000182857V25.6002H25.6002C25.6002 18.8105 22.9031 12.2992 18.102 7.49824C13.3012 2.69714 6.78949 0 0 0L0.000182757 0.000182857Z"
                                fill="#016B55"/>
                            <path
                                d="M25.6002 25.6003V51.2003H51.2002C51.2002 44.4106 48.503 37.8993 43.7019 33.0983C38.9012 28.2972 32.3895 25.6001 25.6 25.6001L25.6002 25.6003Z"
                                fill="#F39202"/>
                            <path
                                d="M25.6001 38.4001C25.6001 45.4694 19.8694 51.2001 12.8001 51.2001C5.73086 51.2001 0.00012207 45.4694 0.00012207 38.4001C0.00012207 31.3308 5.73086 25.6001 12.8001 25.6001C19.8694 25.6001 25.6001 31.3308 25.6001 38.4001Z"
                                fill="#68B5D8"/>
                            <path
                                d="M97.7537 32.9839L96.8897 39.0319C95.6417 39.8639 94.3137 40.5039 92.9057 40.9519C91.4977 41.3679 90.0577 41.5759 88.5857 41.5759C84.9377 41.5759 81.9617 40.4079 79.6577 38.0719C77.3537 35.7359 76.2017 32.6799 76.2017 28.9039C76.2017 25.2559 77.4177 22.2319 79.8497 19.8319C82.3137 17.3999 85.4177 16.1839 89.1617 16.1839C90.7297 16.1839 92.1697 16.4079 93.4817 16.8559C94.7937 17.2719 96.0417 17.9439 97.2257 18.8719V25.2559H97.1297C95.6897 23.8799 94.3457 22.8879 93.0977 22.2799C91.8497 21.6399 90.5857 21.3199 89.3057 21.3199C87.1937 21.3199 85.4337 22.0399 84.0257 23.4799C82.6497 24.9199 81.9617 26.7119 81.9617 28.8559C81.9617 31.0639 82.6177 32.8719 83.9297 34.2799C85.2737 35.6879 86.9857 36.3919 89.0657 36.3919C90.4417 36.3919 91.8977 36.0879 93.4337 35.4799C94.9697 34.8399 96.3777 33.9919 97.6577 32.9359L97.7537 32.9839Z"
                                fill="black"/>
                            <path
                                d="M114.754 21.2719C113.666 21.2719 112.642 21.5279 111.682 22.0399C110.754 22.5519 109.65 23.4479 108.37 24.7279V40.9999H102.706V6.34387L108.37 4.56787V21.0799C109.65 19.3839 110.946 18.1519 112.258 17.3839C113.602 16.6159 115.106 16.2319 116.77 16.2319C119.33 16.2319 121.362 17.0799 122.866 18.7759C124.402 20.4719 125.17 22.7599 125.17 25.6399V40.9999H119.458V26.5039C119.458 24.8399 119.042 23.5599 118.21 22.6639C117.41 21.7359 116.258 21.2719 114.754 21.2719Z"
                                fill="black"/>
                            <path
                                d="M145.441 22.3759C144.065 22.3759 142.705 22.6639 141.361 23.2399C140.017 23.7839 138.497 24.7279 136.801 26.0719V40.9999H131.137V17.3359L136.753 16.3759V22.5199C138.545 20.1199 140.033 18.4879 141.217 17.6239C142.401 16.7599 143.649 16.3279 144.961 16.3279C145.217 16.3279 145.457 16.3439 145.681 16.3759C145.905 16.4079 146.177 16.4559 146.497 16.5199L146.881 22.5199C146.625 22.4559 146.385 22.4239 146.161 22.4239C145.937 22.3919 145.697 22.3759 145.441 22.3759Z"
                                fill="black"/>
                            <path
                                d="M161.795 41.6719C158.051 41.6719 154.963 40.4399 152.531 37.9759C150.131 35.5119 148.931 32.4879 148.931 28.9039C148.931 25.3519 150.163 22.3279 152.627 19.8319C155.091 17.3039 158.179 16.0399 161.891 16.0399C165.667 16.0399 168.755 17.2719 171.155 19.7359C173.555 22.1679 174.755 25.1759 174.755 28.7599C174.755 32.3439 173.523 35.3999 171.059 37.9279C168.595 40.4239 165.507 41.6719 161.795 41.6719ZM169.091 28.7599C169.091 26.5519 168.403 24.7119 167.027 23.2399C165.683 21.7679 163.971 21.0319 161.891 21.0319C159.779 21.0319 158.035 21.7839 156.659 23.2879C155.283 24.7919 154.595 26.6639 154.595 28.9039C154.595 31.1439 155.267 33.0159 156.611 34.5199C157.987 35.9919 159.715 36.7279 161.795 36.7279C163.907 36.7279 165.651 35.9759 167.027 34.4719C168.403 32.9359 169.091 31.0319 169.091 28.7599Z"
                                fill="black"/>
                            <path
                                d="M190.845 21.2719C189.853 21.2719 188.925 21.5119 188.061 21.9919C187.197 22.4719 186.189 23.3039 185.037 24.4879V40.9999H179.373V17.3359L184.989 16.3759V20.8879C186.237 19.2559 187.485 18.0719 188.733 17.3359C190.013 16.5999 191.421 16.2319 192.957 16.2319C194.685 16.2319 196.189 16.6959 197.469 17.6239C198.781 18.5199 199.725 19.7839 200.301 21.4159C201.645 19.5919 202.989 18.2799 204.333 17.4799C205.677 16.6479 207.181 16.2319 208.845 16.2319C211.309 16.2319 213.261 17.0799 214.701 18.7759C216.173 20.4719 216.909 22.7599 216.909 25.6399V40.9999H211.245V26.5039C211.245 24.8719 210.845 23.5919 210.045 22.6639C209.277 21.7359 208.189 21.2719 206.781 21.2719C205.789 21.2719 204.845 21.5279 203.949 22.0399C203.085 22.5199 202.077 23.3359 200.925 24.4879C200.957 24.6159 200.973 24.7599 200.973 24.9199C200.973 25.0799 200.973 25.3199 200.973 25.6399V40.9999H195.309V26.5039C195.309 24.8719 194.909 23.5919 194.109 22.6639C193.341 21.7359 192.253 21.2719 190.845 21.2719Z"
                                fill="black"/>
                            <path
                                d="M243.442 26.5999V40.4239L237.874 41.3839V37.3999C236.178 38.7439 234.562 39.7519 233.026 40.4239C231.522 41.0639 230.034 41.3839 228.562 41.3839C226.418 41.3839 224.69 40.7919 223.378 39.6079C222.098 38.4239 221.458 36.8399 221.458 34.8559C221.458 32.1359 222.802 30.1039 225.49 28.7599C228.21 27.4159 232.322 26.7119 237.826 26.6479C237.794 24.8559 237.33 23.5119 236.434 22.6159C235.57 21.6879 234.306 21.2239 232.642 21.2239C231.298 21.2239 229.906 21.5119 228.466 22.0879C227.058 22.6319 225.618 23.4479 224.146 24.5359L224.05 24.4879L225.058 18.7279C226.402 17.8959 227.762 17.2879 229.138 16.9039C230.546 16.4879 232.05 16.2799 233.65 16.2799C236.818 16.2799 239.234 17.1599 240.898 18.9199C242.594 20.6799 243.442 23.2399 243.442 26.5999ZM226.882 34.4239C226.882 35.2239 227.154 35.8639 227.698 36.3439C228.274 36.7919 229.074 37.0159 230.098 37.0159C231.058 37.0159 232.162 36.7919 233.41 36.3439C234.69 35.8959 236.162 35.2239 237.826 34.3279V29.8159C234.146 30.0719 231.394 30.5679 229.57 31.3039C227.778 32.0399 226.882 33.0799 226.882 34.4239Z"
                                fill="black"/>
                        </svg>

                        Your new favorite eyedropper tool!
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endforeach
