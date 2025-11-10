<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the public applications page.
     */
    public function index(Request $request)
    {
        // Search functionality
        if ($request->has("search") && $request->search != "") {
            $query = Application::query();
            $query->search($request->search);
            $applications = $query->latest()->get();
        } else {
            // Get all applications when not searching
            $applications = Application::latest()->get();
        }

        $stats = [
            "total" => Application::count(),
            "kesekretariatan" => Application::where(
                "category",
                "kesekretariatan",
            )->count(),
            "kepaniteraan" => Application::where(
                "category",
                "kepaniteraan",
            )->count(),
        ];

        // Debug logging
        \Log::info("PublicController applications", [
            "total_fetched" => $applications->count(),
            "kesekretariatan_count" => $applications
                ->where("category", "kesekretariatan")
                ->count(),
            "kepaniteraan_count" => $applications
                ->where("category", "kepaniteraan")
                ->count(),
            "kepaniteraan_apps" => $applications
                ->where("category", "kepaniteraan")
                ->pluck("name"),
        ]);

        return view("public.index", compact("applications", "stats"));
    }
}
