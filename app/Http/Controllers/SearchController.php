<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\User;

class SearchController extends Controller
{
    public function showSearch()
    {
        return view('front-end.layouts.search.result');
    }

    public function searchUsers(Request $request)
    {
        try {
            $currentRole = auth()->user()->role;

            $limit = 10;
            $page = 1;
            $search = $request->search;

            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }
            if (isset($request->page)) {
                $page = intval($request->page);
            }
            $skip = $limit * $page - $limit;

            if ($currentRole != _CONST::STUDENT_ROLE) {
                $foundUsers = User::skip($skip)->take($limit)
                    ->where('role', '!=', _CONST::ADMIN_ROLE)
                    ->where(function ($query) use ($search) {
                        $query->where('last_name', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })->with(['department', 'classRoles' => function ($query) {
                        return $query->with('class_');
                    }])
                    ->orderBy('id', 'desc')
                    ->orderBy('role', 'desc')
                    ->get();
            } else {
                $foundUsers = User::skip($skip)->take($limit)
                    ->where('role', '!=', _CONST::ADMIN_ROLE)
                    ->where('allow_search_by_teacher_only', '=', false)
                    ->where(function ($query) use ($search) {
                        $query->where('last_name', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })->with(['department', 'classRoles' => function ($query) {
                        return $query->with('class_');
                    }])
                    ->orderBy('id', 'desc')
                    ->orderBy('role', 'desc')
                    ->get();
            }
            $data = [
                'foundUsers' => $foundUsers,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }

    public function searchEvents(Request $request)
    {
        try {
            $limit = 10;
            $page = 1;
            $search = $request->search;

            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }
            if (isset($request->page)) {
                $page = intval($request->page);
            }
            $skip = $limit * $page - $limit;

            $foundEvents = Event::skip($skip)
                ->take($limit)
                ->where('name', 'like', '%' . $search . '%')
                ->with(['createdByUser', 'updatedByUser', 'eventMembers'])
                ->orderBy('id', 'desc')
                ->get();
            $data = [
                'foundEvents' => $foundEvents,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }
}
