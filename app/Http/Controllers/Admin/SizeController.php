<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:sizes,name',
            'weight_from' => 'nullable|numeric',
            'weight_to' => 'nullable|numeric',
        ]);

        Size::create($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|max:50|unique:sizes,name,' . $size->id,
            'weight_from' => 'nullable|numeric',
            'weight_to' => 'nullable|numeric',
        ]);

        $size->update($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted successfully.');
    }
}
