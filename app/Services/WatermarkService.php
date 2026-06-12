<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WatermarkService
{
    protected $imageManager;
    
    public function __construct()
    {
        // V2 API - simple constructor
        $this->imageManager = new ImageManager(['driver' => 'gd']);
    }
    
    /**
     * Apply watermark to death certificate
     */
    public function applyWatermarkToDeathCertificate(string $filePath, string $sessionId): string
    {
        try {
            $fullPath = Storage::disk('private')->path($filePath);
            
            if (!file_exists($fullPath)) {
                Log::warning("File not found for watermarking: {$fullPath}");
                return $filePath;
            }
            
            // For PDF files, just return original path
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            if ($extension === 'pdf') {
                return $filePath;
            }
            
            // Open image
            $image = $this->imageManager->make($fullPath);
            
            // Add watermark text
            $image->text('FOR NEO FARAID USE ONLY', 
                $image->width() / 2, 
                $image->height() / 2, 
                function($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(48);
                    $font->color([255, 0, 0, 0.3]);
                    $font->align('center');
                    $font->valign('middle');
                    $font->angle(45);
                }
            );
            
            // Save watermarked image
            $watermarkedPath = 'watermarked/death-certificates/' . $sessionId . '.' . $extension;
            Storage::disk('private')->put($watermarkedPath, $image->encode());
            
            return $watermarkedPath;
            
        } catch (\Exception $e) {
            Log::error('Watermark error: ' . $e->getMessage());
            return $filePath; // Return original if watermarking fails
        }
    }
}