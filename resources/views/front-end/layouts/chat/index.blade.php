@extends("front-end.layouts.index")
@section('content')
    <div class="main-content right-chat-active">

        <div class="middle-sidebar-bottom">
            <div class="middle-sidebar-left pe-0 ps-lg-3 ms-0 me-0" style="max-width: 100%;">
                <div class="row">


                    <div class="col-lg-12 position-relative">
                        <div class="chat-wrapper pt-0 w-100 position-relative scroll-bar bg-white theme-dark-bg">
                            <div class="chat-body p-3 ">
                                <div class="messages-content pb-5">
                                    @include('front-end.layouts.chat.receive', ['message' => "Hi there!"])
                                    @include('front-end.layouts.chat.broadcast', ['message' => "Hi there!"])
                                </div>
                            </div>
                        </div>
                        <div class="chat-bottom dark-bg p-3 shadow-none theme-dark-bg" style="width: 98%;">
                            <form class="chat-form" style="display: flex">
                                <div style="width: 90%; padding: 0 5px 0 0">
									<textarea rows="1"
                                              id="message" style="padding: 10px;border: 1px solid var(--theme-color);color: #000000; width: 100%"
                                              type="text"
                                              autocomplete="off"></textarea>
                                </div>
                                <button class="bg-current"><i class="ti-arrow-right text-white"></i></button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('script_page')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    {{--    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>--}}
    {{--    <script>--}}
    {{--		const beamsClient = new PusherPushNotifications.Client({--}}
    {{--			instanceId: '6c6ab1a2-5728-4c80-a4d9-a56e17f29e3c',--}}
    {{--		});--}}

    {{--		beamsClient.start()--}}
    {{--			.then(() => beamsClient.addDeviceInterest('hello'))--}}
    {{--			.then(() => console.log('Successfully registered and subscribed!'))--}}
    {{--			.catch(console.error);--}}
    {{--    </script>--}}
    <script>
		const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: '{{config('broadcasting.connections.pusher.cluster')}}'});
		const channel = pusher.subscribe('public');
		channel.bind('chat', function (data) {
			console.log(data)
			$.post("/receive", {
				_token: '{{csrf_token()}}',
				message: data.message,
			})
				.done(function (res) {
					$(".messages-content > .message-item").last().after(res);
					$(document).scrollTop($(document).height());
				});
		});

		//Broadcast messages
		$("form").submit(function (event) {
			event.preventDefault();

			$.ajax({
				url: "/broadcast",
				method: 'POST',
				headers: {
					'X-Socket-Id': pusher.connection.socket_id
				},
				data: {
					_token: '{{csrf_token()}}',
					message: $("form #message").val(),
				}
			}).done(function (res) {
				$(".messages-content > .message-item").last().after(res);
				$("form #message").val('');
				$(document).scrollTop($(document).height());
			});
		});
    </script>

@endsection