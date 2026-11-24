<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return $this->success(new UserResource($request->user()), 'User profile retrieved');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('users', 'public');
                $user->image = $path;
            }

            if ($request->filled('name'))
                $user->name = $request->name;
            if ($request->filled('phone'))
                $user->phone = $request->phone;
            if ($request->filled('password'))
                $user->password = Hash::make($request->password);

            $user->save();

            return $this->success(new UserResource($user), 'Profile updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->success([], 'Logged out successfully');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        $user->delete(); // Soft delete as trait is added

        return $this->success([], 'Account deleted successfully');
    }
}
