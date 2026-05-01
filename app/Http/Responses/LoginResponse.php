<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        return match (true) {
            $user->hasRole('super-admin') => redirect()->route('super-admin.dashboard'),
            $user->hasRole('admin')       => redirect()->route('admin.dashboard'),
            $user->hasRole('agent')       => redirect()->route('agent.dashboard'),
            default                       => redirect()->route('dashboard'),
        };
    }
}