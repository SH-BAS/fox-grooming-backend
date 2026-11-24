<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $activeCount = User::where('is_active', true)->count();
        $inactiveCount = User::where('is_active', false)->count();
        $deletedCount = User::onlyTrashed()->count();

        $query = User::with('role')->withCount('bookings');

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } else {
            $status = 'active';
            $query->where('is_active', true);
        }

        $users = $query->latest()->paginate(8)->appends(['status' => $status]);

        return view('admin.users.index', compact(
            'users',
            'status',
            'activeCount',
            'inactiveCount',
            'deletedCount'
        ));
    }

    public function show(User $user)
    {
        $user->load(['role', 'pets.type', 'bookings.bookedServices.size']);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|max:50',
            'image' => 'nullable|image|max:2048',
            'password' => 'required|min:6',
        ]);

        $data = [
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ];

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('users', $imageName, 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|max:50',
            'image' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only(['role_id', 'name', 'email', 'phone']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('users', $imageName, 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index', ['status' => 'active'])
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $label = $user->is_active ? 'active' : 'inactive';

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $user->is_active,
                'message' => "User marked as {$label}.",
            ]);
        }

        return redirect()
            ->route('admin.users.index', ['status' => $user->is_active ? 'active' : 'inactive'])
            ->with('success', "User marked as {$label}.");
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        if (!$user->is_active) {
            $user->is_active = true;
            $user->save();
        }

        return redirect()
            ->route('admin.users.index', ['status' => 'active'])
            ->with('success', 'User restored successfully.');
    }
}
