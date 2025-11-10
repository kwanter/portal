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
        $query = Application::query();

        // Filter by category
        if (
            $request->has("category") &&
            in_array($request->category, ["kesekretariatan", "kepaniteraan"])
        ) {
            $query->category($request->category);
        }

        // Search functionality
        if ($request->has("search") && $request->search != "") {
            $query->search($request->search);
        }

        $applications = $query->latest()->paginate(12);

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

        return view("public.index", compact("applications", "stats"));
    }
}
