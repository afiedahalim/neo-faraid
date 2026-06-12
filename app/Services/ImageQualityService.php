<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ImageQualityService
{
    /**
     * Check the quality of an uploaded death certificate image
     * Lightweight checks that won't reject good quality documents
     */
    public function checkQuality(string $filePath, string $extension): array
    {
        $issues = [];
        $score = 100;
        
        try {
            // For PDF files, always pass quality check
            if ($extension === 'pdf') {
                return ['passed' => true, 'score' => 100, 'issues' => []];
            }
            
            // Load image using GD
            $image = null;
            switch (strtolower($extension)) {
                case 'jpg':
                case 'jpeg':
                    $image = @imagecreatefromjpeg($filePath);
                    break;
                case 'png':
                    $image = @imagecreatefrompng($filePath);
                    break;
                case 'webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $image = @imagecreatefromwebp($filePath);
                    }
                    break;
            }
            
            if (!$image) {
                Log::warning('Could not load image for quality check');
                return ['passed' => true, 'score' => 100, 'issues' => []];
            }
            
            $width = imagesx($image);
            $height = imagesy($image);
            
            // MINIMUM resolution check - more lenient (500x400 minimum)
            if ($width < 500 || $height < 400) {
                $issues[] = 'low_resolution';
                $score -= 20;
                Log::info("Image resolution: {$width}x{$height} - acceptable");
            } elseif ($width < 800 || $height < 600) {
                // Acceptable but lower quality - just a warning, not rejection
                $score -= 5;
                Log::info("Image resolution: {$width}x{$height} - medium quality, still acceptable");
            } else {
                Log::info("Image resolution: {$width}x{$height} - good quality");
            }
            
            // Calculate blur score - MUCH more lenient thresholds
            $blurScore = $this->calculateBlurScore($image);
            
            // Very lenient blur detection - only reject if extremely blurry
            if ($blurScore < 30) {
                $issues[] = 'blurry';
                $score -= 30;
                Log::warning("Image may be blurry: blur score {$blurScore} (threshold < 30)");
            } elseif ($blurScore < 60) {
                // Acceptable - just reduce score slightly
                $score -= 10;
                Log::info("Image quality: blur score {$blurScore} - acceptable");
            } else {
                Log::info("Image quality: blur score {$blurScore} - good quality");
            }
            
            // Check for corner edges (document completeness) - VERY lenient
            $edgeScore = $this->checkCornerEdges($image);
            if ($edgeScore < 0.3) {
                // Only reject if more than 3 corners are missing
                $issues[] = 'incomplete_edges';
                $score -= 25;
                Log::warning("Edge detection score: {$edgeScore} - document may be cropped");
            } elseif ($edgeScore < 0.5) {
                // Acceptable but note it
                $score -= 10;
                Log::info("Edge detection score: {$edgeScore} - document partially cropped, still acceptable");
            } else {
                Log::info("Edge detection score: {$edgeScore} - good document edges");
            }
            
            // Check for glare/exposure - VERY lenient
            $glareScore = $this->checkGlare($image);
            if ($glareScore > 0.30) {
                $issues[] = 'glare';
                $score -= 20;
                Log::warning("Glare score: {$glareScore} - excessive glare detected");
            } elseif ($glareScore > 0.15) {
                // Acceptable glare
                $score -= 5;
                Log::info("Glare score: {$glareScore} - some glare, still acceptable");
            } else {
                Log::info("Glare score: {$glareScore} - good lighting");
            }
            
            imagedestroy($image);
            
        } catch (\Exception $e) {
            Log::warning('Quality check failed: ' . $e->getMessage());
            // Don't reject on error - allow upload
            return ['passed' => true, 'score' => 100, 'issues' => []];
        }
        
        // MUCH more lenient passing threshold - only reject if score < 40
        $passed = count($issues) === 0 || $score >= 40;
        $score = max(0, min(100, $score));
        
        Log::info('Quality check result', [
            'passed' => $passed,
            'score' => $score,
            'issues' => $issues,
        ]);
        
        return [
            'passed' => $passed,
            'score' => $score,
            'issues' => $issues,
            'details' => [
                'resolution' => ['width' => $width ?? 0, 'height' => $height ?? 0],
                'blur_score' => $blurScore ?? 0,
                'edge_score' => $edgeScore ?? 0,
                'glare_score' => $glareScore ?? 0,
            ],
        ];
    }
    
    /**
     * Calculate blur score using Laplacian variance method
     */
    private function calculateBlurScore($image): float
    {
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Use larger sample steps for performance
        $sampleStep = 30;
        $laplacianSum = 0;
        $sampleCount = 0;
        
        // Simple Laplacian kernel
        $kernel = [
            [0, -1, 0],
            [-1, 4, -1],
            [0, -1, 0]
        ];
        
        for ($y = $sampleStep; $y < $height - $sampleStep; $y += $sampleStep) {
            for ($x = $sampleStep; $x < $width - $sampleStep; $x += $sampleStep) {
                // Get grayscale value at pixel
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $gray = (0.299 * $r + 0.587 * $g + 0.114 * $b);
                
                // Apply Laplacian
                $laplacian = 0;
                for ($ky = -1; $ky <= 1; $ky++) {
                    for ($kx = -1; $kx <= 1; $kx++) {
                        $px = $x + $kx;
                        $py = $y + $ky;
                        if ($px >= 0 && $px < $width && $py >= 0 && $py < $height) {
                            $rgb2 = imagecolorat($image, $px, $py);
                            $r2 = ($rgb2 >> 16) & 0xFF;
                            $g2 = ($rgb2 >> 8) & 0xFF;
                            $b2 = $rgb2 & 0xFF;
                            $gray2 = (0.299 * $r2 + 0.587 * $g2 + 0.114 * $b2);
                            $laplacian += $kernel[$ky + 1][$kx + 1] * $gray2;
                        }
                    }
                }
                
                $laplacianSum += abs($laplacian);
                $sampleCount++;
            }
        }
        
        // Normalize by image area for consistent scoring
        $normalizedVariance = $sampleCount > 0 ? ($laplacianSum / $sampleCount) * (1000 / min($width, $height)) : 0;
        return $normalizedVariance;
    }
    
    /**
     * Check if document corners are visible (document is complete)
     */
    private function checkCornerEdges($image): float
    {
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Check multiple points near each corner for more accuracy
        $cornerRegions = [
            // Top-left corner region
            [
                ['x' => 20, 'y' => 20],
                ['x' => 30, 'y' => 20],
                ['x' => 20, 'y' => 30],
            ],
            // Top-right corner region
            [
                ['x' => $width - 20, 'y' => 20],
                ['x' => $width - 30, 'y' => 20],
                ['x' => $width - 20, 'y' => 30],
            ],
            // Bottom-left corner region
            [
                ['x' => 20, 'y' => $height - 20],
                ['x' => 30, 'y' => $height - 20],
                ['x' => 20, 'y' => $height - 30],
            ],
            // Bottom-right corner region
            [
                ['x' => $width - 20, 'y' => $height - 20],
                ['x' => $width - 30, 'y' => $height - 20],
                ['x' => $width - 20, 'y' => $height - 30],
            ],
        ];
        
        $darkCornersCount = 0;
        
        foreach ($cornerRegions as $corner) {
            $darkPoints = 0;
            foreach ($corner as $point) {
                $rgb = imagecolorat($image, $point['x'], $point['y']);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Dark if average < 150 (document background with text)
                $avg = ($r + $g + $b) / 3;
                if ($avg < 150) {
                    $darkPoints++;
                }
            }
            // If at least 2 of 3 points are dark, consider corner present
            if ($darkPoints >= 2) {
                $darkCornersCount++;
            }
        }
        
        // Return ratio of corners detected
        return $darkCornersCount / 4;
    }
    
    /**
     * Check for glare/overexposure
     */
    private function checkGlare($image): float
    {
        $width = imagesx($image);
        $height = imagesy($image);
        
        $sampleStep = 30;
        $overexposedCount = 0;
        $sampleCount = 0;
        
        for ($y = $sampleStep; $y < $height - $sampleStep; $y += $sampleStep) {
            for ($x = $sampleStep; $x < $width - $sampleStep; $x += $sampleStep) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Overexposed if all channels > 245 (more lenient than before)
                if ($r > 245 && $g > 245 && $b > 245) {
                    $overexposedCount++;
                }
                $sampleCount++;
            }
        }
        
        return $sampleCount > 0 ? $overexposedCount / $sampleCount : 0;
    }
}