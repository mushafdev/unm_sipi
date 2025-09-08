<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function processWritingImage($file, $disk)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $uuid = Str::uuid() . '.' . $ext;
        
        // Simpan original
        // Storage::disk($disk)->putFileAs('original', $file, $uuid);
        
        // Process different sizes
        $this->createMediumImage($file, $uuid, $ext, $disk);
        // $this->createThumbnail($file, $uuid, $ext, $disk);
        
        return $uuid;
    }
    
    private function createMediumImage($file, $uuid, $ext, $disk)
    {
        $originalImage = Image::make($file->getRealPath());
        $originalWidth = $originalImage->width();
        $originalHeight = $originalImage->height();
        
        // Hanya resize jika gambar lebih besar dari target
        if ($originalWidth > 800) {
            $image = $originalImage->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Tambah sharpening yang lebih halus
            if ($this->needsSharpening($originalWidth, 800)) {
                $image->sharpen(10);
            }
        } else {
            $image = $originalImage;
        }
        
        $quality = $this->getQualityForSize('medium', $ext);
        $encodedImage = $image->encode($ext, $quality);
        
        Storage::disk($disk)->put("medium/{$uuid}", (string) $encodedImage);
    }
    
    private function createThumbnail($file, $uuid, $ext, $disk)
    {
        $originalImage = Image::make($file->getRealPath());
        $originalWidth = $originalImage->width();
        $originalHeight = $originalImage->height();
        
        // Gunakan pendekatan yang lebih smooth untuk thumbnail
        if ($originalWidth > 300 || $originalHeight > 300) {
            // Resize bertahap untuk hasil yang lebih halus
            $image = $this->resizeWithSteps($originalImage, 300);
        } else {
            $image = $originalImage;
        }
        
        // Sharpening yang sangat ringan untuk thumbnail
        if ($this->needsSharpening($originalWidth, 300)) {
            $image->sharpen(5); // Kurangi dari 10 ke 5
        }
        
        // Tambahkan contrast dan brightness untuk kualitas lebih baik
        $image->contrast(2)->brightness(1);
        
        $quality = $this->getQualityForSize('thumb', $ext);
        $encodedImage = $image->encode($ext, $quality);
        
        Storage::disk($disk)->put("thumb/{$uuid}", (string) $encodedImage);
    }
    
    /**
     * Resize dengan langkah bertahap untuk hasil yang lebih halus
     */
    private function resizeWithSteps($image, $targetSize)
    {
        $currentWidth = $image->width();
        $currentHeight = $image->height();
        
        // Tentukan dimensi terbesar
        $maxDimension = max($currentWidth, $currentHeight);
        
        // Jika pengurangan drastis (>50%), lakukan resize bertahap
        if ($maxDimension > $targetSize * 2) {
            // Step 1: Resize ke 50% dari ukuran asli
            $firstStep = $maxDimension * 0.5;
            $image = $image->resize($firstStep, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Step 2: Resize ke ukuran target
            $image = $image->resize($targetSize, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            // Resize langsung jika pengurangan tidak terlalu drastis
            $image = $image->resize($targetSize, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        return $image;
    }
    
    private function needsSharpening($originalWidth, $targetWidth)
    {
        // Threshold yang lebih ketat untuk sharpening
        $reductionRatio = $targetWidth / $originalWidth;
        return $reductionRatio < 0.6; // Ubah dari 0.7 ke 0.6
    }
    
    private function getQualityForSize($size, $ext)
    {
        $qualityMap = [
            'jpg' => ['medium' => 92, 'thumb' => 95], // Naikkan quality thumbnail
            'jpeg' => ['medium' => 92, 'thumb' => 95],
            'png' => ['medium' => 95, 'thumb' => 95], // Naikkan quality thumbnail
            'webp' => ['medium' => 88, 'thumb' => 90] // Naikkan quality thumbnail
        ];
        
        return $qualityMap[$ext][$size] ?? 90; // Default quality lebih tinggi
    }
    
    public function deleteImages($filename, $disk)
    {
        if ($filename) {
            Storage::disk($disk)->delete([
                'original/' . $filename,
                'medium/' . $filename,
                'thumb/' . $filename
            ]);
        }
    }
}