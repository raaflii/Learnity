<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $roleViews = [
            1 => 'admin.profile',
            2 => 'pengajar.profile',
            3 => 'siswa.profile',
        ];

        if (!isset($roleViews[$user->role_id])) {
            abort(403, 'Unauthorized');
        }

        return view($roleViews[$user->role_id]);
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        $roleViews = [
            1 => 'admin.components.edit-profile',
            2 => 'pengajar.components.edit-profile',
            3 => 'siswa.components.edit-profile',
        ];

        if (!isset($roleViews[$user->role_id])) {
            abort(403, 'Unauthorized');
        }

        return view($roleViews[$user->role_id], compact('user', 'profile'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'education' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:1000',
            'expertise' => 'nullable|string|max:1000',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarFile = $request->file('avatar');
            $avatarName = 'avatars/' . Str::uuid() . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->storeAs('', $avatarName, 'public');
            
            $user->update(['avatar' => $avatarName]);
        }
        
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $request->bio,
                'education' => $request->education,
                'experience' => $request->experience,
                'expertise' => $request->expertise,
                'social_links' => json_encode(array_filter($request->social_links ?? [])),
            ]
        );

        $user->update([
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}