<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Skin;
use App\Models\User;
use App\Services\ServerStatus;

class DashboardController extends Controller
{
    public function __invoke(ServerStatus $status)
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'linked' => User::whereNotNull('minecraft_name')->count(),
                'banned' => User::whereNotNull('banned_at')->count(),
                'pending_skins' => Skin::where('status', Skin::STATUS_PENDING)->count(),
                'news' => News::count(),
            ],
            'server' => $status->get(),
            'recentUsers' => User::latest()->limit(8)->get(),
        ]);
    }
}
