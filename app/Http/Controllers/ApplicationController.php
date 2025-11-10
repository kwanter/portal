<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
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

        $applications = $query
            ->with(["creator", "updater"])
            ->latest()
            ->paginate(15);

        return view("applications.index", compact("applications"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("applications.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255|unique:applications,name",
            "url" => "required|url|max:500",
            "description" => "required|string|max:2000",
            "category" => "required|in:kesekretariatan,kepaniteraan",
        ]);

        DB::beginTransaction();
        try {
            Application::create($validated);
            DB::commit();

            return redirect()
                ->route("applications.index")
                ->with("success", "Application created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "error",
                    "Failed to create application: " . $e->getMessage(),
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        $application->load(["creator", "updater", "deleter", "audits.user"]);

        return view("applications.show", compact("application"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        return view("applications.edit", compact("application"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "url" => "required|url|max:500",
            "description" => "required|string|max:2000",
            "category" => "required|in:kesekretariatan,kepaniteraan",
        ]);

        // Check unique name (excluding current application)
        if ($request->name !== $application->name) {
            $exists = Application::where("name", $request->name)
                ->where("id", "!=", $application->id)
                ->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        "name" =>
                            "The application name has already been taken.",
                    ]);
            }
        }

        try {
            $application->name = $validated["name"];
            $application->url = $validated["url"];
            $application->description = $validated["description"];
            $application->category = $validated["category"];
            $application->updated_by = auth()->id();
            $application->save();

            return redirect()
                ->route("applications.index")
                ->with("success", "Application updated successfully.");
        } catch (\Exception $e) {
            \Log::error("Application update failed", [
                "application_id" => $application->id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "error",
                    "Failed to update application: " . $e->getMessage(),
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        DB::beginTransaction();
        try {
            $application->delete();
            DB::commit();

            return redirect()
                ->route("applications.index")
                ->with("success", "Application deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(
                    "error",
                    "Failed to delete application: " . $e->getMessage(),
                );
        }
    }

    /**
     * Restore a soft-deleted application.
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $application = Application::withTrashed()->findOrFail($id);
            $application->restore();
            DB::commit();

            return redirect()
                ->route("applications.index")
                ->with("success", "Application restored successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with(
                    "error",
                    "Failed to restore application: " . $e->getMessage(),
                );
        }
    }
}
