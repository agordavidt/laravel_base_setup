<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'agents' => User::role('agent')->count(),
                'users'  => User::role('user')->count(),
            ],
        ]);
    }
}