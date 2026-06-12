<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OCRApiService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected bool $useExternalApi;
    
    public function __construct()
    {
        $this->apiKey = config('services.ocr.api_key', '');
        $this->apiUrl = config('services.ocr.api_url', 'https://api.ocr.space/parse/image');
        $this->useExternalApi = config('services.ocr.use_external', false);
    }
    
    /**
     * Extract text from death certificate using OCR
     */
    public function extractFromDeathCertificate(string $filePath): array
    {
        $fullPath = Storage::disk('private')->path($filePath);
        
        if (!file_exists($fullPath)) {
            throw new \Exception('File not found: ' . $fullPath);
        }
        
        try {
            if ($this->useExternalApi && $this->apiKey) {
                $text = $this->extractViaExternalApi($fullPath);
            } else {
                $text = $this->extractViaTesseract($fullPath);
            }
            
            if (empty(trim($text))) {
                throw new \Exception('OCR returned empty result');
            }
            
            $this->saveRawText($filePath, $text);
            
            return $this->parse($text);
            
        } catch (\Exception $e) {
            Log::error('OCR extraction failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Extract using Tesseract OCR (local)
     */
    protected function extractViaTesseract(string $imagePath): string
    {
        $tesseractPath = $this->findTesseractPath();
        
        if (!file_exists($tesseractPath)) {
            throw new \Exception('Tesseract OCR not found at: ' . $tesseractPath);
        }
        
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        
        if ($extension === 'pdf') {
            return $this->processPdfWithTesseract($imagePath, $tesseractPath);
        }
        
        $cmd = sprintf(
            '"%s" "%s" stdout -l msa+eng --oem 3 --psm 6 2>&1',
            $tesseractPath,
            $imagePath
        );
        
        $output = shell_exec($cmd);
        
        if ($output === null) {
            throw new \Exception('Failed to execute Tesseract OCR');
        }
        
        return $output;
    }
    
    /**
     * Process PDF with Tesseract
     */
    protected function processPdfWithTesseract(string $pdfPath, string $tesseractPath): string
    {
        $imagickPath = $this->findImageMagickPath();
        
        if (!$imagickPath) {
            throw new \Exception('ImageMagick required for PDF processing');
        }
        
        $tempDir = sys_get_temp_dir() . '/ocr_' . uniqid();
        mkdir($tempDir, 0777, true);
        
        $cmd = sprintf(
            '"%s" convert -density 300 "%s" "%s/page.png"',
            $imagickPath,
            $pdfPath,
            $tempDir
        );
        
        shell_exec($cmd);
        
        $text = '';
        $images = glob($tempDir . '/*.png');
        
        foreach ($images as $img) {
            $cmd = sprintf(
                '"%s" "%s" stdout -l msa+eng --oem 3 --psm 6 2>&1',
                $tesseractPath,
                $img
            );
            $text .= shell_exec($cmd) . "\n";
        }
        
        $this->cleanup($tempDir);
        
        return $text;
    }
    
    /**
     * Extract using external OCR API
     */
    protected function extractViaExternalApi(string $filePath): string
    {
        $fileContent = base64_encode(file_get_contents($filePath));
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        $response = Http::timeout(60)->post($this->apiUrl, [
            'apikey' => $this->apiKey,
            'language' => 'msa',
            'isOverlayRequired' => false,
            'base64Image' => 'data:image/' . $extension . ';base64,' . $fileContent,
            'OCREngine' => 2,
        ]);
        
        if (!$response->successful()) {
            throw new \Exception('External OCR API failed: ' . $response->body());
        }
        
        $data = $response->json();
        
        if (isset($data['ParsedResults'][0]['ParsedText'])) {
            return $data['ParsedResults'][0]['ParsedText'];
        }
        
        throw new \Exception('No text extracted from external OCR');
    }
    
    /**
     * Parse OCR text to extract death certificate information
     */
    protected function parse(string $text): array
    {
        $lines = explode("\n", $text);
        $normalized = $this->normalizeText($text);
        
        $data = [
            'deceased_name' => null,
            'deceased_nric' => null,
            'date_of_birth' => null,
            'gender' => null,
            'death_date' => null,
            'death_place' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'residential_address' => null,
            'marital_status' => null,
            'father_name' => null,
            'mother_name' => null,
            'spouse_name' => null,
            'cause_of_death' => null,
            'certificate_no' => null,
            'age' => null,
        ];
        
        // Extract NRIC
        if (preg_match('/\b(\d{6}-\d{2}-\d{4})\b/', $normalized, $matches)) {
            $data['deceased_nric'] = $matches[1];
        } elseif (preg_match('/\b(\d{12})\b/', $normalized, $matches)) {
            $data['deceased_nric'] = substr($matches[1], 0, 6) . '-' . 
                                      substr($matches[1], 6, 2) . '-' . 
                                      substr($matches[1], 8, 4);
        }
        
        // Extract Name
        $namePatterns = [
            '/NAMA\s*PENUH\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NO\.|\s+JANTINA)/i',
            '/NAMA\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NO\.)/i',
            '/DECEASED\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NRIC)/i',
        ];
        
        foreach ($namePatterns as $pattern) {
            if (preg_match($pattern, $normalized, $matches)) {
                $data['deceased_name'] = ucwords(strtolower(trim($matches[1])));
                break;
            }
        }
        
        // Extract Gender
        if (preg_match('/JANTINA\s*:?\s*([A-Z]+)/i', $normalized, $matches)) {
            $gender = strtoupper(trim($matches[1]));
            if (strpos($gender, 'LELAKI') !== false || strpos($gender, 'MALE') !== false) {
                $data['gender'] = 'male';
            } elseif (strpos($gender, 'PEREMPUAN') !== false || strpos($gender, 'FEMALE') !== false) {
                $data['gender'] = 'female';
            }
        }
        
        // Extract Death Date
        $datePatterns = [
            '/(\d{1,2})\/(\d{1,2})\/(\d{4})/',
            '/(\d{1,2})-(\d{1,2})-(\d{4})/',
            '/(\d{4})-(\d{2})-(\d{2})/',
            '/TARIKH\s*KEMATIAN\s*:?\s*([^\n]+)/i',
        ];
        
        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $normalized, $matches)) {
                if (strpos($pattern, 'TARIKH') !== false) {
                    $timestamp = strtotime($matches[1]);
                    if ($timestamp) {
                        $data['death_date'] = date('Y-m-d', $timestamp);
                    }
                } elseif (isset($matches[3])) {
                    if (strlen($matches[1]) === 4) {
                        $data['death_date'] = sprintf('%04d-%02d-%02d', $matches[1], $matches[2], $matches[3]);
                    } else {
                        $data['death_date'] = sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
                    }
                }
                if ($data['death_date']) break;
            }
        }
        
        // Extract Death Place
        if (preg_match('/TEMPAT\s*KEMATIAN\s*:?\s*([^\n]+)/i', $normalized, $matches)) {
            $data['death_place'] = trim($matches[1]);
        }
        
        // Extract Cause of Death
        if (preg_match('/SEBAB\s*KEMATIAN\s*:?\s*([^\n]+)/i', $normalized, $matches)) {
            $data['cause_of_death'] = trim($matches[1]);
        }
        
        // Extract Marital Status
        if (preg_match('/STATUS\s*PERKAHWINAN\s*:?\s*([A-Z]+)/i', $normalized, $matches)) {
            $status = strtoupper(trim($matches[1]));
            if (strpos($status, 'BERKAHWIN') !== false) $data['marital_status'] = 'married';
            elseif (strpos($status, 'BUJANG') !== false) $data['marital_status'] = 'single';
            elseif (strpos($status, 'JANDA') !== false || strpos($status, 'DUDA') !== false) $data['marital_status'] = 'widowed';
        }
        
        // Extract Contact Email (look for email pattern)
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches)) {
            $data['contact_email'] = strtolower($matches[0]);
        }
        
        // Extract Contact Phone
        if (preg_match('/\b(01[0-9]-?[0-9]{7,8})\b/', $text, $matches)) {
            $data['contact_phone'] = $matches[1];
        } elseif (preg_match('/\b(0[0-9]{1,2}-?[0-9]{7,8})\b/', $text, $matches)) {
            $data['contact_phone'] = $matches[1];
        }
        
        // Extract Age
        if (preg_match('/UMUR\s*:?\s*(\d+)/i', $normalized, $matches)) {
            $data['age'] = (int)$matches[1];
        }
        
        // Calculate confidence score
        $filledFields = 0;
        $requiredFields = ['deceased_name', 'deceased_nric', 'death_date'];
        foreach ($requiredFields as $field) {
            if (!empty($data[$field])) $filledFields++;
        }
        
        $confidence = (int) round(($filledFields / count($requiredFields)) * 50) + 20;
        $data['_confidence'] = min(100, max(0, $confidence));
        
        return $data;
    }
    
    /**
     * Normalize text for better pattern matching
     */
    protected function normalizeText(string $text): string
    {
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = mb_strtoupper($text, 'UTF-8');
        
        // Fix common OCR misreads
        $replacements = [
            '|' => '1', 'I' => '1', 'l' => '1', '!' => '1',
            'O' => '0', 'Q' => '0', 'S' => '5', '$' => '5',
            'Z' => '2', 'B' => '8',
        ];
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);
        
        // Normalize spaces and line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        
        return trim($text);
    }
    
    /**
     * Find Tesseract OCR executable path
     */
    protected function findTesseractPath(): string
    {
        $paths = [
            'C:\PROGRA~1\Tesseract-OCR\tesseract.exe',
            'C:\Program Files\Tesseract-OCR\tesseract.exe',
            'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe',
            '/usr/bin/tesseract',
            '/usr/local/bin/tesseract',
        ];
        
        // Try to find via which command
        $which = shell_exec('where tesseract 2>nul 2>/dev/null');
        if ($which && trim($which) && file_exists(trim($which))) {
            return trim($which);
        }
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return 'tesseract'; // fallback, hope it's in PATH
    }
    
    /**
     * Find ImageMagick executable path
     */
    protected function findImageMagickPath(): ?string
    {
        $paths = [
            'C:\PROGRA~1\ImageMagick-7.1.2-Q16-HDRI\magick.exe',
            'C:\Program Files\ImageMagick-*\\magick.exe',
            '/usr/bin/convert',
            '/usr/local/bin/convert',
        ];
        
        foreach ($paths as $pattern) {
            $matches = glob($pattern);
            if (!empty($matches) && file_exists($matches[0])) {
                return $matches[0];
            }
        }
        
        return null;
    }
    
    /**
     * Save raw OCR text for debugging
     */
    protected function saveRawText(string $filePath, string $text): void
    {
        try {
            Storage::disk('private')->put($filePath . '.ocr.txt', $text);
        } catch (\Exception $e) {
            // Non-critical, ignore
        }
    }
    
    /**
     * Clean up temporary directory
     */
    protected function cleanup(string $dir): void
    {
        if (is_dir($dir)) {
            foreach (glob($dir . '/*') as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            @rmdir($dir);
        }
    }
}