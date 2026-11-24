<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $staff = auth()->guard('staff')->user();
        return view('staff.profile.edit', compact('staff'));
    }

    public function update(Request $request)
    {
        $staff = auth()->guard('staff')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('staff', $imageName, 'public');
        }

        $staff->update($data);

        return redirect()->route('staff.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function password()
    {
        return view('staff.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $staff = auth()->guard('staff')->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, $staff->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $staff->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('staff.profile.password')->with('success', 'Password changed successfully.');
    }
}
