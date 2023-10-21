<?php

namespace App\Facades;

use App\Models\ChMessage as Message;
use App\Models\ChFavorite as Favorite;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;
use Exception;
use Pusher\PusherException;

class ChatUserMessenger
{
    public Pusher $pusher;

    /**
     * Get max file's upload size in MB.
     *
     * @return float|int
     */
    public function getMaxUploadSize(): float|int
    {
        return config('chatify.attachments.max_upload_size') * 1048576;
    }

    /**
     * @throws PusherException
     */
    public function __construct()
    {
        $this->pusher = new Pusher(
            config('chatify.pusher.key'),
            config('chatify.pusher.secret'),
            config('chatify.pusher.app_id'),
            config('chatify.pusher.options'),
        );
    }

    /**
     * This method returns the allowed image extensions
     * to attach with the message.
     *
     * @return array
     */
    public function getAllowedImages()
    {
        return config('chatify.attachments.allowed_images');
    }

    /**
     * This method returns the allowed file extensions
     * to attach with the message.
     *
     * @return array
     */
    public function getAllowedFiles(): array
    {
        return config('chatify.attachments.allowed_files');
    }

    /**
     * Returns an array contains messenger's colors
     *
     * @return array
     */
    public function getMessengerColors(): array
    {
        return config('chatify.colors');
    }

    /**
     * Returns a fallback primary color.
     *
     * @return array|string
     */
    public function getFallbackColor(): array|string
    {
        $colors = $this->getMessengerColors();
        return count($colors) > 0 ? $colors[0] : '#000000';
    }

    /**
     * Trigger an event using Pusher
     *
     * @param string $channel
     * @param string $event
     * @param array $data
     * @return object
     */
    public function push(string $channel, string $event, array $data): object
    {
        return $this->pusher->trigger($channel, $event, $data);
    }

