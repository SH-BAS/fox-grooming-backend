<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $tax = Tax::first();
        return view('admin.tax.form', compact('tax'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'out_of_area_fee' => 'required|numeric|min:0',
            'reschedule_fee' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
        ]);

        $tax = Tax::first();

        if ($tax) {
            $tax->update($request->only('out_of_area_fee', 'reschedule_fee', 'tax'));
        } else {
            Tax::create($request->only('out_of_area_fee', 'reschedule_fee', 'tax'));
        }

        return redirect()->back()->with('success', 'Tax settings updated successfully.');
    }
}
