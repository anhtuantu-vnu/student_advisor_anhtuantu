<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Exception;
use App\Services\AccountService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    use ResponseTrait;

    public AccountService $loginService;

    public function __construct(
        AccountService $loginService
    )
    {
        $this->loginService = $loginService;
    }

    /**
     * Redirect the use to the Google authentication page
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle(): \Illuminate\Http\Response
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information form Google
     * @return void
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
                'password'  => Str::uuid()
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
            $dataUser['password'] = Hash::make($dataUser['password']);
            $this->loginService->createAccount($dataUser);
            return $this->successWithNoContent(__("messages.account.register_success"));
        } catch (Exception $e) {
            return $this->failedWithErrors(500, $e->getMessage());
        }

    }

    /**
     * Login account by data login
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $resultLogin = $this->loginService->loginAccount($email, $password);
            if($resultLogin) {
                return $this->successWithContent($resultLogin, __('messages.account.login_success'));
            }
            return $this->failedWithErrors(500, __('messages.account.login_failed'));
        } catch (Exception $e) {
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
