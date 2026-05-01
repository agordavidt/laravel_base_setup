<?php

namespace App\Http\Controllers\SuperAdmin; 

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Use validated role queries — Spatie scopes, not raw where()
        return view('super-admin.dashboard', [
            'stats' => [
                'total_users' => User::count(),
                'admins'      => User::role('admin')->count(),
                'agents'      => User::role('agent')->count(),
                'users'       => User::role('user')->count(),
            ],
        ]);
    }
}