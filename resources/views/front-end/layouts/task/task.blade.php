@foreach($dataTask as $task)
{{--    @dd($task)--}}
    <div
        class="p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3 target"
        data-bs-toggle="modal" data-bs-target="#ModelTask"
        draggable="true">
        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">{{ $task['name'] }}</h4>
        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">{{ $task['description'] }}</p>
{{--        <span--}}
{{--            class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
        <span
            class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block me-1"
            style="color: white; background-color: {{$backgroundTag}}"
        >{{ $type }}</span>
        <ul class="memberlist mt-4 mb-2 ms-0">
            <li><a href="#"><img src="{{ $task['user_assign']['avatar'] }}" alt="user"
                                 class="d-inline-block" style="border-radius: 50%; width: 24px; height: 24px"></a></li>
            <li class="ps-2 w-auto"><a href="#"
                                       class="fw-500 text-grey-500 font-xssss">{{ $task['user_assign']['first_name'] . $task['user_assign']['last_name'] }} assigned</a>
            </li>
        </ul>
    </div>
@endforeach
