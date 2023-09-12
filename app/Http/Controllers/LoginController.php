<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Services\LoginService;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public LoginService $loginService;

    public function __construct(
        LoginService $loginService
    )
    {
        $this->loginService = $loginService;
    }

    /**
     * Redirect the use to the Google authentication page
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information form Google
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            //$user = Socialite=> => driver('google')->user();
            $user = ["id"=>  "110941361666850811275",
                      "nickname"=>  null,
                      "name"=>  "Nguyễn Quốc Nam",
                      "email"=>  "namnq@omegatheme.com",
                      "avatar"=>  "https=> //lh3.googleusercontent.com/a/ACg8ocJZdrsRL2gEJA6tVQbcwAYM1z86rMbBFuLZNRAa9wd8=s96-c",
                      "user"=>  [
                                    "sub"=>  "110941361666850811275",
                        "name"=>  "Nguyễn Quốc Nam",
                        "given_name"=>  "Nguyễn",
                        "family_name"=>  "Quốc Nam",
                        "picture"=>  "https=> //lh3.googleusercontent.com/a/ACg8ocJZdrsRL2gEJA6tVQbcwAYM1z86rMbBFuLZNRAa9wd8=s96-c",
                        "email"=>  "namnq@omegatheme.com",
                        "email_verified"=>  true,
                        "locale"=>  "en",
                        "hd"=>  "omegatheme.com",
                        "id"=>  "110941361666850811275",
                        "verified_email"=>  true,
                        "link"=>  null
                      ],
                      "attributes"=>  [
                                    "id"=>  "110941361666850811275",
                        "nickname"=>  null,
                        "name"=>  "Nguyễn Quốc Nam",
                        "email"=>  "namnq@omegatheme.com",
                        "avatar"=>  "https=> //lh3.googleusercontent.com/a/ACg8ocJZdrsRL2gEJA6tVQbcwAYM1z86rMbBFuLZNRAa9wd8=s96-c",
                        "avatar_original"=>  "https=> //lh3.googleusercontent.com/a/ACg8ocJZdrsRL2gEJA6tVQbcwAYM1z86rMbBFuLZNRAa9wd8=s96-c"
                      ],
                      "token"=>  "ya29.a0AfB_byAOJuegh3Yv4VpK-Gdo7iHGwP8iBn5f5EQ6nr8h53hn3OTf-APdQ76GK6TvNKSGw3zeBZwj0ElpCLGfnevnpLKO-ULxsfqVnRWJlEZcquyTHuSEZXXBcxb1-tncICBTpkH_2WN8SouRQJZETxGU5nHwRAfeYwaCgYKAXESARISFQGOcNnCmCGEkEh35tRROnARbkd91w0169",
                      "refreshToken"=>  null,
                      "expiresIn"=>  3599,
                      "approvedScopes"=>  [
                                    "openid",
                                    "https=> //www.googleapis.com/auth/userinfo.email",
                                    "https=> //www.googleapis.com/auth/userinfo.profile"
                                ]];
            $dataUser = [
                'first_name' => $user['user']['given_name'],
                'last_name' => $user['user']['family_name'],
                'role'      => 'student',
                'email'     => $user['email'],
                'password'  => Str::uuid()
            ];
            $this->loginService->createAccount($dataUser);
            dd($user);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
