<script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script >
    const userID = {{ auth()->id() }}
    window.messageChat = {
        name: "{{ config('chatify.name') }}",
        sounds: {!! json_encode(config('chatify.sounds')) !!},
        allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
        allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
        maxUploadSize: {{ \App\Facades\ChatMessage::getMaxUploadSize() }},
        pusher: {!! json_encode(config('chatify.pusher')) !!},
        pusherAuthEndpoint: '{{route("pusher.auth")}}',
        url: '{{url('')}}',
    };
    window.messageChat.allAllowedExtensions = messageChat.allowedImages.concat(messageChat.allowedFiles);
</script>
<script src="{{ asset('assets/js/utils.js') }}"></script>
<script src="{{ asset('assets/js/code.js') }}"></script>
<script>
	const beamsClient = new PusherPushNotifications.Client({
		instanceId: '6c6ab1a2-5728-4c80-a4d9-a56e17f29e3c',
	});

	beamsClient.start()
		.then(() => beamsClient.addDeviceInterest(`user-${userID}`))
		.catch(console.error);
</script>
