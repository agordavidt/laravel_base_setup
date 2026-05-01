<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $url = match (true) {
            $user->hasRole('super-admin') => route('super-admin.dashboard'),
            $user->hasRole('admin')       => route('admin.dashboard'),
            $user->hasRole('agent')       => route('agent.dashboard'),
            default                       => route('dashboard'),
        };

        return redirect()->intended($url);
    }
}