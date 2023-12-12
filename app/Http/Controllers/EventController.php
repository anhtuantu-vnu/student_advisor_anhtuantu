<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\View\View;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventMember;
use App\Models\Notification;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Mail\SendEventInvitation;

class EventController extends Controller
{
    /**
     * @return View
     */
    public function showEventDetail($uuid): View
    {
        try {
            $thisEvent = Event::where('uuid', '=', $uuid)
                ->with([
                    'createdByUser',
                    'updatedByUser',
                ])
                ->first();
            if ($thisEvent == null) {
                abort(404);
            }
            $targetEventMembers = EventMember::where([
                ['event_id', '=', $uuid],
                ['user_id', '=', auth()->user()->uuid],
            ])->with('user')->get();

            $going = false;
            $interested = false;
            $goingCount = 0;
            $interestedCount = 0;

            foreach ($targetEventMembers as $eventMember) {
                if ($eventMember->status == EventMember::STATUS_GOING) {
                    $going = true;
                }
                if ($eventMember->status == EventMember::STATUS_INTERESTED) {
                    $interested = true;
                }
            }
            foreach ($thisEvent->eventMembers as $eventMember) {
                if ($eventMember->status == EventMember::STATUS_GOING) {
                    $goingCount++;
                }
                if ($eventMember->status == EventMember::STATUS_INTERESTED) {
                    $interestedCount++;
                }
            }

            // get invitation
            $invitation = EventInvitation::where([
                ['event_id', '=', $thisEvent->uuid],
                ['origin_user', '=', $thisEvent->created_by],
                ['target_user', '=', auth()->user()->uuid],
            ])->first();

            // get all invitations
            $invitations = EventInvitation::where([
                ['event_id', '=', $thisEvent->uuid],
                ['origin_user', '=', $thisEvent->created_by],
            ])->with(['targetUserInfo'])->get();

            return view('front-end.layouts.event.detail', [
                'event' => $thisEvent,
                'targetEventMembers' => $targetEventMembers,
                'going' => $going,
                'interested' => $interested,
                'goingCount' => $goingCount,
                'interestedCount' => $interestedCount,
                'invitation' => $invitation,
                'invitations' => $invitations,
            ]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function getLatestEvents()
    {
        return view('front-end.layouts.event.lastest');
    }

    /**
     * @return View
     */
    public function updateEventDetail($uuid): View
    {
        $thisEvent = Event::where('uuid', '=', $uuid)->with(['createdByUser', 'updatedByUser', 'eventMembers'])->first();

        return view('front-end.layouts.event.update', [
            'event' => $thisEvent,
        ]);
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param Request $request
     */
    public function getEvents(Request $request)
    {
        try {
            $limit = 10;
            $page = 1;
            $type = Event::ALL_TYPES;
            $active = 1;

            if (isset($request->active)) {
                $active = intval($request->active);
            }

            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }
            if (isset($request->page)) {
                $page = intval($request->page);
            }
            if (isset($request->type)) {
                $type = explode(",", $request->type);
            }
            $skip = $limit * $page - $limit;

            $data = [
                'events' => Event::skip($skip)->take($limit)
                    ->whereIn('type', $type)
                    ->where('active', '=', $active)
                    ->with(['createdByUser', 'updatedByUser', 'eventMembers'])
                    ->orderBy('id', 'desc')
                    ->get(),
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param Request $request
     */
    public function createEvent(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $name = $request->event_name;
            $description = $request->event_description;
            $location = $request->event_location;
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $startHour = $request->start_hour;
            $startMinute = $request->start_minute;
            $endHour = $request->end_hour;
            $endMinute = $request->end_minute;
            $color = $request->color;
            $chosenDepartments = $request->chosen_departments;
            $eventFiles = [];
            $type = 'event';
            if (isset($request->type)) {
                $type = $request->type;
            }

            if ($request->hasFile('files')) {
                $basePath = config('aws_.event_images.path') . '/' . config('aws_.event_images.file_path');
                $files = $request->file('files');

                foreach ($files as $file) {
                    $fileType = explode('/', $file->getClientMimeType())[0];
                    if ($fileType == 'image') {
                        $newFileName = Str::uuid() . '_' . $file->getClientOriginalName();
                        $orderPopPath = $basePath . '/' . $newFileName;
                        Storage::disk('s3')->put($orderPopPath, file_get_contents($file));

                        array_push($eventFiles, [
                            'url' => $newFileName,
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                        ]);
                    }
                }
            }

            $event = [
                'uuid' => Str::uuid(),
                'name' => $name,
                'description' => $description,
                'location' => $location,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_hour' => $startHour,
                'start_minute' => $startMinute,
                'end_hour' => $endHour,
                'end_minute' => $endMinute,
                'color' => $color,
                'files' => json_encode($eventFiles),
                'tags' => $chosenDepartments,
                'created_by' => $user->uuid,
                'updated_by' => $user->uuid,
                'type' => $type,
            ];
            $insertedEvent = Event::create($event);
            DB::commit();

            $data = [
                'event' => Event::with(['createdByUser', 'updatedByUser', 'eventMembers'])->find($insertedEvent->id),
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param Request $request
     */
    public function removeEventImages(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $thisEvent = Event::findOrFail($id);

            if ($thisEvent->created_by != $user->uuid) {
                return $this->failedWithErrors(400, 'this is not your event');
            }

            // delete old files
            foreach (json_decode($thisEvent->files, true) as $file) {
                Storage::disk('s3')->delete($file["url"]);
            }

            $event = [
                "files" => json_encode([]),
            ];

            $thisEvent->fill($event);
            $thisEvent->save();
            DB::commit();

            $data = [
                'event' => Event::with(['createdByUser', 'updatedByUser', 'eventMembers'])->find($id),
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param Request $request
     */
    public function updateEvent(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $thisEvent = Event::findOrFail($id);

            if ($thisEvent->created_by != $user->uuid) {
                return $this->failedWithErrors(400, 'this is not your event');
            }

            $name = $request->event_name;
            $description = $request->event_description;
            $location = $request->event_location;
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $startHour = $request->start_hour;
            $startMinute = $request->start_minute;
            $endHour = $request->end_hour;
            $endMinute = $request->end_minute;
            $color = $request->color;
            $chosenDepartments = $request->chosen_departments;
            $eventFiles = [];

            if ($request->hasFile('files')) {
                $basePath = config('aws_.event_images.path') . '/' . config('aws_.event_images.file_path');
                $files = $request->file('files');

                foreach ($files as $file) {
                    $fileType = explode('/', $file->getClientMimeType())[0];
                    if ($fileType == 'image') {
                        $newFileName = Str::uuid() . '_' . $file->getClientOriginalName();
                        $orderPopPath = $basePath . '/' . $newFileName;
                        Storage::disk('s3')->put($orderPopPath, file_get_contents($file));

                        array_push($eventFiles, [
                            'url' => $newFileName,
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                        ]);
                    }
                }

                // delete old files
                foreach (json_decode($thisEvent->files, true) as $file) {
                    Storage::disk('s3')->delete($file["url"]);
                }
            }

            $event = [
                'name' => $name,
                'description' => $description,
                'location' => $location,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_hour' => $startHour,
                'start_minute' => $startMinute,
                'end_hour' => $endHour,
                'end_minute' => $endMinute,
                'color' => $color,
                'tags' => $chosenDepartments,
                'updated_by' => $user->uuid,
            ];
            if (count($eventFiles) > 0) {
                $event['files'] = json_encode($eventFiles);
            }

            $thisEvent->fill($event);
            $thisEvent->save();
            DB::commit();

            $data = [
                'event' => Event::with(['createdByUser', 'updatedByUser', 'eventMembers'])->find($id),
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param int $id
     */
    public function goingToEvent($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $thisEvent = Event::findOrFail($id);

            if ($thisEvent == null) {
                return $this->failedWithErrors(404, 'Event not found');
            }

            $findExistedGoingEventMember = EventMember::where([
                ['user_id', '=', $user->uuid],
                ['event_id', '=', $thisEvent->uuid],
                ['status', '=', EventMember::STATUS_GOING],
            ])->first();

            if ($findExistedGoingEventMember == null) {
                $newEventMember = [
                    'user_id' => $user->uuid,
                    'event_id' => $thisEvent->uuid,
                    'status' => EventMember::STATUS_GOING,
                    'uuid' => Str::uuid(),
                ];
                $insertedEventMember = EventMember::create($newEventMember);

                $data = [
                    'status' => EventMember::STATUS_GOING,
                    'eventMember' => $insertedEventMember,
                ];

                // update invitation if any
                $findInvitation = EventInvitation::where([
                    ['event_id', '=', $thisEvent->uuid],
                    ['origin_user', '=', $thisEvent->created_by],
                    ['target_user', '=', $user->uuid],
                    ['status', '=', EventInvitation::STATUS_NO_RESPONSE],
                ])->first();

                if ($findInvitation != null) {
                    $findInvitation->status = EventInvitation::STATUS_GOING;
                    $findInvitation->save();

                    // create going to event notification
                    if ($thisEvent->created_by != $user->uuid) {
                        $notiData = [
                            'target_url' => '/events' . '/' . $thisEvent->uuid,
                            'origin_user' => $user->uuid,
                            'target_user' => $thisEvent->created_by,
                            'event_id' => $thisEvent->uuid,
                            'type' => Notification::EVENT_TYPES['RESPONDED_TO_EVENT_GOING'],
                        ];
                        Notification::create($notiData);
                    }
                } else {
                    // create going to event notification
                    if ($thisEvent->created_by != $user->uuid) {
                        $notiData = [
                            'target_url' => '/events' . '/' . $thisEvent->uuid,
                            'origin_user' => $user->uuid,
                            'target_user' => $thisEvent->created_by,
                            'event_id' => $thisEvent->uuid,
                            'type' => Notification::EVENT_TYPES['GOING_TO_EVENT'],
                        ];
                        Notification::create($notiData);
                    }
                }
            } else {
                $findExistedGoingEventMember->delete();
                $data = [
                    'status' => 'undo ' . EventMember::STATUS_GOING,
                    'eventMember' => null,
                ];
            }
            DB::commit();

            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    public function rejectEventInvitation($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $thisEvent = Event::findOrFail($id);

            if ($thisEvent == null) {
                return $this->failedWithErrors(404, 'Event not found');
            }

            $findInvitation = EventInvitation::where([
                ['event_id', '=', $thisEvent->uuid],
                ['origin_user', '=', $thisEvent->created_by],
                ['target_user', '=', $user->uuid],
                ['status', '=', EventInvitation::STATUS_NO_RESPONSE],
            ])->first();

            if ($findInvitation == null) {
                return $this->failedWithErrors(404, 'Invitation not found');
            }

            $findInvitation->status = EventInvitation::STATUS_REJECT;
            $findInvitation->save();

            $findExistedGoingEventMember = EventMember::where([
                ['user_id', '=', $user->uuid],
                ['event_id', '=', $thisEvent->uuid],
                ['status', '=', EventMember::STATUS_GOING],
            ])->first();

            if ($findExistedGoingEventMember != null) {
                $findExistedGoingEventMember->delete();
            }

            $notiData = [
                'target_url' => '/events' . '/' . $thisEvent->uuid,
                'origin_user' => $user->uuid,
                'target_user' => $thisEvent->created_by,
                'event_id' => $thisEvent->uuid,
                'type' => Notification::EVENT_TYPES['RESPONDED_TO_EVENT_REJECTED'],
            ];
            Notification::create($notiData);

            DB::commit();

            $data = [
                'status' => 'reject event invitation',
            ];

            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param int $id
     */
    public function interestedInEvent($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $thisEvent = Event::findOrFail($id);

            if ($thisEvent == null) {
                return $this->failedWithErrors(404, 'Event not found');
            }

            $findExistedInterestedEventMember = EventMember::where([
                ['user_id', '=', $user->uuid],
                ['event_id', '=', $thisEvent->uuid],
                ['status', '=', EventMember::STATUS_INTERESTED],
            ])->first();

            if ($findExistedInterestedEventMember == null) {
                $newEventMember = [
                    'user_id' => $user->uuid,
                    'event_id' => $thisEvent->uuid,
                    'status' => EventMember::STATUS_INTERESTED,
                    'uuid' => Str::uuid(),
                ];
                $insertedEventMember = EventMember::create($newEventMember);

                $data = [
                    'status' => EventMember::STATUS_INTERESTED,
                    'eventMember' => $insertedEventMember,
                ];

                // create going to event notification
                if ($thisEvent->created_by != $user->uuid) {
                    $notiData = [
                        'target_url' => '/events' . '/' . $thisEvent->uuid,
                        'origin_user' => $user->uuid,
                        'target_user' => $thisEvent->created_by,
                        'event_id' => $thisEvent->uuid,
                        'type' => Notification::EVENT_TYPES['INTERESTED_IN_EVENT'],
                    ];
                    Notification::create($notiData);
                }
            } else {
                $findExistedInterestedEventMember->delete();
                $data = [
                    'status' => 'undo ' . EventMember::STATUS_INTERESTED,
                    'eventMember' => null,
                ];
            }
            DB::commit();

            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    public function inviteToEvent(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $thisEvent = Event::with(['createdByUser'])
                ->findOrFail($id);

            if ($thisEvent == null) {
                return $this->failedWithErrors(404, 'Event not found');
            }

            $userIds = explode(",", $request->userIds);
            $userEmails = explode(",", $request->userEmails);
            $userNames = explode(",", $request->userNames);
            $eventUrl = $request->eventUrl;
            $usersToInvite = [];
            $eventInvitations = [];

            foreach ($userIds as $userId) {
                $findInvitation = EventInvitation::where([
                    ['event_id', '=', $thisEvent->uuid],
                    ['origin_user', '=', $thisEvent->created_by],
                    ['target_user', '=', $user->uuid],
                    ['status', '=', EventInvitation::STATUS_NO_RESPONSE],
                ])->first();

                if ($findInvitation == null) {
                    array_push($usersToInvite, $userId);
                }
            }

            foreach ($usersToInvite as $userId) {
                array_push($eventInvitations, [
                    'event_id' => $thisEvent->uuid,
                    'origin_user' => $thisEvent->created_by,
                    'target_user' => $userId,
                    'status' => EventInvitation::STATUS_NO_RESPONSE,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            if (count($eventInvitations) > 0) {
                EventInvitation::insert($eventInvitations);
            }

            // create event invitation notifications
            $notificationsToCreate = [];
            foreach ($usersToInvite as $user) {
                array_push($notificationsToCreate, [
                    'target_url' => '/events' . '/' . $thisEvent->uuid,
                    'origin_user' => $thisEvent->created_by,
                    'target_user' => $user,
                    'event_id' => $thisEvent->uuid,
                    'type' => Notification::EVENT_TYPES['INVITED_TO_EVENT'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            if (count($notificationsToCreate) > 0) {
                foreach ($notificationsToCreate as $noti) {
                    Notification::create($noti);
                }
                // Notification::insert($notificationsToCreate);
            }

            DB::commit();

            $titleMap = [
                1 => 'Mr',
                2 => 'Mrs',
                3 => 'Mr',
            ];

            foreach ($userEmails as $index => $userEmail) {
                Mail::to($userEmail)->send(new SendEventInvitation(
                    'Invitation to event ' . $thisEvent->name,
                    $thisEvent,
                    $thisEvent->createdByUser->last_name . ' ' . $thisEvent->createdByUser->first_name,
                    $titleMap[$thisEvent->createdByUser->gender],
                    $userNames[$index],
                    $eventUrl
                ));
            }

            $data = [
                'message' => 'send event invitations successfully',
            ];

            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    public function cancelEvent($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $thisEvent = Event::findOrFail($id);

            if ($thisEvent == null) {
                return $this->failedWithErrors(404, 'Event not found');
            }

            $thisEvent->active = 0;
            $thisEvent->save();

            $eventMembers = EventMember::where([
                ['event_id', '=', $thisEvent->uuid],
                ['user_id', '!=', $thisEvent->created_by],
                ['status', '=', EventMember::STATUS_GOING],
            ])->get();

            $notisToCreate = [];
            foreach ($eventMembers as $member) {
                $notiData = [
                    'target_url' => '/events' . '/' . $thisEvent->uuid,
                    'origin_user' => $thisEvent->created_by,
                    'target_user' => $member->user_id,
                    'event_id' => $thisEvent->uuid,
                    'type' => Notification::EVENT_TYPES['CANCEL_EVENT'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                array_push($notisToCreate, $notiData);
            }

            if (count($notisToCreate) > 0) {
                Notification::insert($notisToCreate);
            }

            DB::commit();

            $data = [
                'event' => $thisEvent,
                'message' => 'canceled event successfully',
            ];

            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }
}
