<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePhotoRequest;
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
     */
    public function updatePassword(Request $request)
    {
        // Placeholder - functionality to be implemented later
        return redirect()->route('password.change')->with('success', 'Password updated successfully');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
