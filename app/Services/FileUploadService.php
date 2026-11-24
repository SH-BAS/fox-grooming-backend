<?php

namespace App\Services;

use App\Models\PetImage;
use App\Models\PetDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload a single image/file.
     *
     * @param Request $request
     * @param string $key
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    public static function uploadSingle(Request $request, string $key, string $path, string $disk = 'public')
    {
        if ($request->hasFile($key)) {
            return $request->file($key)->store($path, $disk);
        }
        return null;
    }

    /**
     * Upload multiple images/files.
     *
     * @param Request $request
     * @param string $key
     * @param string $path
     * @param string $disk
     * @return array
     */
    public static function uploadMultiple(Request $request, string $key, string $path, string $disk = 'public')
    {
        $paths = [];
        if ($request->hasFile($key)) {
            foreach ($request->file($key) as $file) {
                $paths[] = $file->store($path, $disk);
            }
        }
        return $paths;
    }

    /**
     * Save multiple images for a pet to the database.
     *
     * @param int $petId
     * @param array $files UploadedFile objects
     * @param string $path
     * @return void
     */
    public static function savePetImages(int $petId, $files, string $path = 'pets/gallery')
    {
        if (!$files) return;

        foreach ($files as $file) {
            $storedPath = $file->store($path, 'public');
            PetImage::create([
                'pet_id' => $petId,
                'image' => $storedPath
            ]);
        }
    }

    /**
     * Save multiple documents for a pet to the database.
     *
     * @param int $petId
     * @param array $files UploadedFile objects
     * @param array $documentTypes
     * @param string $path
     * @return void
     */
    public static function savePetDocuments(int $petId, $files, array $documentTypes = [], string $path = 'pets/documents')
    {
        if (!$files) return;

        foreach ($files as $key => $file) {
            $storedPath = $file->store($path, 'public');
            PetDocument::create([
                'pet_id' => $petId,
                'file' => $storedPath,
                'document_type' => $documentTypes[$key] ?? 'Other'
            ]);
        }
    }

    /**
     * Delete a file from storage.
     *
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public static function delete(?string $path, string $disk = 'public')
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}
