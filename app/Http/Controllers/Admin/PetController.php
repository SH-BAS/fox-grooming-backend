<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetImage;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Services\FileUploadService;


class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::withTrashed()->with(['user', 'category', 'type', 'size', 'images'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.pets.index', compact('pets'));
    }

    public function create(Request $request)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $types = CategoryType::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::all();
        $selectedUser = null;

        if ($request->filled('user_id')) {
            $selectedUser = User::find($request->user_id);
        }

        return view('admin.pets.create', compact('users', 'categories', 'types', 'sizes', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'name' => 'required|max:150',
            'age' => 'required|numeric',
            'weight' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'is_vaccinated' => 'required|boolean',
            'notes' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp,jfif',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048|mimes:jpeg,png,jpg,webp,jfif,gif',
        ]);

        $data = $validated;
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = FileUploadService::uploadSingle($request, 'profile_image', 'pets/profiles');
        }

        // Create the Pet record
        $pet = Pet::create($data);

        if ($request->hasFile('images')) {
            FileUploadService::savePetImages($pet->id, $request->file('images'));
        }

        if ($request->filled('redirect_user_id')) {
            return redirect()->route('admin.users.show', $request->redirect_user_id)
                ->with('success', 'Pet added successfully for the selected customer.');
        }

        return redirect()->route('admin.pets.index')->with('success', 'Pet created successfully with gallery.');
    }

    public function show(Pet $pet)
    {   
        //withtrashed
        $pet->withTrashed();
        $pet->load(['user', 'category', 'type', 'size', 'bookings.bookedServices.service', 'images', 'documents']);
        return view('admin.pets.show', compact('pet'));
    }

    public function edit(Request $request, Pet $pet)
    {
        $users = User::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $types = CategoryType::where('is_active', true)->orderBy('name')->get();
        $sizes = Size::all();
        $redirectUser = null;

        if ($request->filled('redirect_user_id')) {
            $redirectUser = User::find($request->redirect_user_id);
        }

        return view('admin.pets.edit', compact('pet', 'users', 'categories', 'types', 'sizes', 'redirectUser'));
    }

    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'name' => 'required|max:150',
            'age' => 'required|numeric',
            'weight' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'is_vaccinated' => 'required|boolean',
            'notes' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,webp,jfif',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048|mimes:jpeg,png,jpg,webp,jfif,gif',
        ]);

        $data = $validated;
        if ($request->hasFile('profile_image')) {
            FileUploadService::delete($pet->getRawOriginal('profile_image'));
            $data['profile_image'] = FileUploadService::uploadSingle($request, 'profile_image', 'pets/profiles');
        }

        $pet->update($data);

        if ($request->hasFile('images')) {
            FileUploadService::savePetImages($pet->id, $request->file('images'));
        }

        if ($request->filled('redirect_user_id')) {
            return redirect()->route('admin.users.show', $request->redirect_user_id)
                ->with('success', 'Pet details updated successfully.');
        }

        return redirect()->route('admin.pets.index')->with('success', 'Pet details and gallery updated successfully.');
    }

    public function getBreeds(Category $category)
    {
        $breeds = $category->types()->where('is_active', true)->get(['id', 'name']);
        return response()->json([
            'breeds' => $breeds,
            'is_sizeable' => $category->pricing_type === 'sizeable'
        ]);
    }

    public function deleteImage(PetImage $image)
    {
        FileUploadService::delete($image->image);


        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }

    public function destroy(Request $request, Pet $pet)
    {
        $userId = $request->input('redirect_user_id', $pet->user_id);
        $pet->delete();

        if ($request->filled('redirect_user_id')) {
            return redirect()->route('admin.users.show', $userId)
                ->with('success', 'Pet deleted successfully.');
        }

        return redirect()->route('admin.pets.index')->with('success', 'Pet deleted successfully.');
    }
}
