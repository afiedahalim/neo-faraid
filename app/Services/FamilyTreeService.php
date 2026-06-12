<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FamilyTreeService
{
    protected $deceased;
    protected $heirsData;
    protected $distributionData;
    
    public function __construct($deceased, $heirsData, $distributionData)
    {
        $this->deceased = $deceased;
        $this->heirsData = $heirsData;
        $this->distributionData = $distributionData;
    }
    
    public function generate()
    {
        try {
            Log::info('FamilyTreeService::generate called', [
                'deceased' => $this->deceased['name'] ?? 'Unknown',
                'heirs_data_keys' => array_keys($this->heirsData),
                'distribution_count' => count($this->distributionData),
                'distribution_sample' => array_slice($this->distributionData, 0, 3)
            ]);
            
            // Generate unique filename
            $filename = 'family_tree_' . time() . '_' . uniqid() . '.png';
            $path = 'family_trees/' . $filename;
            
            // Create the family tree image
            $image = $this->createFamilyTreeImage();
            
            if (!$image) {
                throw new \Exception('Failed to create family tree image');
            }
            
            // Save the image to storage
            $storagePath = storage_path('app/public/' . $path);
            
            // Ensure directory exists
            $directory = dirname($storagePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save image
            imagepng($image, $storagePath);
            imagedestroy($image);
            
            Log::info('Family tree image saved successfully', [
                'path' => $path,
                'full_path' => $storagePath
            ]);
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('FamilyTreeService generation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    private function createFamilyTreeImage()
    {
        // Image dimensions
        $width = 1200;
        $height = 800;
        
        // Create image
        $image = imagecreatetruecolor($width, $height);
        
        // Allocate colors
        $backgroundColor = imagecolorallocate($image, 255, 255, 255);
        $deceasedColor = imagecolorallocate($image, 220, 53, 69); // Red
        $spouseColor = imagecolorallocate($image, 26, 95, 180); // Blue
        $parentColor = imagecolorallocate($image, 37, 211, 102); // Green
        $childColor = imagecolorallocate($image, 255, 193, 7); // Yellow
        $textColor = imagecolorallocate($image, 33, 37, 41); // Dark
        $lineColor = imagecolorallocate($image, 108, 117, 125); // Gray
        $boxBorderColor = imagecolorallocate($image, 206, 212, 218); // Light gray
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);
        
        // Set font (using GD built-in font for simplicity)
        $font = 5; // Built-in GD font
        
        // Calculate positions
        $centerX = $width / 2;
        $deceasedY = 150;
        
        // Draw deceased
        $this->drawPersonBox($image, $centerX, $deceasedY, 
            $this->deceased['name'] ?? 'Deceased', 
            'Deceased', 
            $deceasedColor, $textColor, $boxBorderColor, $font);
        
        // Draw spouse(s)
        $spouseCount = $this->heirsData['wife_count'] ?? 0;
        if ($this->deceased['gender'] === 'female') {
            $spouseCount = $this->heirsData['husband_count'] ?? 0;
        }
        
        if ($spouseCount > 0) {
            $spouseY = $deceasedY + 150;
            $spouseSpacing = 200;
            
            for ($i = 0; $i < min($spouseCount, 2); $i++) {
                $spouseX = $centerX - ($spouseSpacing / 2) + ($i * $spouseSpacing);
                $spouseName = $this->deceased['gender'] === 'male' ? "Wife " . ($i + 1) : "Husband";
                $this->drawPersonBox($image, $spouseX, $spouseY, 
                    $spouseName, 
                    'Spouse', 
                    $spouseColor, $textColor, $boxBorderColor, $font);
                
                // Draw connecting line
                imageline($image, $centerX, $deceasedY + 40, $spouseX, $spouseY - 40, $lineColor);
            }
        }
        
        // Draw parents if alive
        if (($this->heirsData['father_status'] ?? 'deceased') === 'alive') {
            $parentY = $deceasedY - 150;
            $this->drawPersonBox($image, $centerX - 150, $parentY, 
                'Father', 
                'Parent', 
                $parentColor, $textColor, $boxBorderColor, $font);
            
            // Draw connecting line
            imageline($image, $centerX - 150, $parentY + 40, $centerX, $deceasedY - 40, $lineColor);
        }
        
        if (($this->heirsData['mother_status'] ?? 'deceased') === 'alive') {
            $parentY = $deceasedY - 150;
            $this->drawPersonBox($image, $centerX + 150, $parentY, 
                'Mother', 
                'Parent', 
                $parentColor, $textColor, $boxBorderColor, $font);
            
            // Draw connecting line
            imageline($image, $centerX + 150, $parentY + 40, $centerX, $deceasedY - 40, $lineColor);
        }
        
        // Draw children
        $sonCount = $this->heirsData['son_count'] ?? 0;
        $daughterCount = $this->heirsData['daughter_count'] ?? 0;
        $totalChildren = $sonCount + $daughterCount;
        
        if ($totalChildren > 0) {
            $childY = $deceasedY + 300;
            $childSpacing = 150;
            $startX = $centerX - (($totalChildren - 1) * $childSpacing / 2);
            
            $childIndex = 0;
            
            // Draw sons
            for ($i = 0; $i < $sonCount; $i++) {
                $childX = $startX + ($childIndex * $childSpacing);
                $this->drawPersonBox($image, $childX, $childY, 
                    "Son " . ($i + 1), 
                    'Child', 
                    $childColor, $textColor, $boxBorderColor, $font);
                
                // Draw connecting line
                imageline($image, $centerX, $deceasedY + 40, $childX, $childY - 40, $lineColor);
                $childIndex++;
            }
            
            // Draw daughters
            for ($i = 0; $i < $daughterCount; $i++) {
                $childX = $startX + ($childIndex * $childSpacing);
                $this->drawPersonBox($image, $childX, $childY, 
                    "Daughter " . ($i + 1), 
                    'Child', 
                    $childColor, $textColor, $boxBorderColor, $font);
                
                // Draw connecting line
                imageline($image, $centerX, $deceasedY + 40, $childX, $childY - 40, $lineColor);
                $childIndex++;
            }
        }
        
        // Add title
        $title = "Family Tree: " . ($this->deceased['name'] ?? 'Unknown');
        $titleWidth = imagefontwidth($font) * strlen($title);
        $titleX = ($width - $titleWidth) / 2;
        imagestring($image, $font, $titleX, 30, $title, $textColor);
        
        // Add footer with distribution info
        $distributionInfo = "Inheritance Distribution - " . date('Y-m-d');
        $infoWidth = imagefontwidth($font) * strlen($distributionInfo);
        $infoX = ($width - $infoWidth) / 2;
        imagestring($image, $font, $infoX, $height - 40, $distributionInfo, $textColor);
        
        return $image;
    }
    
    private function drawPersonBox($image, $x, $y, $name, $relationship, $fillColor, $textColor, $borderColor, $font)
    {
        $boxWidth = 120;
        $boxHeight = 80;
        
        // Draw box
        imagefilledrectangle($image, 
            $x - ($boxWidth / 2), 
            $y - ($boxHeight / 2), 
            $x + ($boxWidth / 2), 
            $y + ($boxHeight / 2), 
            $fillColor);
        
        // Draw border
        imagerectangle($image, 
            $x - ($boxWidth / 2), 
            $y - ($boxHeight / 2), 
            $x + ($boxWidth / 2), 
            $y + ($boxHeight / 2), 
            $borderColor);
        
        // Calculate text positions
        $nameWidth = imagefontwidth($font) * strlen($name);
        $nameX = $x - ($nameWidth / 2);
        
        $relationshipWidth = imagefontwidth($font) * strlen($relationship);
        $relationshipX = $x - ($relationshipWidth / 2);
        
        // Draw name
        imagestring($image, $font, $nameX, $y - 25, $name, $textColor);
        
        // Draw relationship
        imagestring($image, $font, $relationshipX, $y - 10, $relationship, $textColor);
        
        // Add inheritance amount if available
        foreach ($this->distributionData as $heir) {
            if (stripos($heir['heir'] ?? '', $name) !== false || 
                stripos($heir['relationship'] ?? '', $relationship) !== false) {
                
                $amount = isset($heir['amount']) ? 'RM ' . number_format($heir['amount'], 2) : '';
                if ($amount) {
                    $amountWidth = imagefontwidth($font) * strlen($amount);
                    $amountX = $x - ($amountWidth / 2);
                    imagestring($image, $font, $amountX, $y + 5, $amount, $textColor);
                }
                break;
            }
        }
    }
}