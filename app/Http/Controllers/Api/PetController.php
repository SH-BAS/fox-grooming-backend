<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetImage;
use App\Models\PetDocument;
use App\Http\Resources\PetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;


class PetController extends Controller
{
    public function index(Request $request)
    {
        $pets = $request->user()->pets()->with(['category', 'type', 'size', 'images', 'documents'])->get();
        return $this->success(PetResource::collection($pets), 'Pets retrieved');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'category_type_id' => 'required|exists:category_types,id',
            'size_id' => 'required|exists:sizes,id',
            'name' => 'required|string|max:255',
            'age' => 'nullable|string',
            'weight' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'is_vaccinated' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            $data = $request->only([
                'category_id',
                'category_type_id',
                'size_id',
                'name',
                'age',
                'weight',
                'gender',
                'is_vaccinated',
                'notes'
            ]);

            if ($request->hasFile('profile_image')) {
                $data['profile_image'] = FileUploadService::uploadSingle($request, 'profile_image', 'pets/profile');
            }

            $pet = $request->user()->pets()->create($data);

            if ($request->hasFile('images')) {
                FileUploadService::savePetImages($pet->id, $request->file('images'));
            }

            if ($request->hasFile('documents')) {
                FileUploadService::savePetDocuments($pet->id, $request->file('documents'));
            }

            DB::commit();

            return $this->success(new PetResource($pet->load(['images', 'documents'])), 'Pet created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Request $request, $id)
    {
        $pet = Pet::find($id);
        if (!$pet) {
            return $this->error('Pet not found', 404);
        }
        if ($pet->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        return $this->success(new PetResource($pet->load(['category', 'type', 'size', 'images', 'documents'])), 'Pet details retrieved');
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::find($id);
        if (!$pet) {
            return $this->error('Pet not found', 404);
        }
        if ($pet->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'category_type_id' => 'required|exists:category_types,id',
            'size_id' => 'required|exists:sizes,id',
            'name' => 'sometimes|string|max:255',
            'age' => 'nullable|string',
            'weight' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'is_vaccinated' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            $data = $request->only([
                'category_id',
                'category_type_id',
                'size_id',
                'name',
                'age',
                'weight',
                'gender',
                'is_vaccinated',
                'notes'
            ]);

            if ($request->hasFile('profile_image')) {
                FileUploadService::delete($pet->getRawOriginal('profile_image'));
                $data['profile_image'] = FileUploadService::uploadSingle($request, 'profile_image', 'pets/profiles');
            }

            $pet->update($data);

            if ($request->hasFile('images')) {
                FileUploadService::savePetImages($pet->id, $request->file('images'));
            }

            if ($request->hasFile('documents')) {
                FileUploadService::savePetDocuments($pet->id, $request->file('documents'));
            }

            DB::commit();

            return $this->success(new PetResource($pet->load(['images', 'documents'])), 'Pet updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $pet = Pet::find($id);
        if (!$pet) {
            return $this->error('Pet not found', 404);
        }
        if ($pet->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        $pet->delete(); // Soft delete
        return $this->success([], 'Pet deleted successfully');
    }

    public function removeImage(Request $request, $id)
    {
        $petImage = PetImage::find($id);
        if (!$petImage) {
            return $this->error('Pet image not found', 404);
        }
        $pet = $petImage->pet;
        if ($pet->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        FileUploadService::delete($petImage->image);
        $petImage->delete();

        return $this->success([], 'Image removed');
    }

    public function removeDocument(Request $request, $id)
    {
        $petDocument = PetDocument::find($id);
        if (!$petDocument) {
            return $this->error('Pet document not found', 404);
        }
        $pet = $petDocument->pet;
        if ($pet->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        FileUploadService::delete($petDocument->document);
        $petDocument->delete();

        return $this->success([], 'Document removed');
    }
}
