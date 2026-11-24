<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Size;
use App\Models\PetImage;
use App\Models\PetDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Services\FileUploadService;


class PetController extends Controller
{
    public function index()
    {
        $pets = Auth::user()->pets()->with('category', 'type', 'size')->get();
        return view('profile.pets.index', compact('pets'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $sizes = Size::all();
        return view('profile.pets.create', compact('categories', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'category_type_id' => [
                'required',
                Rule::exists('category_types', 'id')->where(function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                }),
            ],
            'size_id' => [
                Rule::requiredIf(function () use ($request) {
                    $category = Category::find($request->category_id);
                    return $category && $category->pricing_type === 'sizeable';
                }),
                'nullable',
                'exists:sizes,id'
            ],
            'name' => 'required|string|max:255',
            'age' => 'required|numeric',
            'weight' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'is_vaccinated' => 'required|boolean',
            'notes' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048|mimes:jpeg,png,jpg,webp',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:5120|mimes:pdf,doc,docx,jpg,png,jpeg',
            'document_types' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['category_id', 'category_type_id', 'size_id', 'name', 'age', 'weight', 'gender', 'is_vaccinated', 'notes']);
            $data['user_id'] = Auth::id();

            $data['profile_image'] = FileUploadService::uploadSingle($request, 'profile_image', 'pets/profiles');

            $pet = Pet::create($data);

            if ($request->hasFile('images')) {
                FileUploadService::savePetImages($pet->id, $request->file('images'));
            }

            if ($request->hasFile('documents')) {
                FileUploadService::savePetDocuments($pet->id, $request->file('documents'), $request->document_types ?? []);
            }

            DB::commit();
            return redirect()->route('web.pets.index')->with('success', 'Pet added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function getBreeds(Category $category)
    {
        $breeds = $category->types()->where('is_active', true)->get(['id', 'name']);
        return response()->json([
            'breeds' => $breeds,
            'is_sizeable' => $category->pricing_type === 'sizeable'
        ]);
    }

    public function show(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403);
        }
        $pet->load(['category', 'type', 'size', 'images', 'documents']);
        return view('profile.pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403);
        }
        $pet->load('images', 'documents');
        $categories = Category::where('is_active', true)->get();
        $sizes = Size::all();
        $breeds = CategoryType::where('category_id', $pet->category_id)->where('is_active', true)->get();
        return view('profile.pets.edit', compact('pet', 'categories', 'sizes', 'breeds'));
    }

    public function update(Request $request, Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'category_type_id' => [
                'required',
                Rule::exists('category_types', 'id')->where(function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                }),
            ],
            'size_id' => [
                Rule::requiredIf(function () use ($request) {
                    $category = Category::find($request->category_id);
                    return $category && $category->pricing_type === 'sizeable';
                }),
                'nullable',
                'exists:sizes,id'
            ],
            'name' => 'required|string|max:255',
            'age' => 'required|numeric',
            'weight' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'is_vaccinated' => 'required|boolean',
            'notes' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048|mimes:jpeg,png,jpg,webp',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:5120|mimes:pdf,doc,docx,jpg,png,jpeg',
            'document_types' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['category_id', 'category_type_id', 'size_id', 'name', 'age', 'weight', 'gender', 'is_vaccinated', 'notes']);

            if ($request->hasFile('profile_image')) {
                FileUploadService::delete($pet->getRawOriginal('profile_image'));
                $data['profile_image'] = FileUploadService::uploadSingle($request, 'profile_image', 'pets/profiles');
            }

            $pet->update($data);

            if ($request->hasFile('images')) {
                FileUploadService::savePetImages($pet->id, $request->file('images'));
            }

            if ($request->hasFile('documents')) {
                FileUploadService::savePetDocuments($pet->id, $request->file('documents'), $request->document_types ?? []);
            }

            DB::commit();
            return redirect()->route('web.pets.index')->with('success', 'Pet updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteImage(PetImage $image)
    {
        if ($image->pet->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            FileUploadService::delete($image->image);
            $image->delete();
            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403);
        }
        $pet->delete();
        return back()->with('success', 'Pet deleted successfully.');
    }
}
