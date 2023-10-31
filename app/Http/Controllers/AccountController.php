<?php

namespace App\Http\Controllers;

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
    public function showLogin(): View
    {
        return view('front-end.layouts.login');
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
}
