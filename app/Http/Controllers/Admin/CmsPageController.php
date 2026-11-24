<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    protected function allowedKeys(): array
    {
        return [
            'terms_condition' => 'Terms & Conditions',
            'privacy_policy' => 'Privacy Policy',
            'about_us' => 'About Us',
            'e_signature' => 'E-Signature Agreement',
        ];
    }

    public function index()
    {
        $allowedKeys = $this->allowedKeys();

        $existingPages = CmsPage::whereIn('page_key', array_keys($allowedKeys))->get()->keyBy('page_key');

        return view('admin.cms-pages.index', compact('allowedKeys', 'existingPages'));
    }

    public function edit($key)
    {
        $allowedKeys = $this->allowedKeys();

        if (!array_key_exists($key, $allowedKeys)) {
            return redirect()->route('admin.cms-pages.index')->with('error', 'Invalid page key.');
        }

        $page = CmsPage::firstOrNew(['page_key' => $key]);
        if (!$page->exists) {
            $page->title = $allowedKeys[$key];
        }

        return view('admin.cms-pages.edit', compact('page', 'key', 'allowedKeys'));
    }

    public function update(Request $request, $key)
    {
        $allowedKeys = $this->allowedKeys();

        if (!array_key_exists($key, $allowedKeys)) {
            return redirect()->route('admin.cms-pages.index')->with('error', 'Invalid page key.');
        }

        $request->validate([
            'title' => 'required|max:200',
            'body' => 'required',
        ]);

        CmsPage::updateOrCreate(
            ['page_key' => $key],
            [
                'title' => $request->title,
                'body' => $request->body,
            ]
        );

        return redirect()->route('admin.cms-pages.index')->with('success', 'Page saved successfully.');
    }
}
