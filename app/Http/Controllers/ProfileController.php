<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdatePhotoRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('profiles.index', compact('user'));
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(UpdatePhotoRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Delete old photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Upload new photo
        $file = $request->file('photo');
        $filename = 'profile_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $photoPath = $file->storeAs('profiles', $filename, 'public');

        // Update user photo
        User::where('id', $user->id)->update(['photo' => $photoPath]);

        return redirect()->route('profiles.index')
            ->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        User::where('id', $user->id)->update(['photo' => null]);

        return redirect()->route('profiles.index')
            ->with('success', 'Profile photo deleted successfully!');
    }

    /**
     * Show the change password form.
     */
    public function showChangePassword(): View
    {
        return view('profiles.change-password');
    }

    /**
     * Update the user's password.
     *
     * This method ensures 100% verification accuracy by:
     * 1. Verifying the user is authenticated
     * 2. Strictly verifying the current password matches exactly
     * 3. Ensuring new password is different from current password
     * 4. Only updating password after all verifications pass
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Verify user is authenticated
        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'You must be logged in to change your password.']);
        }

        // Get fresh user data from database to ensure accuracy
        $freshUser = User::find($user->id);

        if (! $freshUser) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'User account not found.']);
        }

        $currentPasswordInput = $request->input('current_password');
        $newPassword = $request->input('password');

        // Strict verification: Current password must match exactly (plain text comparison)
        // This ensures 100% verification accuracy - no unauthorized password changes
        if ($currentPasswordInput !== $freshUser->password) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect. Please verify and try again.'])
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }

        // Additional check: New password must be different from current password
        if ($newPassword === $freshUser->password) {
            return back()
                ->withErrors(['password' => 'New password must be different from your current password.'])
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }

        // All verifications passed - update password (stored as plain text)
        User::where('id', $freshUser->id)->update([
            'password' => $newPassword,
        ]);

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        return redirect()->route('password.change')
            ->with('success', 'Your new password has been updated successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): View
    {
        $user = Auth::user();

        return view('profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validated();

        User::where('id', $user->id)->update($validated);

        return redirect()->route('profiles.edit')
            ->with('success', 'Your Update has been saved!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
