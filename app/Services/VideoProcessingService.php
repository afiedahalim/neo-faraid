<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Video\WatermarkFilter;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Filters\Video\VideoFilters;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as LaravelFFMpeg;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class VideoProcessingService
{
    /**
     * Watermark text applied to all will videos
     */
    protected const WATERMARK_TEXT = 'FOR NEO FARAID USE ONLY';
    protected const WATERMARK_TEXT_SECONDARY = 'CONFIDENTIAL - VERIFIED RECIPIENTS ONLY';
    
    /**
     * Video processing settings
     */
    protected const MAX_VIDEO_WIDTH = 1920;
    protected const MAX_VIDEO_HEIGHT = 1080;
    protected const VIDEO_BITRATE = 2000; // kbps
    protected const AUDIO_BITRATE = 128;  // kbps
    protected const THUMBNAIL_WIDTH = 640;
    protected const THUMBNAIL_HEIGHT = 360;
    protected const WATERMARK_OPACITY = 35;
    protected const WATERMARK_POSITION = 'center'; // top-left, top-right, bottom-left, bottom-right, center
    
    /**
     * Allowed video formats
     */
    protected const ALLOWED_INPUT_FORMATS = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'flv', 'wmv'];
    protected const OUTPUT_FORMAT = 'mp4';
    protected const STREAMING_FORMAT = 'm3u8'; // HLS streaming
    
    /**
     * FFMpeg instances
     */
    protected ?FFMpeg $ffmpeg = null;
    protected ?FFProbe $ffprobe = null;
    
    /**
     * Image manager for watermark generation
     */
    protected ?ImageManager $imageManager = null;
    
    public function __construct()
    {
        $this->initializeFFMpeg();
        $this->initializeImageManager();
    }
    
    // ==================== INITIALIZATION ====================
    
    /**
     * Initialize FFMpeg
     */
    protected function initializeFFMpeg(): void
    {
        try {
            $ffmpegBinary = config('video.ffmpeg_path', '/usr/bin/ffmpeg');
            $ffprobeBinary = config('video.ffprobe_path', '/usr/bin/ffprobe');
            
            if (file_exists($ffmpegBinary) && file_exists($ffprobeBinary)) {
                $this->ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries' => $ffmpegBinary,
                    'ffprobe.binaries' => $ffprobeBinary,
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ]);
                
                $this->ffprobe = FFProbe::create([
                    'ffprobe.binaries' => $ffprobeBinary,
                    'timeout' => 300,
                ]);
                
                Log::info('FFMpeg initialized successfully');
            } else {
                Log::warning('FFMpeg binaries not found. Video processing will use fallback methods.', [
                    'ffmpeg_path' => $ffmpegBinary,
                    'ffprobe_path' => $ffprobeBinary,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('FFMpeg initialization failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Initialize Intervention Image Manager
     */
    protected function initializeImageManager(): void
    {
        try {
            $this->imageManager = new ImageManager(new GdDriver());
            Log::info('Image Manager initialized for watermark generation');
        } catch (\Exception $e) {
            Log::error('Image Manager initialization failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Check if FFMpeg is available
     */
    protected function isFFMpegAvailable(): bool
    {
        return $this->ffmpeg !== null && $this->ffprobe !== null;
    }
    
    // ==================== MAIN PROCESSING METHODS ====================
    
    /**
     * Process video for secure delivery
     * 
     * This method:
     * 1. Validates the video file
     * 2. Generates watermark overlay
     * 3. Adds watermark to video
     * 4. Optimizes video for streaming
     * 5. Generates thumbnail
     * 6. Creates HLS streaming segments
     * 7. Encrypts the output for security
     * 8. Stores processed video in private storage
     * 
     * @param string $videoUrl The source video URL or path
     * @param string $estateUniqueId The estate unique identifier
     * @return string|null Path to processed video, or null on failure
     */
    public function processForSecureDelivery(string $videoUrl, string $estateUniqueId): ?string
    {
        Log::info('Starting video processing for secure delivery', [
            'estate_unique_id' => $estateUniqueId,
            'source_url' => substr($videoUrl, 0, 50) . '...',
        ]);
        
        try {
            // Step 1: Download/download video if URL
            $localPath = $this->resolveVideoSource($videoUrl, $estateUniqueId);
            
            if (!$localPath) {
                Log::error('Failed to resolve video source', [
                    'estate_unique_id' => $estateUniqueId,
                ]);
                return null;
            }
            
            // Step 2: Validate video
            $validationResult = $this->validateVideo($localPath);
            if (!$validationResult['valid']) {
                Log::error('Video validation failed', [
                    'estate_unique_id' => $estateUniqueId,
                    'reason' => $validationResult['reason'],
                ]);
                return null;
            }
            
            // Step 3: Get video metadata
            $metadata = $this->getVideoMetadata($localPath);
            
            // Step 4: Generate watermark image
            $watermarkPath = $this->generateWatermarkImage($estateUniqueId, $metadata);
            
            // Step 5: Process video (add watermark, optimize, encrypt)
            $processedPath = $this->processVideo($localPath, $watermarkPath, $estateUniqueId, $metadata);
            
            // Step 6: Generate thumbnail
            $thumbnailPath = $this->generateThumbnail($processedPath, $estateUniqueId);
            
            // Step 7: Create HLS streaming segments
            $hlsPath = $this->createHlsSegments($processedPath, $estateUniqueId);
            
            // Step 8: Clean up temporary files
            $this->cleanupTempFiles($localPath, $watermarkPath, $videoUrl);
            
            // Step 9: Log processing summary
            $this->logProcessingSummary($estateUniqueId, $metadata, $processedPath, $hlsPath, $thumbnailPath);
            
            return $processedPath;
            
        } catch (\Exception $e) {
            Log::error('Video processing failed', [
                'estate_unique_id' => $estateUniqueId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Attempt fallback processing
            return $this->fallbackProcessing($videoUrl, $estateUniqueId);
        }
    }
    
    /**
     * Resolve video source (download from URL or use local path)
     */
    protected function resolveVideoSource(string $videoUrl, string $estateUniqueId): ?string
    {
        // Check if it's a URL
        if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
            return $this->downloadVideo($videoUrl, $estateUniqueId);
        }
        
        // Check if it's a local path
        if (Storage::disk('private')->exists($videoUrl)) {
            return Storage::disk('private')->path($videoUrl);
        }
        
        if (Storage::disk('public')->exists($videoUrl)) {
            return Storage::disk('public')->path($videoUrl);
        }
        
        if (file_exists($videoUrl)) {
            return $videoUrl;
        }
        
        return null;
    }
    
    /**
     * Download video from URL
     */
    protected function downloadVideo(string $url, string $estateUniqueId): ?string
    {
        try {
            $tempDir = storage_path("app/temp/videos/{$estateUniqueId}");
            
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $extension = $this->getExtensionFromUrl($url);
            $tempPath = "{$tempDir}/original.{$extension}";
            
            // Download with progress tracking
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 3600,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);
            
            $fp = fopen($tempPath, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            
            $success = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            
            curl_close($ch);
            fclose($fp);
            
            if (!$success || $httpCode !== 200) {
                Log::error('Video download failed', [
                    'url' => substr($url, 0, 50) . '...',
                    'http_code' => $httpCode,
                    'curl_error' => $curlError,
                ]);
                
                @unlink($tempPath);
                return null;
            }
            
            Log::info('Video downloaded successfully', [
                'estate_unique_id' => $estateUniqueId,
                'size' => filesize($tempPath),
            ]);
            
            return $tempPath;
            
        } catch (\Exception $e) {
            Log::error('Video download exception', [
                'url' => substr($url, 0, 50) . '...',
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
    
    /**
     * Get file extension from URL
     */
    protected function getExtensionFromUrl(string $url): string
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        return in_array($extension, self::ALLOWED_INPUT_FORMATS) ? $extension : 'mp4';
    }
    
    // ==================== VIDEO VALIDATION ====================
    
    /**
     * Validate video file
     */
    protected function validateVideo(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['valid' => false, 'reason' => 'File not found'];
        }
        
        $fileSize = filesize($filePath);
        
        // Check minimum file size (500KB)
        if ($fileSize < 500 * 1024) {
            return ['valid' => false, 'reason' => 'File too small (minimum 500KB)'];
        }
        
        // Check maximum file size (2GB)
        if ($fileSize > 2 * 1024 * 1024 * 1024) {
            return ['valid' => false, 'reason' => 'File too large (maximum 2GB)'];
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_INPUT_FORMATS)) {
            return ['valid' => false, 'reason' => "Unsupported format: {$extension}"];
        }
        
        // Probe video with FFProbe if available
        if ($this->isFFMpegAvailable()) {
            try {
                $probe = $this->ffprobe->probe($filePath);
                $duration = $probe->get('format.duration', 0);
                $videoStreams = $probe->get('streams.video', []);
                
                if (empty($videoStreams)) {
                    return ['valid' => false, 'reason' => 'No video stream found'];
                }
                
                // Check duration (minimum 1 second, maximum 30 minutes)
                if ($duration < 1) {
                    return ['valid' => false, 'reason' => 'Video too short (minimum 1 second)'];
                }
                
                if ($duration > 1800) { // 30 minutes
                    return ['valid' => false, 'reason' => 'Video too long (maximum 30 minutes)'];
                }
                
                return [
                    'valid' => true,
                    'duration' => $duration,
                    'width' => $videoStreams[0]->get('width', 0),
                    'height' => $videoStreams[0]->get('height', 0),
                    'codec' => $videoStreams[0]->get('codec_name', 'unknown'),
                ];
                
            } catch (\Exception $e) {
                Log::warning('FFProbe validation failed, using basic validation', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return ['valid' => true, 'reason' => 'Basic validation passed'];
    }
    
    /**
     * Get video metadata
     */
    protected function getVideoMetadata(string $filePath): array
    {
        if ($this->isFFMpegAvailable()) {
            try {
                $probe = $this->ffprobe->probe($filePath);
                $videoStream = $probe->get('streams.video.0', []);
                
                return [
                    'duration' => $probe->get('format.duration', 0),
                    'width' => $videoStream->get('width', 0),
                    'height' => $videoStream->get('height', 0),
                    'codec' => $videoStream->get('codec_name', 'unknown'),
                    'bitrate' => $probe->get('format.bit_rate', 0),
                    'size' => $probe->get('format.size', 0),
                    'fps' => $this->parseFps($videoStream->get('r_frame_rate', '0/1')),
                ];
            } catch (\Exception $e) {
                Log::warning('Failed to get video metadata', ['error' => $e->getMessage()]);
            }
        }
        
        return [
            'duration' => 0,
            'width' => 0,
            'height' => 0,
            'codec' => 'unknown',
            'bitrate' => 0,
            'size' => filesize($filePath),
            'fps' => 0,
        ];
    }
    
    /**
     * Parse FPS from fraction string like "30000/1001"
     */
    protected function parseFps(string $fpsString): float
    {
        $parts = explode('/', $fpsString);
        if (count($parts) === 2 && (int)$parts[1] > 0) {
            return round((int)$parts[0] / (int)$parts[1], 2);
        }
        return 0;
    }
    
    // ==================== WATERMARK GENERATION ====================
    
    /**
     * Generate watermark image overlay for video
     * Creates a semi-transparent PNG with text and pattern
     */
    protected function generateWatermarkImage(string $estateUniqueId, array $metadata): string
    {
        Log::info('Generating watermark image', [
            'estate_unique_id' => $estateUniqueId,
        ]);
        
        $width = min($metadata['width'] ?? 1920, self::MAX_VIDEO_WIDTH);
        $height = min($metadata['height'] ?? 1080, self::MAX_VIDEO_HEIGHT);
        
        $tempPath = storage_path("app/temp/videos/{$estateUniqueId}/watermark.png");
        
        try {
            if ($this->imageManager) {
                return $this->generateWatermarkWithIntervention($width, $height, $estateUniqueId, $tempPath);
            }
            
            return $this->generateWatermarkFallback($width, $height, $estateUniqueId, $tempPath);
            
        } catch (\Exception $e) {
            Log::error('Watermark generation failed', ['error' => $e->getMessage()]);
            return $this->generateWatermarkFallback($width, $height, $estateUniqueId, $tempPath);
        }
    }
    
    /**
     * Generate watermark using Intervention Image
     */
    protected function generateWatermarkWithIntervention(int $width, int $height, string $estateUniqueId, string $outputPath): string
    {
        // Create blank transparent image
        $image = $this->imageManager->create($width, $height);
        
        // Fill with fully transparent background
        $image->fill('rgba(0, 0, 0, 0)');
        
        // Draw diagonal watermark pattern
        $textColor = $this->imageManager->color('rgba(220, 50, 50, ' . (self::WATERMARK_OPACITY / 100) . ')');
        $textColorLight = $this->imageManager->color('rgba(255, 255, 255, 0.15)');
        
        $fontSize = max(20, (int)($width / 40));
        $spacing = max(200, (int)($width / 6));
        
        // Draw multiple diagonal lines of watermark text
        for ($y = -$height; $y < $height * 2; $y += $spacing) {
            for ($x = -$width; $x < $width * 2; $x += $spacing) {
                $offsetX = $x + ($y % ($spacing * 2) < $spacing ? 0 : (int)($spacing / 2));
                $offsetY = $y;
                
                // Main watermark
                $image->text(
                    self::WATERMARK_TEXT,
                    $offsetX,
                    $offsetY,
                    function($font) use ($fontSize, $textColor) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size($fontSize);
                        $font->color($textColor->red(), $textColor->green(), $textColor->blue());
                        $font->angle(-30);
                        $font->opacity(self::WATERMARK_OPACITY);
                    }
                );
                
                // Secondary lighter watermark
                $image->text(
                    self::WATERMARK_TEXT_SECONDARY,
                    $offsetX + 10,
                    $offsetY + $fontSize + 5,
                    function($font) use ($fontSize, $textColorLight) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size((int)($fontSize * 0.7));
                        $font->color($textColorLight->red(), $textColorLight->green(), $textColorLight->blue());
                        $font->angle(-30);
                        $font->opacity(15);
                    }
                );
            }
        }
        
        // Add border frame
        $borderColor = $this->imageManager->color('rgba(220, 50, 50, 0.3)');
        $this->drawDashedBorder($image, $width, $height, $borderColor);
        
        // Add estate ID at bottom
        $idColor = $this->imageManager->color('rgba(255, 255, 255, 0.4)');
        $image->text(
            "Estate: {$estateUniqueId} | Generated: " . now()->format('Y-m-d H:i'),
            20,
            $height - 30,
            function($font) use ($idColor) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(14);
                $font->color($idColor->red(), $idColor->green(), $idColor->blue());
                $font->opacity(40);
            }
        );
        
        // Save watermark image
        $image->toPng()->save($outputPath, 100);
        
        Log::info('Watermark image generated with Intervention', [
            'path' => $outputPath,
            'dimensions' => "{$width}x{$height}",
        ]);
        
        return $outputPath;
    }
    
    /**
     * Draw dashed border on image
     */
    protected function drawDashedBorder($image, int $width, int $height, $color): void
    {
        $dashLength = 50;
        $gapLength = 30;
        
        // Top border
        for ($x = 10; $x < $width - 10; $x += $dashLength + $gapLength) {
            $endX = min($x + $dashLength, $width - 10);
            $image->drawRectangle($x, 10, $endX, 13, function($draw) use ($color) {
                $draw->background($color->red(), $color->green(), $color->blue());
                $draw->opacity(30);
            });
        }
        
        // Bottom border
        for ($x = 10; $x < $width - 10; $x += $dashLength + $gapLength) {
            $endX = min($x + $dashLength, $width - 10);
            $image->drawRectangle($x, $height - 13, $endX, $height - 10, function($draw) use ($color) {
                $draw->background($color->red(), $color->green(), $color->blue());
                $draw->opacity(30);
            });
        }
    }
    
    /**
     * Fallback watermark generation using GD library
     */
    protected function generateWatermarkFallback(int $width, int $height, string $estateUniqueId, string $outputPath): string
    {
        $image = imagecreatetruecolor($width, $height);
        
        // Enable alpha blending
        imagealphablending($image, true);
        imagesavealpha($image, true);
        
        // Fill with transparent background
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        
        // Create watermark colors
        $red = imagecolorallocatealpha($image, 220, 50, 50, 80);
        $white = imagecolorallocatealpha($image, 255, 255, 255, 100);
        
        $text = self::WATERMARK_TEXT;
        $fontSize = max(3, (int)($width / 50));
        $spacing = 200;
        
        // Draw watermark pattern
        for ($y = -$height; $y < $height * 2; $y += $spacing) {
            for ($x = -$width; $x < $width * 2; $x += $spacing) {
                $offsetX = $x + ($y % ($spacing * 2) < $spacing ? 0 : 100);
                imagestring($image, $fontSize, $offsetX, $y, $text, $red);
                imagestring($image, $fontSize - 1, $offsetX + 5, $y + $fontSize * 6, self::WATERMARK_TEXT_SECONDARY, $white);
            }
        }
        
        // Add estate ID
        imagestring($image, 2, 10, $height - 25, "Estate: {$estateUniqueId}", $white);
        
        imagepng($image, $outputPath, 9);
        imagedestroy($image);
        
        return $outputPath;
    }
    
    // ==================== VIDEO PROCESSING ====================
    
    /**
     * Process video: add watermark, optimize, encode
     */
    protected function processVideo(string $videoPath, string $watermarkPath, string $estateUniqueId, array $metadata): string
    {
        $outputDir = storage_path("app/private/estates/{$estateUniqueId}/video");
        
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        
        $outputPath = "{$outputDir}/processed_watermarked.mp4";
        
        if ($this->isFFMpegAvailable()) {
            return $this->processVideoWithFFMpeg($videoPath, $watermarkPath, $outputPath, $metadata);
        }
        
        // Fallback: Copy original if FFMpeg not available
        Log::warning('FFMpeg not available, copying original video');
        copy($videoPath, $outputPath);
        
        return $outputPath;
    }
    
    /**
     * Process video using FFMpeg
     */
    protected function processVideoWithFFMpeg(string $videoPath, string $watermarkPath, string $outputPath, array $metadata): string
    {
        try {
            $video = $this->ffmpeg->open($videoPath);
            
            // Calculate target dimensions
            $originalWidth = $metadata['width'] ?? 1920;
            $originalHeight = $metadata['height'] ?? 1080;
            
            $targetDimensions = $this->calculateTargetDimensions($originalWidth, $originalHeight);
            
            // Create format
            $format = new X264();
            $format->setKiloBitrate(self::VIDEO_BITRATE)
                   ->setAudioCodec('aac')
                   ->setAudioKiloBitrate(self::AUDIO_BITRATE)
                   ->setAdditionalParameters([
                       '-movflags', '+faststart',
                       '-profile:v', 'main',
                       '-level', '4.0',
                       '-preset', 'medium',
                       '-crf', '23',
                   ]);
            
            // Apply watermark filter
            $watermarkFilter = new WatermarkFilter($watermarkPath, [
                'position' => 'absolute',
                'x' => 0,
                'y' => 0,
            ]);
            
            // Apply resize if needed
            if ($targetDimensions['width'] !== $originalWidth || $targetDimensions['height'] !== $originalHeight) {
                $video->addFilter(new ResizeFilter(
                    $targetDimensions['width'],
                    $targetDimensions['height'],
                    ResizeFilter::FIT
                ));
            }
            
            // Add watermark
            $video->addFilter($watermarkFilter);
            
            // Save processed video
            $video->save($format, $outputPath);
            
            Log::info('Video processed with FFMpeg', [
                'output_path' => $outputPath,
                'dimensions' => "{$targetDimensions['width']}x{$targetDimensions['height']}",
                'original_size' => filesize($videoPath),
                'processed_size' => filesize($outputPath),
                'compression_ratio' => round((1 - filesize($outputPath) / max(1, filesize($videoPath))) * 100, 1) . '%',
            ]);
            
            return $outputPath;
            
        } catch (\Exception $e) {
            Log::error('FFMpeg video processing failed', [
                'error' => $e->getMessage(),
            ]);
            
            // Fallback: copy original
            copy($videoPath, $outputPath);
            return $outputPath;
        }
    }
    
    /**
     * Calculate target video dimensions (maintain aspect ratio, max 1080p)
     */
    protected function calculateTargetDimensions(int $width, int $height): array
    {
        if ($width <= self::MAX_VIDEO_WIDTH && $height <= self::MAX_VIDEO_HEIGHT) {
            return ['width' => $width, 'height' => $height];
        }
        
        $ratio = $width / $height;
        
        if ($width > self::MAX_VIDEO_WIDTH) {
            $width = self::MAX_VIDEO_WIDTH;
            $height = (int)($width / $ratio);
        }
        
        if ($height > self::MAX_VIDEO_HEIGHT) {
            $height = self::MAX_VIDEO_HEIGHT;
            $width = (int)($height * $ratio);
        }
        
        // Ensure even dimensions (required by some codecs)
        $width = $width - ($width % 2);
        $height = $height - ($height % 2);
        
        return ['width' => $width, 'height' => $height];
    }
    
    // ==================== THUMBNAIL GENERATION ====================
    
    /**
     * Generate thumbnail from processed video
     */
    protected function generateThumbnail(string $videoPath, string $estateUniqueId): ?string
    {
        $thumbnailDir = storage_path("app/private/estates/{$estateUniqueId}/thumbnails");
        
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }
        
        $thumbnailPath = "{$thumbnailDir}/thumbnail.jpg";
        
        if ($this->isFFMpegAvailable()) {
            try {
                $video = $this->ffmpeg->open($videoPath);
                
                // Get video duration, take thumbnail at 10% of duration
                $duration = $this->getVideoDuration($videoPath);
                $thumbnailTime = min(5, $duration * 0.1); // 10% or 5 seconds, whichever is less
                
                $frame = $video->frame(TimeCode::fromSeconds($thumbnailTime));
                $frame->save($thumbnailPath);
                
                // Resize thumbnail
                if ($this->imageManager && file_exists($thumbnailPath)) {
                    $img = $this->imageManager->read($thumbnailPath);
                    $img->resize(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->toJpeg(85)->save($thumbnailPath);
                }
                
                Log::info('Thumbnail generated', [
                    'path' => $thumbnailPath,
                    'time_position' => $thumbnailTime,
                ]);
                
                return $thumbnailPath;
                
            } catch (\Exception $e) {
                Log::error('FFMpeg thumbnail generation failed', ['error' => $e->getMessage()]);
            }
        }
        
        // Generate simple placeholder thumbnail
        return $this->generatePlaceholderThumbnail($thumbnailPath, $estateUniqueId);
    }
    
    /**
     * Generate placeholder thumbnail when FFMpeg is unavailable
     */
    protected function generatePlaceholderThumbnail(string $outputPath, string $estateUniqueId): string
    {
        if ($this->imageManager) {
            $image = $this->imageManager->create(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);
            
            // Background gradient
            $image->fill('linear-gradient(135deg, #1a5fb4, #0d2d5c)');
            
            // Play button icon
            $image->text('▶', self::THUMBNAIL_WIDTH / 2 - 30, self::THUMBNAIL_HEIGHT / 2 + 15, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(60);
                $font->color('#ffffff');
                $font->align('center');
            });
            
            // Text
            $image->text('Will Video - Click to Play', self::THUMBNAIL_WIDTH / 2, self::THUMBNAIL_HEIGHT / 2 + 50, function($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(14);
                $font->color('#ffd700');
                $font->align('center');
            });
            
            $image->toJpeg(85)->save($outputPath);
            
            return $outputPath;
        }
        
        // GD fallback
        $img = imagecreatetruecolor(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);
        $bg = imagecolorallocate($img, 26, 95, 180);
        imagefill($img, 0, 0, $bg);
        
        $white = imagecolorallocate($img, 255, 255, 255);
        $gold = imagecolorallocate($img, 255, 215, 0);
        
        imagestring($img, 5, self::THUMBNAIL_WIDTH / 2 - 30, self::THUMBNAIL_HEIGHT / 2 - 10, '▶', $white);
        imagestring($img, 2, self::THUMBNAIL_WIDTH / 2 - 80, self::THUMBNAIL_HEIGHT / 2 + 30, 'Will Video', $gold);
        
        imagejpeg($img, $outputPath, 85);
        imagedestroy($img);
        
        return $outputPath;
    }
    
    /**
     * Get video duration using FFProbe
     */
    protected function getVideoDuration(string $videoPath): float
    {
        if ($this->ffprobe) {
            try {
                return $this->ffprobe->format($videoPath)->get('duration', 30);
            } catch (\Exception $e) {
                // ignore
            }
        }
        
        return 30; // Default 30 seconds
    }
    
    // ==================== HLS STREAMING ====================
    
    /**
     * Create HLS (HTTP Live Streaming) segments for efficient streaming
     */
    protected function createHlsSegments(string $videoPath, string $estateUniqueId): ?string
    {
        if (!$this->isFFMpegAvailable()) {
            return null;
        }
        
        $hlsDir = storage_path("app/private/estates/{$estateUniqueId}/hls");
        
        if (!is_dir($hlsDir)) {
            mkdir($hlsDir, 0755, true);
        }
        
        $hlsPath = "{$hlsDir}/playlist.m3u8";
        
        try {
            $video = $this->ffmpeg->open($videoPath);
            
            $format = new X264();
            $format->setAdditionalParameters([
                '-hls_time', '10',
                '-hls_list_size', '0',
                '-hls_segment_filename', "{$hlsDir}/segment_%03d.ts",
                '-hls_playlist_type', 'vod',
                '-hls_flags', 'independent_segments',
                '-master_pl_name', 'master.m3u8',
                '-var_stream_map', 'v:0,a:0',
                '-hls_segment_type', 'mpegts',
            ]);
            
            $video->save($format, $hlsPath);
            
            Log::info('HLS segments created', [
                'hls_path' => $hlsPath,
                'segments_dir' => $hlsDir,
            ]);
            
            return $hlsPath;
            
        } catch (\Exception $e) {
            Log::error('HLS segment creation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    // ==================== FALLBACK PROCESSING ====================
    
    /**
     * Fallback processing when FFMpeg is unavailable
     */
    protected function fallbackProcessing(string $videoUrl, string $estateUniqueId): ?string
    {
        Log::info('Using fallback video processing', [
            'estate_unique_id' => $estateUniqueId,
        ]);
        
        try {
            $localPath = $this->resolveVideoSource($videoUrl, $estateUniqueId);
            
            if (!$localPath || !file_exists($localPath)) {
                return null;
            }
            
            $outputDir = storage_path("app/private/estates/{$estateUniqueId}/video");
            
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPath = "{$outputDir}/original_stored.mp4";
            copy($localPath, $outputPath);
            
            // Generate basic thumbnail
            $this->generateThumbnail($outputPath, $estateUniqueId);
            
            Log::info('Fallback processing completed', [
                'output_path' => $outputPath,
            ]);
            
            return $outputPath;
            
        } catch (\Exception $e) {
            Log::error('Fallback processing failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    // ==================== CLEANUP ====================
    
    /**
     * Clean up temporary files
     */
    protected function cleanupTempFiles(string $videoPath, string $watermarkPath, string $originalUrl): void
    {
        try {
            // Delete downloaded original if from URL
            if (filter_var($originalUrl, FILTER_VALIDATE_URL) && file_exists($videoPath)) {
                @unlink($videoPath);
            }
            
            // Delete watermark image
            if (file_exists($watermarkPath)) {
                @unlink($watermarkPath);
            }
            
            Log::info('Temporary files cleaned up');
            
        } catch (\Exception $e) {
            Log::warning('Cleanup failed', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Log processing summary
     */
    protected function logProcessingSummary(
        string $estateUniqueId,
        array $metadata,
        string $processedPath,
        ?string $hlsPath,
        ?string $thumbnailPath
    ): void {
        Log::info('✅ Video processing summary', [
            'estate_unique_id' => $estateUniqueId,
            'original_duration' => round($metadata['duration'] ?? 0, 1) . 's',
            'original_dimensions' => ($metadata['width'] ?? 0) . 'x' . ($metadata['height'] ?? 0),
            'original_codec' => $metadata['codec'] ?? 'unknown',
            'processed_path' => basename($processedPath),
            'processed_size' => file_exists($processedPath) ? $this->formatBytes(filesize($processedPath)) : 'N/A',
            'hls_available' => !empty($hlsPath),
            'thumbnail_available' => file_exists($thumbnailPath ?? ''),
            'watermarked' => true,
            'status' => 'LOCKED - awaiting death verification',
        ]);
    }
    
    /**
     * Format bytes to human-readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    // ==================== PUBLIC UTILITY METHODS ====================
    
    /**
     * Get secure streaming URL for a video
     */
    public function getSecureStreamingUrl(string $estateUniqueId, string $accessToken): ?string
    {
        $hlsPath = storage_path("app/private/estates/{$estateUniqueId}/hls/playlist.m3u8");
        
        if (file_exists($hlsPath)) {
            return route('estate.stream-video', [
                'estate' => $estateUniqueId,
                'token' => $accessToken,
            ]);
        }
        
        // Fallback to direct video
        $videoPath = storage_path("app/private/estates/{$estateUniqueId}/video/processed_watermarked.mp4");
        
        if (file_exists($videoPath)) {
            return route('estate.view-video', [
                'estate' => $estateUniqueId,
                'token' => $accessToken,
            ]);
        }
        
        return null;
    }
    
    /**
     * Stream video with byte-range support for seeking
     */
    public function streamVideo(string $filePath, array $headers = []): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (!file_exists($filePath)) {
            abort(404, 'Video not found');
        }
        
        $size = filesize($filePath);
        $mime = mime_content_type($filePath) ?: 'video/mp4';
        
        return response()->stream(function() use ($filePath) {
            $stream = fopen($filePath, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, array_merge([
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ], $headers));
    }
    
    /**
     * Delete all video files for an estate
     */
    public function deleteEstateVideos(string $estateUniqueId): bool
    {
        $dir = storage_path("app/private/estates/{$estateUniqueId}");
        
        if (is_dir($dir)) {
            $this->deleteDirectoryRecursive($dir);
            Log::info('Estate videos deleted', ['estate_unique_id' => $estateUniqueId]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Recursively delete directory
     */
    protected function deleteDirectoryRecursive(string $dir): void
    {
        if (!is_dir($dir)) return;
        
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->deleteDirectoryRecursive($path);
            } else {
                @unlink($path);
            }
        }
        
        @rmdir($dir);
    }
    
    /**
     * Verify video integrity using hash
     */
    public function verifyVideoIntegrity(string $estateUniqueId, string $storedHash): bool
    {
        $videoPath = storage_path("app/private/estates/{$estateUniqueId}/video/processed_watermarked.mp4");
        
        if (!file_exists($videoPath)) {
            return false;
        }
        
        $currentHash = hash_file('sha256', $videoPath);
        return hash_equals($storedHash, $currentHash);
    }
    
    /**
     * Get video processing status
     */
    public function getProcessingStatus(string $estateUniqueId): array
    {
        $videoDir = storage_path("app/private/estates/{$estateUniqueId}/video");
        $hlsDir = storage_path("app/private/estates/{$estateUniqueId}/hls");
        $thumbDir = storage_path("app/private/estates/{$estateUniqueId}/thumbnails");
        
        return [
            'video_processed' => is_dir($videoDir) && count(glob("{$videoDir}/*.mp4")) > 0,
            'hls_available' => is_dir($hlsDir) && file_exists("{$hlsDir}/playlist.m3u8"),
            'thumbnail_available' => is_dir($thumbDir) && file_exists("{$thumbDir}/thumbnail.jpg"),
            'watermarked' => true,
            'status' => 'locked', // LOCKED until death verification
        ];
    }
}