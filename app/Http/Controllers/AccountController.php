<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Exception;
use App\Services\AccountService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public AccountService $loginService;

    public function __construct(
        AccountService $loginService
    ) {
        $this->loginService = $loginService;
    }

    /**
     * @return View
     */
    public function showLogin(Request $request): View
    {
        $lang = $request->lang;
        if ($lang == null || $lang == '') {
            $lang = "en";
        }
        return view('front-end.layouts.login', ['lang' => $lang]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Redirect the use to the Google authentication page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information form Google
     * @return RedirectResponse
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $user = Socialite::driver('google')->user();
            $dataUser = [
                'first_name' => $user->user['given_name'],
                'last_name' => $user->user['family_name'],
                'role'      => 'student',
                'email'     => $user->email,
                'avatar'    => $user->user['picture'],
                'password'  => Hash::make($user->email)
            ];
            $this->loginService->createAccount($dataUser);
            return redirect()->route("app.home");
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * Register account by API
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $dataUser = $request->input();
            $dataUser['password'] = bcrypt($dataUser['password']);
            $this->loginService->createAccount($dataUser);
            return $this->successWithNoContent(__("messages.account.register_success"));
        } catch (Exception $e) {
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    /**
     * Update user language
     * @param Request $request
     */
    public function updateLang(Request $request)
    {
        try {
            $lang = $request->lang;
            $user = auth()->user();
            $user->lang = $lang;
            $user->save();
            return $this->successWithNoContent(__("messages.account.update_success"));
        } catch (Exception $e) {
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    /**
     * Login account by data login
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function login(Request $request): JsonResponse|RedirectResponse
    {
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                return redirect('/');
            }

            return redirect('/login')->withErrors(['error', __('messages.account.login_failed')])->withInput();
        } catch (Exception $e) {
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    /**
     * @return View
     */
    public function showProfile(): View
    {
        $thisUser = auth()->user()->load([
            'department',
            'classRoles' => function ($query) {
                return $query->with(['class_']);
            }
        ]);

        if ($thisUser->role == _CONST::STUDENT_ROLE && $thisUser->classRoles != null) {
            $class_ = $thisUser->classRoles->count() > 0 ? $thisUser->classRoles[0]->class_ : null;
        } else {
            $class_ = null;
        }

        $genderMap = User::GENDER_MAP;

        return view('front-end.layouts.user.profile', [
            'thisUser' => $thisUser,
            'class_' => $class_,
            'genderMap' => $genderMap
        ]);
    }

    /**
     * @return View
     */
    public function showUserDetail($uuid): View
    {
        $thisUser = User::where('uuid', '=', $uuid)->with([
            'department',
            'classRoles' => function ($query) {
                return $query->with(['class_']);
            }
        ])->first();

        if ($thisUser == null) {
            abort(404);
        }

        if ($thisUser->role == _CONST::STUDENT_ROLE && $thisUser->classRoles != null) {
            $class_ = $thisUser->classRoles->count() > 0 ? $thisUser->classRoles[0]->class_ : null;
        } else {
            $class_ = null;
        }

        $genderMap = User::GENDER_MAP;

        return view('front-end.layouts.user.detail', [
            'thisUser' => $thisUser,
            'class_' => $class_,
            'genderMap' => $genderMap
        ]);
    }

    public function updateAvatar(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('file')) {
                $basePath = config('aws_.avatar_images.path') . '/' . config('aws_.avatar_images.file_path');
                $uploadedFile = $request->file('file');

                $name = $request->name;
                if ($name == null || $name == '') {
                    $name = $uploadedFile->getClientOriginalName();
                }

                $fileType = explode('/', $uploadedFile->getClientMimeType())[0];
                if ($fileType != 'image') {
                    return $this->failedWithErrors(500, "only image files are supported");
                }

                $newFileName = Str::uuid() . '_' . $uploadedFile->getClientOriginalName();
                $orderPopPath = $basePath . '/' . $newFileName;
                Storage::disk('s3')->put($orderPopPath, file_get_contents($uploadedFile));

                $fullAvatarUrl = config('aws_.aws_url.url') . '/' . $orderPopPath;

                $thisUser = auth()->user();
                $oldAvatar = $thisUser->avatar;
                $thisUser->avatar = $fullAvatarUrl;
                $thisUser->save();
                DB::commit();

                // delete old avatar
                $oldAvatar = str_replace(config('aws_.aws_url.url') . '/', '', $oldAvatar);
                Storage::disk('s3')->delete($oldAvatar);

                return $this->successWithContent($fullAvatarUrl, __("messages.account.update_success"));
            } else {
                return $this->failedWithErrors(500, "no files specified");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Log::info('Error');

            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    public function updateAllowSearchByTeachersOnly(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $allowSearchByTeacherOnly = intval($request->allow_search_by_teacher_only) == 1 ? true : false;
            $user->allow_search_by_teacher_only = $allowSearchByTeacherOnly;
            $user->save();

            DB::commit();

            return $this->successWithContent([
                'message' => 'updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Log::info('Error');

            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
