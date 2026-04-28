<?php

declare(strict_types=1);

namespace App\Actions\Product;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class HandleProductImage
{
    public const STORAGE_DISK = 'public';
    public const STORAGE_PATH = 'products';

    public function store(UploadedFile $file): string
    {
        $filename = $this->generateUniqueFilename($file);

        $file->storeAs(self::STORAGE_PATH, $filename, self::STORAGE_DISK);

        return $filename;
    }

    public function delete(string $filename): bool
    {
        return Storage::disk(self::STORAGE_DISK)->delete(self::STORAGE_PATH . '/' . $filename);
    }

    public function update(string $oldFilename, UploadedFile $newFile): string
    {
        $this->delete($oldFilename);

        return $this->store($newFile);
    }

    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = hexdec(uniqid());

        return "{$baseName}.{$extension}";
    }
}
