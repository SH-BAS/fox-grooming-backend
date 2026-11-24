<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with(['role', 'franchise'])->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::all();
        $franchises = Franchise::where('is_active', true)->get();
        return view('admin.admins.create', compact('roles', 'franchises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'franchise_id' => 'nullable|exists:franchises,id',
            'name' => 'required|max:100',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $data['is_active'] = $request->has('is_active');

        Admin::create($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $franchises = Franchise::where('is_active', true)->get();
        return view('admin.admins.edit', compact('admin', 'roles', 'franchises'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'franchise_id' => 'nullable|exists:franchises,id',
            'name' => 'required|max:100',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:6',
        ]);

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->has('is_active');

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully.');
    }
}