    /**
     * Authentication for pusher
     *
     * @param $requestUser
     * @param $authUser
     * @param string $channelName
     * @param string $socket_id
     */
    public function pusherAuth($requestUser, $authUser, string $channelName, string $socket_id)
    {
        $authData = json_encode([
            'user_id'   => $authUser->id,
            'user_info' => [
                'name' => $authUser->last_name . $authUser->first_name,
            ],
        ]);
        if (Auth::check()) {
            if ($requestUser->id == $authUser->id) {
                return $this->pusher->socket_auth(
                    $channelName,
                    $socket_id,
                    $authData
                );
            }
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json(['message' => 'Not authenticated'], 403);
    }

    /**
     * Fetch & parse message and return the message card
     * view as a response.
     *
     * @param Message|null $prefetchedMessage
     * @param int|null $id
     * @return array
     */
    public function parseMessage(Message $prefetchedMessage = null, int $id = null): array
    {
        $msg = null;
        $attachment = null;
        $attachment_type = null;
        $attachment_title = null;
        if (! ! $prefetchedMessage) {
            $msg = $prefetchedMessage;
        } else {
            $msg = Message::where('id', $id)->first();
            if (! $msg) {
                return [];
            }
        }
        if (isset($msg->attachment)) {
            $attachmentOBJ = json_decode($msg->attachment);
            $attachment = $attachmentOBJ->new_name;
            $attachment_title = htmlentities(trim($attachmentOBJ->old_name), ENT_QUOTES, 'UTF-8');
            $ext = pathinfo($attachment, PATHINFO_EXTENSION);
            $attachment_type = in_array($ext, $this->getAllowedImages()) ? 'image' : 'file';
        }
        return [
            'id'         => $msg->id,
            'from_id'    => $msg->from_id,
            'to_id'      => $msg->to_id,
            'message'    => $msg->body,
            'attachment' => (object)[
                'file'  => $attachment,
                'title' => $attachment_title,
                'type'  => $attachment_type,
            ],
            'timeAgo'    => $msg->created_at->diffForHumans(),
            'created_at' => $msg->created_at->toIso8601String(),
            'isSender'   => ($msg->from_id == Auth::user()->id),
            'seen'       => $msg->seen,
        ];
    }

    /**
     * Return a message card with the given data.
     *
     * @param $data
     * @param bool $renderDefaultCard
     * @return string
     */
    public function messageCard($data, bool $renderDefaultCard = false): string
    {
        if ($renderDefaultCard) {
            $data['isSender'] = false;
        }
        return view('chat.layouts.messageCard', $data)->render();
    }

    /**
     * Default fetch messages query between a Sender and Receiver.
     *
     * @param int $user_id
     * @return Message|Builder
     */
    public function fetchMessagesQuery($user_id): Builder|Message
    {
        return Message::where('from_id', Auth::user()->id)->where('to_id', $user_id)
            ->orWhere('from_id', $user_id)->where('to_id', Auth::user()->id);
    }

    /**
     * create a new message to database
     *
     * @param array $data
     * @return Message
     */
    public function newMessage($data): Message
    {
        $message = new Message();
        $message->from_id = $data['from_id'];
        $message->to_id = $data['to_id'];
        $message->body = $data['body'];
        $message->attachment = $data['attachment'];
        $message->save();
        return $message;
    }

    /**
     * Make messages between the sender [Auth user] and
     * the receiver [User id] as seen.
     *
     * @param int $user_id
     * @return bool|int
     */
    public function makeSeen($user_id): bool|int
    {
        Message::Where('from_id', $user_id)
            ->where('to_id', Auth::user()->id)
            ->where('seen', 0)
            ->update(['seen' => 1]);

        return 1;
    }

    /**
     * Get last message for a specific user
     *
     * @param int $user_id
     * @return Message|null
     */
    public function getLastMessageQuery($user_id): ?Message
    {
        return $this->fetchMessagesQuery($user_id)->latest()->first();
    }

    /**
     * Count Unseen messages
     *
     * @param int $user_id
     */
    public function countUnseenMessages($user_id)
    {
        return Message::where('from_id', $user_id)->where('to_id', Auth::user()->id)->where('seen', 0)->count();
    }

    /**
     * Get user list's item data [Contact Itme]
     * (e.g. User data, Last message, Unseen Counter...)
     *
     * @param Collection $user
     * @return string
     * @throws Exception
     */
    public function getContactItem($user): string
    {
        try {
            // get last message
            $lastMessage = $this->getLastMessageQuery($user->id);
            // Get Unseen messages counter
            $unseenCounter = $this->countUnseenMessages($user->id);
            if ($lastMessage) {
                $lastMessage->created_at = $lastMessage->created_at->toIso8601String();
                $lastMessage->timeAgo = $lastMessage->created_at->diffForHumans();
            }
            return view('chat.layouts.listItem', [
                'get'           => 'users',
                'user'          => $this->getUserWithAvatar($user),
                'lastMessage'   => $lastMessage,
                'unseenCounter' => $unseenCounter,
            ])->render();
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    /**
     * Get user with avatar (formatted).
     *
     */
    public function getUserWithAvatar($user)
    {
        if ($user->avatar == 'avatar.png' && config('chatify.gravatar.enabled')) {
            $imageSize = config('chatify.gravatar.image_size');
            $imageset = config('chatify.gravatar.imageset');
            $user->avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=' . $imageSize . '&d=' . $imageset;
        } else {
            $user->avatar = $user->avatar;
        }
        return $user;
    }

    /**
     * Check if a user in the favorite list
     *
     * @param int $user_id
     * @return boolean
     */
    public function inFavorite($user_id): bool
    {
        return Favorite::where('user_id', Auth::user()->id)
            ->where('favorite_id', $user_id)->count() > 0
            ? true : false;
    }

    /**
     * Make user in favorite list
     *
     * @param int $user_id
     * @param int $star
     * @return boolean
     */
    public function makeInFavorite($user_id, $action): bool
    {
        if ($action > 0) {
            // Star
            $star = new Favorite();
            $star->user_id = Auth::user()->id;
            $star->favorite_id = $user_id;
            $star->save();
            return $star ? true : false;
        } else {
            // UnStar
            $star = Favorite::where('user_id', Auth::user()->id)->where('favorite_id', $user_id)->delete();
            return $star ? true : false;
        }
    }

    /**
     * Get shared photos of the conversation
     *
     * @param int $user_id
     * @return array
     */
    public function getSharedPhotos($user_id): array
    {
        $images = []; // Default
        // Get messages
        $msgs = $this->fetchMessagesQuery($user_id)->orderBy('created_at', 'DESC');
        if ($msgs->count() > 0) {
            foreach ($msgs->get() as $msg) {
                // If message has attachment
                if ($msg->attachment) {
                    $attachment = json_decode($msg->attachment);
                    // determine the type of the attachment
                    in_array(pathinfo($attachment->new_name, PATHINFO_EXTENSION), $this->getAllowedImages())
                        ? array_push($images, $attachment->new_name) : '';
                }
            }
        }
        return $images;
    }

    /**
     * Delete Conversation
     *
     * @param int $user_id
     * @return int
     */
    public function deleteConversation($user_id): int
    {
        try {
            foreach ($this->fetchMessagesQuery($user_id)->get() as $msg) {
                // delete file attached if exist
                if (isset($msg->attachment)) {
                    $path = config('chatify.attachments.folder') . '/' . json_decode($msg->attachment)->new_name;
                    if (self::storage()->exists($path)) {
                        self::storage()->delete($path);
                    }
                }
                // delete from database
                $msg->delete();
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Delete message by ID
     *
     * @param int $id
     * @return bool|int
     * @throws Exception
     */
    public function deleteMessage(int $id): bool|int
    {
        try {
            $msg = Message::where('from_id', auth()->id())->where('id', $id)->firstOrFail();
            if (isset($msg->attachment)) {
                $path = config('chatify.attachments.folder') . '/' . json_decode($msg->attachment)->new_name;
                if (self::storage()->exists($path)) {
                    self::storage()->delete($path);
                }
            }
            $msg->delete();
            return 1;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Return a storage instance with disk name specified in the config.
     *
     */
    public function storage(): Filesystem
    {
        return Storage::disk(config('chatify.storage_disk_name'));
    }

    /**
     * Get user avatar url.
     *
     * @param string $user_avatar_name
     * @return string
     */
    public function getUserAvatarUrl($user_avatar_name): string
    {
        return self::storage()->url(config('chatify.user_avatar.folder') . '/' . $user_avatar_name);
    }

    /**
     * Get attachment's url.
     *
     * @param string $attachment_name
     * @return string
     */
    public function getAttachmentUrl($attachment_name): string
    {
        return '/storage/' . config('chatify.attachments.folder') . '/' . $attachment_name;
    }
}
