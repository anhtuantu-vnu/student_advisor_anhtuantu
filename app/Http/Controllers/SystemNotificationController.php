<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SystemNotificationController extends Controller
{
    public function index()
    {
        $systemNotifications = SystemNotification::paginate(10);
        return view('front-end.layouts._admin.systemNotifications.index', [
            'systemNotifications' => $systemNotifications,
        ]);
    }

    public function create()
    {
        return view('front-end.layouts._admin.systemNotifications.create');
    }

    public function postCreate(Request $request)
    {
        try {
            DB::beginTransaction();

            $title = $request->title;
            $content = $request->content;
            $content_en = $request->content_en;

            $systemNotification = [
                'title' => $title,
                'content' => $content,
                'content_en' => $content_en,
                'created_by' => auth()->user()->uuid,
                'updated_by' => auth()->user()->uuid,
            ];

            SystemNotification::create($systemNotification);
            DB::commit();

            $request->session()->flash('success', 'Created system notification successfully');
            return redirect('/admin/notifications');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $request->session()->flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function update($id)
    {
        $systemNotification = SystemNotification::findOrFail($id);
        if ($systemNotification == null) {
            abort(404);
        }
        return view('front-end.layouts._admin.systemNotifications.update', [
            'systemNotification' => $systemNotification,
        ]);
    }

    public function postUpdate($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $systemNotification = SystemNotification::findOrFail($id);
            if ($systemNotification == null) {
                abort(404);
            }

            $title = $request->title;
            $content = $request->content;
            $content_en = $request->content_en;
            $active = $request->active;

            $systemNotificationData = [
                'title' => $title,
                'content' => $content,
                'content_en' => $content_en,
                'active' => $active == "active",
                'created_by' => auth()->user()->uuid,
                'updated_by' => auth()->user()->uuid,
            ];

            $systemNotification->fill($systemNotificationData);
            $systemNotification->save();
            DB::commit();

            $request->session()->flash('success', 'Updated system notification successfully');
            return redirect('/admin/notifications');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $request->session()->flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
