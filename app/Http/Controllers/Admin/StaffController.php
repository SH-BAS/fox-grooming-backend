<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $activeCount = Staff::where('is_active', true)->count();
        $inactiveCount = Staff::where('is_active', false)->count();
        $deletedCount = Staff::onlyTrashed()->count();

        $query = Staff::with('role')
            ->withCount([
                'bookings as total_bookings_count',
                'bookings as completed_bookings_count' => function ($q) {
                    $q->where('status', 'completed');
                },
            ]);

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } else {
            $status = 'active';
            $query->where('is_active', true);
        }

        $staffMembers = $query->latest()->paginate(8)->appends(['status' => $status]);

        return view('admin.staff.index', compact(
            'staffMembers',
            'status',
            'activeCount',
            'inactiveCount',
            'deletedCount'
        ));
    }

    public function show($id)
    {
        $staff = Staff::with(['role', 'bookings.user', 'reviews.user'])
            ->withCount([
                'bookings as total_bookings_count',
                'bookings as completed_bookings_count' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->findOrFail($id);

        return view('admin.staff.show', compact('staff'));
    }

    public function create()
    {
        $staffRole = Role::where('slug', 'staff')->first();
        return view('admin.staff.create', compact('staffRole'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|max:100',
            'email' => 'required|email|unique:staff,email',
            'image' => 'nullable|image|max:2048',
            'password' => 'required|min:6',
        ]);

        $staffRole = Role::where('slug', 'staff')->first();

        $data = $request->except(['image']);
        $data['role_id'] = $staffRole->id;
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('staff', $imageName, 'public');
        }

        Staff::create($data);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function edit(Staff $staff)
    {
        $staffRole = Role::where('slug', 'staff')->first();
        return view('admin.staff.edit', compact('staff', 'staffRole'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'image' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $staffRole = Role::where('slug', 'staff')->first();

        $data = $request->except(['image']);
        $data['role_id'] = $staffRole->id;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('staff', $imageName, 'public');
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()
            ->route('admin.staff.index', ['status' => 'active'])
            ->with('success', 'Staff member deleted successfully.');
    }

    public function toggleStatus(Staff $staff)
    {
        $staff->is_active = !$staff->is_active;
        $staff->save();

        $label = $staff->is_active ? 'active' : 'inactive';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $staff->is_active,
                'message' => "Staff marked as {$label}.",
            ]);
        }

        return redirect()
            ->route('admin.staff.index', ['status' => $staff->is_active ? 'active' : 'inactive'])
            ->with('success', "Staff marked as {$label}.");
    }

    public function restore($id)
    {
        $staff = Staff::onlyTrashed()->findOrFail($id);
        $staff->restore();

        if (!$staff->is_active) {
            $staff->is_active = true;
            $staff->save();
        }

        return redirect()
            ->route('admin.staff.index', ['status' => 'active'])
            ->with('success', 'Staff restored successfully.');
    }
}
