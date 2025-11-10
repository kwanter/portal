<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public front page (no authentication required)
Route::get("/", [PublicController::class, "index"])->name("home");

// Dashboard
Route::get("/dashboard", [DashboardController::class, "index"])
    ->middleware(["auth", "verified"])
    ->name("dashboard");

// Application routes - protected by auth middleware
Route::middleware("auth")->group(function () {
    // Profile routes
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    // Application CRUD routes
    Route::resource("applications", ApplicationController::class);
    Route::post("applications/{id}/restore", [
        ApplicationController::class,
        "restore",
    ])->name("applications.restore");

    // User management routes
    Route::resource("users", UserController::class);
    Route::post("users/{user}/verify", [UserController::class, "verify"])->name(
        "users.verify",
    );
    Route::post("users/{user}/unverify", [
        UserController::class,
        "unverify",
    ])->name("users.unverify");
});

require __DIR__ . "/auth.php";
