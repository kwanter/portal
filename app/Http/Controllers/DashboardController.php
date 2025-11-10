<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $stats = [
            "total_applications" => Application::count(),
            "kesekretariatan_apps" => Application::where(
                "category",
                "kesekretariatan",
            )->count(),
            "kepaniteraan_apps" => Application::where(
                "category",
                "kepaniteraan",
            )->count(),
            "total_users" => User::count(),
        ];

        $recentApplications = Application::with("creator")
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view(
            "dashboard",
            compact("stats", "recentApplications", "recentUsers"),
        );
    }
}
