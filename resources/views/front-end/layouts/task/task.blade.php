@foreach($dataTask as $task)
    <div
        class="p-3 bg-lightblue cart_task theme-dark-bg mt-0 mb-3 ms-3 me-3 rounded-3 target"
        data-bs-toggle="modal" data-bs-target="#ModelTask"
        draggable="true">
        <h4 class="font-xsss fw-700 text-grey-900 mb-2 mt-1 d-block">{{ $task['name'] }}</h4>
        <p class="font-xssss lh-24 fw-500 text-grey-500 mt-3 d-block mb-3">{{ $task['description'] }}</p>
{{--        <span--}}
{{--            class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-info d-inline-block text-info">30 Min</span>--}}
{{--        <span--}}
{{--            class="font-xsssss fw-700 ps-3 pe-3 lh-32 text-uppercase rounded-3 ls-2 alert-success d-inline-block text-success me-1">Design</span>--}}
        <ul class="memberlist mt-4 mb-2 ms-0">
            <li><a href="#"><img src="{{ asset("images/user-6.png") }}" alt="user"
                                 class="w30 d-inline-block"></a></li>
            <li><a href="#"><img src="{{ asset("images/user-7.png") }}" alt="user"
                                 class="w30 d-inline-block"></a></li>
            <li><a href="#"><img src="{{ asset("images/user-8.png") }}" alt="user"
                                 class="w30 d-inline-block"></a></li>
            <li><a href="#"><img src="{{ asset("images/user-3.png") }}" alt="user"
                                 class="w30 d-inline-block"></a></li>
            <li class="last-member"><a href="#"
                                       class="bg-white fw-600 text-grey-500 font-xssss ls-3 text-center">+2</a>
            </li>
            <li class="ps-4 w-auto"><a href="#"
                                       class="fw-500 text-grey-500 font-xssss">Member</a>
            </li>
        </ul>
    </div>
@endforeach
