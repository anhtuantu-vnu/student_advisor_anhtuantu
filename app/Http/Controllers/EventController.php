<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\ResponseTrait;

use Illuminate\View\View;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EventMember;
use App\Models\Notification;

class EventController extends Controller
{
    use ResponseTrait;

    /**
     * @return View
     */
    public function showEventDetail($uuid): View
    {
        $thisEvent = Event::where('uuid', '=', $uuid)->with(['createdByUser', 'updatedByUser', 'eventMembers'])->first();
        $targetEventMembers = EventMember::where([
            ['event_id', '=', $uuid],
            ['user_id', '=', auth()->user()->uuid],
        ])->get();

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

        return view('front-end.layouts.event.detail', [
            'event' => $thisEvent,
            'targetEventMembers' => $targetEventMembers,
            'going' => $going,
            'interested' => $interested,
            'goingCount' => $goingCount,
            'interestedCount' => $interestedCount,
        ]);
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
            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }
            if (isset($request->page)) {
                $page = intval($request->page);
            }
            $skip = $limit * $page - $limit;

            $data = [
                'events' => Event::skip($skip)->take($limit)
                    ->with(['createdByUser', 'updatedByUser', 'eventMembers'])->get(),
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
}
