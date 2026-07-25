<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * All user-management actions require an administrator.
     * Users manage their own profile via ProfileController.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")->orWhere(
                    'email',
                    'like',
                    "%{$request->search}%",
                );
            });
        }

        $users = $query
            ->withCount('createdApplications')
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'user' => auth()->id(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['createdApplications', 'updatedApplications']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.
                $user->id.
                ',id',
        ]);

        // Check if password is being updated
        if ($request->filled('password')) {
            $request->validate([
                'password' => [
                    'required',
                    'confirmed',
                    Rules\Password::defaults(),
                ],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        try {
            $user->update($validated);

            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('User update failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            $user->delete();

            return redirect()
                ->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('User deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Verify the user's email address.
     */
    public function verify(User $user)
    {
        $this->authorize('verify', $user);

        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->back()
                ->with('info', 'User email is already verified.');
        }

        try {
            $user->markEmailAsVerified();

            return redirect()
                ->back()
                ->with('success', 'User email verified successfully.');
        } catch (\Exception $e) {
            Log::error('User verification failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to verify user. Please try again.');
        }
    }

    /**
     * Unverify the user's email address.
     */
    public function unverify(User $user)
    {
        $this->authorize('unverify', $user);

        if (! $user->hasVerifiedEmail()) {
            return redirect()
                ->back()
                ->with('info', 'User email is already unverified.');
        }

        // Prevent unverifying yourself
        if ($user->id === auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'You cannot unverify your own email address.');
        }

        try {
            $user->email_verified_at = null;
            $user->save();

            return redirect()
                ->back()
                ->with('success', 'User email unverified successfully.');
        } catch (\Exception $e) {
            Log::error('User unverification failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to unverify user. Please try again.');
        }
    }
}
