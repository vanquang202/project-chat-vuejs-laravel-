<?php

namespace App\Http\Controllers\Api\Google;

use App\Events\NotificationC;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function requestLogin(Request $request)
    {
        $provider = 'google';
        $token = (Socialite::driver($provider))->stateless()->getAccessTokenResponse($request->code);
        $user = Socialite::driver($provider)->userFromToken($token['access_token']);
        $id_social = 'google_id';
        $findId = User::where($id_social, $user->id)->first();
        unset($findId->password);
        if ($findId) {
            $this->loginAuthAccount($findId);
            return response()->json(['message' => 'Success login', 'payload' => $findId]);
        } else {
            if ($ac = User::where('email', $user->email)->first()) {
                $ac->$id_social = $user->id;
                $ac->save();
                $this->loginAuthAccount($ac);
            } else {
                $userLogin = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    $id_social => $user->id,
                    'password' => password_hash($user->id, PASSWORD_DEFAULT),
                ]);
                $this->loginAuthAccount($userLogin);
            };
            // Auth::login($userLogin);
            event(new NotificationC(auth()->id()));
            return response()->json(['message' => 'Success login', 'payload' => $userLogin]);
        }
    }

    public function loginAuthAccount($userLogin)
    {
        auth()->login($userLogin);
    }
}