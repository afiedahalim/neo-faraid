<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OCRService
{
    protected string $tesseractPath;
    protected ?string $imagickPath;
    protected string $language;

    public function __construct()
    {
        $this->tesseractPath = $this->findTesseractPath();
        $this->imagickPath = $this->findImageMagickPath();
        $this->language = config('services.tesseract.language', 'msa+eng');
        
        Log::info('OCR Service initialized', [
            'tesseract' => $this->tesseractPath,
            'imagick' => $this->imagickPath,
        ]);
    }

    /**
     * Extract structured data from a death certificate image/PDF.
     *
     * @param string $filePath Relative path in the 'private' disk.
     * @return array Parsed data with a '_confidence' key.
     *
     * @throws \Exception
     */
    public function extractFromDeathCertificate(string $filePath): array
    {
        $fullPath = Storage::disk('private')->path($filePath);

        if (!file_exists($fullPath)) {
            throw new \Exception('File not found: ' . $fullPath);
        }

        $text = $this->performOCR($fullPath);

        if (empty(trim($text))) {
            throw new \Exception('OCR returned empty result');
        }

        $this->saveRawText($filePath, $text);

        $parsedData = $this->parse($text);
        
        // DEBUG: Log the parsed data
        Log::info('OCR Parsed Data', [
            'deceased_name' => $parsedData['deceased_name'] ?? null,
            'deceased_nric' => $parsedData['deceased_nric'] ?? null,
            'cause_of_death' => $parsedData['cause_of_death'] ?? null,
            'confidence' => $parsedData['_confidence'] ?? 0,
        ]);
        
        return $parsedData;
    }

    // ------------------------------------------------------------------------
    // OCR Execution
    // ------------------------------------------------------------------------

    protected function performOCR(string $filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'pdf') {
            return $this->processPdf($filePath);
        }

        return $this->processImage($filePath);
    }

    protected function processImage(string $imagePath): string
    {
        $cmd = sprintf(
            '"%s" "%s" stdout -l %s --oem 3 --psm 6 2>&1',
            $this->tesseractPath,
            $imagePath,
            $this->language
        );

        $output = shell_exec($cmd) ?? '';
        
        Log::info('Tesseract command executed', ['cmd' => $cmd]);
        Log::info('OCR Output preview', ['output' => substr($output, 0, 500)]);
        
        return $output;
    }

    protected function processPdf(string $pdfPath): string
    {
        if ($this->imagickPath) {
            return $this->pdfViaCli($pdfPath);
        }
        return $this->pdfViaPhp($pdfPath);
    }

    protected function pdfViaCli(string $pdfPath): string
    {
        $tempDir = sys_get_temp_dir() . '/ocr_' . uniqid();
        mkdir($tempDir);

        $cmd = sprintf(
            '"%s" convert -density 300 "%s" "%s/page.png"',
            $this->imagickPath,
            $pdfPath,
            $tempDir
        );

        shell_exec($cmd);

        $text = '';
        foreach (glob($tempDir . '/*.png') as $img) {
            $text .= $this->processImage($img) . "\n";
        }

        $this->cleanup($tempDir);

        return $text;
    }

    protected function pdfViaPhp(string $pdfPath): string
    {
        if (!extension_loaded('imagick')) {
            throw new \Exception('Imagick extension not loaded');
        }
        
        $imagick = new \Imagick();
        $imagick->setResolution(300, 300);
        $imagick->readImage($pdfPath);

        $text = '';

        foreach ($imagick as $page) {
            $page->setImageFormat('png');
            $tmp = tempnam(sys_get_temp_dir(), 'ocr') . '.png';
            $page->writeImage($tmp);

            $text .= $this->processImage($tmp) . "\n";
            unlink($tmp);
        }
        
        $imagick->clear();

        return $text;
    }

    // ------------------------------------------------------------------------
    // PARSER - Generic JPN Death Certificate Parser
    // ------------------------------------------------------------------------

    protected function parse(string $text): array
    {
        $lines = explode("\n", $text);
        $normalized = $this->normalize($text);
        
        Log::debug('OCR Text Preview', ['text' => substr($text, 0, 1000)]);

        // Initialize result array with all possible fields
        $data = [
            'certificate_no'         => null,
            'deceased_name'          => null,
            'deceased_nric'          => null,
            'age'                    => null,
            'gender'                 => null,
            'death_date'             => null,
            'death_time'             => null,
            'death_place'            => null,
            'cause_of_death'         => null,
            'last_address'           => null,
            'informant_name'         => null,
            'doctor_name'            => null,
            'spouse_name'            => null,
            'father_name'            => null,
            'mother_name'            => null,
            'children_count'         => null,
            'estate_value'           => null,
            'informant_relationship' => null,
        ];

        // Extract each field using multiple methods
        $data['certificate_no'] = $this->findCertificateNumber($lines, $normalized);
        $data['deceased_name'] = $this->findDeceasedName($lines, $normalized);
        $data['deceased_nric'] = $this->findNRIC($normalized);
        $data['age'] = $this->findAge($lines, $normalized);
        $data['gender'] = $this->findGender($normalized);
        $data['death_date'] = $this->findDeathDate($normalized);
        $data['death_time'] = $this->findDeathTime($normalized);
        $data['death_place'] = $this->findDeathPlace($lines, $normalized);
        $data['cause_of_death'] = $this->findCauseOfDeath($lines, $normalized);
        $data['last_address'] = $this->findLastAddress($lines, $normalized);
        $data['informant_name'] = $this->findInformantName($lines, $normalized);
        $data['doctor_name'] = $this->findDoctorName($lines, $normalized);
        $data['spouse_name'] = $this->findSpouseName($lines, $normalized);
        $data['father_name'] = $this->findFatherName($lines, $normalized);
        $data['mother_name'] = $this->findMotherName($lines, $normalized);
        $data['children_count'] = $this->findChildrenCount($normalized);
        
        // If cause_of_death is null, try direct pattern matching on the raw text
        if (empty($data['cause_of_death']) && !empty($text)) {
            $patterns = [
                '/SEBAB\s*KEMATIAN\s*:?\s*([^\n]+)/i',
                '/CAUSE\s+OF\s+DEATH\s*:?\s*([^\n]+)/i',
                '/Cause\s+of\s+Death\s*:?\s*([^\n]+)/i',
            ];
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $text, $matches)) {
                    $cause = trim($matches[1]);
                    if (!empty($cause) && strlen($cause) > 2) {
                        $data['cause_of_death'] = $cause;
                        break;
                    }
                }
            }
        }
        
        // Clean up extracted data
        $data['deceased_name'] = $this->cleanPersonName($data['deceased_name']);
        $data['informant_name'] = $this->cleanPersonName($data['informant_name']);
        $data['doctor_name'] = $this->cleanPersonName($data['doctor_name']);
        $data['spouse_name'] = $this->cleanPersonName($data['spouse_name']);
        $data['father_name'] = $this->cleanPersonName($data['father_name']);
        $data['mother_name'] = $this->cleanPersonName($data['mother_name']);
        
        $data['last_address'] = $this->cleanAddress($data['last_address']);
        $data['death_place'] = $this->cleanAddress($data['death_place']);
        
        $data['cause_of_death'] = $this->cleanCauseOfDeath($data['cause_of_death']);
        
        if ($data['deceased_nric']) {
            $data['deceased_nric'] = $this->formatNRIC($data['deceased_nric']);
        }
        
        // Calculate confidence
        $confidence = $this->calculateConfidence($data);
        
        // Merge confidence into the result array
        return array_merge($data, ['_confidence' => $confidence]);
    }

    protected function normalize(string $text): string
    {
        // Convert to uppercase for consistent matching
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = mb_strtoupper($text, 'UTF-8');
        
        // Fix common OCR misreads
        $replacements = [
            '|' => '1', 'I' => '1', 'l' => '1', '!' => '1',
            'O' => '0', 'Q' => '0',
            'S' => '5', '$' => '5',
            'Z' => '2', 'B' => '8',
        ];
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);
        
        // Normalize spaces and line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        
        return trim($text);
    }

    // ------------------------------------------------------------------------
    // FIELD FINDER METHODS
    // ------------------------------------------------------------------------

    protected function findCertificateNumber(array $lines, string $text): ?string
    {
        $patterns = [
            '/NO\.?\s*DAFTAR\s*:?\s*([A-Z0-9\s\-]+)/i',
            '/NO\.?\s*SIJIL\s*:?\s*([A-Z0-9\s\-]+)/i',
            '/CERTIFICATE\s*NO\.?\s*:?\s*([A-Z0-9\s\-]+)/i',
            '/REGISTRATION\s*NO\.?\s*:?\s*([A-Z0-9\s\-]+)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $certNo = trim($matches[1]);
                if (!empty($certNo) && strlen($certNo) >= 5) {
                    return $certNo;
                }
            }
        }
        return null;
    }

    protected function findDeceasedName(array $lines, string $text): ?string
    {
        // Method 1: Look for Nama Penuh section
        $inDeceasedSection = false;
        foreach ($lines as $i => $line) {
            if (preg_match('/SI\s*MATI\s*\/\s*DECEASED/i', $line)) {
                $inDeceasedSection = true;
                continue;
            }
            if ($inDeceasedSection && preg_match('/NAMA\s*PENUH/i', $line)) {
                for ($j = $i + 1; $j < min($i + 5, count($lines)); $j++) {
                    $name = trim($lines[$j]);
                    if (!empty($name) && strlen($name) > 5 && !preg_match('/^\d+$/', $name)) {
                        $name = preg_replace('/^(PEN|ALMARHUM|ALMARHUMAH)\s+/i', '', $name);
                        return $name;
                    }
                }
            }
        }
        
        // Method 2: Direct pattern matching
        $patterns = [
            '/NAMA\s*PENUH\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NO\.|\s+JANTINA|\s+UMUR)/i',
            '/NAMA\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NO\.|\s+JANTINA|\s+UMUR)/i',
            '/ALMARHUM\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+BIN|\s+BINTI)/i',
            '/DECEASED\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NRIC|\s+IC)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);
                if (!empty($name) && strlen($name) > 5) {
                    return $name;
                }
            }
        }
        
        return null;
    }

    protected function findNRIC(string $text): ?string
    {
        $patterns = [
            '/\b(\d{6}-\d{2}-\d{4})\b/',
            '/\b(\d{12})\b/',
            '/NO\.?\s*KP\s*:?\s*(\d{6}-?\d{2}-?\d{4})/i',
            '/NRIC\s*:?\s*(\d{6}-?\d{2}-?\d{4})/i',
            '/IC\s*:?\s*(\d{6}-?\d{2}-?\d{4})/i',
            '/NO\.?\s*K\/P\s*:?\s*(\d{6}-?\d{2}-?\d{4})/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    protected function findAge(array $lines, string $text): ?int
    {
        $patterns = [
            '/UMUR\s*:?\s*(\d+)\s*TAHUN/i',
            '/UMUR\s*:?\s*(\d+)/i',
            '/AGE\s*:?\s*(\d+)/i',
            '/(\d+)\s*TAHUN/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $age = (int)$matches[1];
                if ($age > 0 && $age < 150) {
                    return $age;
                }
            }
        }
        return null;
    }

    protected function findGender(string $text): ?string
    {
        if (preg_match('/JANTINA\s*:?\s*([A-Z\s]+)/i', $text, $matches)) {
            $gender = strtoupper(trim($matches[1]));
            if (strpos($gender, 'LELAKI') !== false || strpos($gender, 'MALE') !== false) {
                return 'male';
            }
            if (strpos($gender, 'PEREMPUAN') !== false || strpos($gender, 'FEMALE') !== false) {
                return 'female';
            }
        }
        
        if (preg_match('/\bBIN\b/i', $text)) {
            return 'male';
        }
        if (preg_match('/\bBINTI\b/i', $text)) {
            return 'female';
        }
        
        return null;
    }

    protected function findDeathDate(string $text): ?string
    {
        if (preg_match('/(\d{1,2})\s*HB\.?\s*([A-Z]+)\s*(\d{4})/i', $text, $matches)) {
            return $this->convertMalayDate($matches[1], $matches[2], $matches[3]);
        }
        
        if (preg_match('/(\d{1,2})\s+([A-Z]{3,})\s+(\d{4})/i', $text, $matches)) {
            $month = strtoupper($matches[2]);
            $months = ['JAN', 'FEB', 'MAC', 'APR', 'MEI', 'JUN', 'JUL', 'OGOS', 'SEP', 'OKT', 'NOV', 'DIS'];
            if (in_array(substr($month, 0, 3), $months)) {
                return $this->convertMalayDate($matches[1], $matches[2], $matches[3]);
            }
        }
        
        if (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $text, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        
        if (preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})/', $text, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        
        if (preg_match('/(?:TARIKH\s*KEMATIAN|DATE\s*OF\s*DEATH)\s*:?\s*([^\n]+)/i', $text, $matches)) {
            $dateStr = trim($matches[1]);
            $timestamp = strtotime($dateStr);
            if ($timestamp && $timestamp <= time()) {
                return date('Y-m-d', $timestamp);
            }
        }
        
        return null;
    }

    protected function findDeathTime(string $text): ?string
    {
        $patterns = [
            '/(\d{1,2}:\d{2})\s*(AM|PM)/i',
            '/MASA\s*:?\s*(\d{1,2}:\d{2})/i',
            '/TIME\s*:?\s*(\d{1,2}:\d{2})/i',
            '/(\d{1,2})\s*(PAGI|TENGAH\s*HARI|PETANG|MALAM)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                if (isset($matches[2])) {
                    return $matches[1] . ' ' . $matches[2];
                }
                return $matches[1];
            }
        }
        return null;
    }

    protected function findDeathPlace(array $lines, string $text): ?string
    {
        $inPlaceSection = false;
        $placeParts = [];
        
        foreach ($lines as $line) {
            if (preg_match('/TEMPAT\s*KEMATIAN/i', $line)) {
                $inPlaceSection = true;
                continue;
            }
            if ($inPlaceSection && preg_match('/SEBAB|ALAMAT|KETURUNAN|PENGESAH/i', $line)) {
                break;
            }
            if ($inPlaceSection) {
                $line = trim($line);
                if (!empty($line) && strlen($line) > 3 && 
                    !preg_match('/^RACE\s+/i', $line) &&
                    !preg_match('/^KETURUNAN/i', $line)) {
                    $placeParts[] = $line;
                }
            }
        }
        
        if (!empty($placeParts)) {
            $place = implode(' ', $placeParts);
            $place = preg_replace('/PLACE\s+OF\s+DEATH\s*\.?/i', '', $place);
            return trim($place);
        }
        
        return null;
    }

    protected function findCauseOfDeath(array $lines, string $text): ?string
    {
        $inCauseSection = false;
        
        foreach ($lines as $line) {
            if (preg_match('/SEBAB\s*KEMATIAN/i', $line)) {
                $inCauseSection = true;
                continue;
            }
            if ($inCauseSection && preg_match('/NAMA\s*PENGESAH|DOKTOR/i', $line)) {
                break;
            }
            if ($inCauseSection) {
                $line = trim($line);
                if (!empty($line) && strlen($line) > 2 && !preg_match('/^\d+$/', $line)) {
                    $line = preg_replace('/^SEBAB\s*KEMATIAN\s*:?\s*/i', '', $line);
                    $line = preg_replace('/^CAUSE\s+OF\s+DEATH\s*:?\s*/i', '', $line);
                    return $line;
                }
            }
        }
        
        $patterns = [
            '/SEBAB\s*KEMATIAN\s*:?\s*([^\n]+)/i',
            '/CAUSE\s+OF\s+DEATH\s*:?\s*([^\n]+)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $cause = trim($matches[1]);
                if (!empty($cause) && strlen($cause) > 2) {
                    return $cause;
                }
            }
        }
        
        return null;
    }

    protected function findLastAddress(array $lines, string $text): ?string
    {
        $inAddressSection = false;
        $addressParts = [];
        
        foreach ($lines as $line) {
            if (preg_match('/ALAMAT\s*TERAKHIR/i', $line)) {
                $inAddressSection = true;
                continue;
            }
            if ($inAddressSection) {
                if (preg_match('/SEBAB|KETURUNAN|PENGESAH|PEMBERITAHU/i', $line)) {
                    break;
                }
                $line = trim($line);
                if (!empty($line) && strlen($line) > 2) {
                    $line = preg_replace('/^\d+\s*/', '', $line);
                    if (!empty($line)) {
                        $addressParts[] = $line;
                    }
                }
            }
        }
        
        if (!empty($addressParts)) {
            return implode(' ', $addressParts);
        }
        
        return null;
    }

    protected function findInformantName(array $lines, string $text): ?string
    {
        $inInformantSection = false;
        
        foreach ($lines as $i => $line) {
            if (preg_match('/PEMBERITAHU\s*\/\s*INFORMANT/i', $line)) {
                $inInformantSection = true;
                continue;
            }
            if ($inInformantSection && preg_match('/NAMA/i', $line)) {
                for ($j = $i + 1; $j < min($i + 5, count($lines)); $j++) {
                    $name = trim($lines[$j]);
                    if (!empty($name) && strlen($name) > 5 && !preg_match('/^\d+$/', $name)) {
                        $name = preg_replace('/\d{6}-\d{2}-\d{4}/', '', $name);
                        return $name;
                    }
                }
            }
        }
        
        return null;
    }

    protected function findDoctorName(array $lines, string $text): ?string
    {
        $inDoctorSection = false;
        
        foreach ($lines as $i => $line) {
            if (preg_match('/NAMA\s*PENGESAH/i', $line)) {
                $inDoctorSection = true;
                continue;
            }
            if ($inDoctorSection && preg_match('/NO\.\s*KAD|NO\.\s*KEP/i', $line)) {
                continue;
            }
            if ($inDoctorSection) {
                $line = trim($line);
                if (!empty($line) && strlen($line) > 3 && !preg_match('/^\d+$/', $line)) {
                    return $line;
                }
            }
        }
        
        return null;
    }

    protected function findSpouseName(array $lines, string $text): ?string
    {
        $patterns = [
            '/NAMA\s*PASANGAN\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NO\.|\s+UMUR|\s+JANTINA)/i',
            '/PASANGAN\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+BIN|\s+BINTI)/i',
            '/SUAMI\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+BIN)/i',
            '/ISTERI\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+BINTI)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);
                if (!empty($name) && strlen($name) > 3) {
                    return $name;
                }
            }
        }
        return null;
    }

    protected function findFatherName(array $lines, string $text): ?string
    {
        $patterns = [
            '/NAMA\s*BAPA\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+NAMA\s+IBU|\s+ALAMAT)/i',
            '/BAPA\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+IBU|\s+ALAMAT)/i',
            '/BIN\s+([A-Z][A-Z\s]+)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);
                if (!empty($name) && strlen($name) > 3) {
                    return $name;
                }
            }
        }
        return null;
    }

    protected function findMotherName(array $lines, string $text): ?string
    {
        $patterns = [
            '/NAMA\s*IBU\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+ALAMAT)/i',
            '/IBU\s*:?\s*([A-Z][A-Z\s]+?)(?=\s+ALAMAT)/i',
            '/BINTI\s+([A-Z][A-Z\s]+)/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);
                if (!empty($name) && strlen($name) > 3) {
                    return $name;
                }
            }
        }
        return null;
    }

    protected function findChildrenCount(string $text): ?int
    {
        $patterns = [
            '/BILANGAN\s*ANAK\s*:?\s*(\d+)/i',
            '/CHILDREN\s*:?\s*(\d+)/i',
            '/(\d+)\s*ORANG\s*ANAK/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $count = (int)$matches[1];
                if ($count >= 0 && $count <= 50) {
                    return $count;
                }
            }
        }
        return null;
    }

    // ------------------------------------------------------------------------
    // CLEANING & FORMATTING
    // ------------------------------------------------------------------------

    protected function convertMalayDate(string $day, string $month, string $year): string
    {
        $months = [
            'JANUARI' => 1, 'FEBRUARI' => 2, 'MAC' => 3, 'APRIL' => 4,
            'MEI' => 5, 'JUN' => 6, 'JULAI' => 7, 'OGOS' => 8,
            'SEPTEMBER' => 9, 'OKTOBER' => 10, 'NOVEMBER' => 11, 'DISEMBER' => 12
        ];
        
        $monthUpper = strtoupper(trim($month));
        $monthNum = $months[$monthUpper] ?? 1;
        $dayPadded = str_pad((int)$day, 2, '0', STR_PAD_LEFT);
        
        return sprintf('%04d-%02d-%02d', (int)$year, $monthNum, (int)$dayPadded);
    }

    protected function cleanPersonName(?string $name): ?string
    {
        if (empty($name)) {
            return null;
        }
        
        $name = preg_replace('/[^A-Za-z\s\-\.\']/', '', $name);
        
        $prefixes = ['ALMARHUM', 'ALMARHUMAH', 'ENCIK', 'PUAN', 'CIK', 'TUAN', 'HAJI', 'HAJAH', 'PEN', 'MR', 'MRS', 'MS', 'DR'];
        foreach ($prefixes as $prefix) {
            $name = preg_replace('/^' . $prefix . '\s+/i', '', $name);
        }
        
        $name = preg_replace('/\s+/', ' ', $name);
        $name = ucwords(strtolower(trim($name)));
        
        return $name ?: null;
    }

    protected function cleanAddress(?string $address): ?string
    {
        if (empty($address)) {
            return null;
        }
        
        $address = preg_replace('/^ALAMAT\s*TERAKHIR\s*/i', '', $address);
        $address = preg_replace('/^ALAMAT\s*/i', '', $address);
        $address = preg_replace('/^\d+\s+(?![A-Z])/', '', $address);
        $address = preg_replace('/\s+/', ' ', $address);
        
        return trim($address);
    }

    protected function cleanCauseOfDeath(?string $cause): ?string
    {
        if (empty($cause)) {
            return null;
        }
        
        $fixes = [
            'SAKILIUA' => 'SAKIT TUA',
            'SAKILTUA' => 'SAKIT TUA',
            'SAKITLUA' => 'SAKIT TUA',
            'SAKIT-TUA' => 'SAKIT TUA',
            'KEGAGALAN' => 'KEGAGALAN',
            'JANGKITAN' => 'JANGKITAN',
            'KEMALANGAN' => 'KEMALANGAN',
        ];
        
        $cause = str_replace(array_keys($fixes), array_values($fixes), $cause);
        $cause = preg_replace('/\s+/', ' ', $cause);
        
        return ucwords(strtolower(trim($cause)));
    }

    protected function formatNRIC(string $nric): string
    {
        $nric = preg_replace('/[^0-9]/', '', $nric);
        
        if (strlen($nric) === 12) {
            return substr($nric, 0, 6) . '-' . substr($nric, 6, 2) . '-' . substr($nric, 8, 4);
        }
        
        return $nric;
    }

    /**
     * Calculate confidence score based on successfully extracted fields.
     */
    protected function calculateConfidence(array $data): int
    {
        $score = 0;
        
        if (!empty($data['deceased_name']) && $data['deceased_name'] !== null) {
            $score += 30;
        }
        
        if (!empty($data['deceased_nric']) && $data['deceased_nric'] !== null) {
            $score += 30;
        }
        
        if (!empty($data['cause_of_death']) && $data['cause_of_death'] !== null) {
            $score += 15;
        }
        
        if (!empty($data['death_date']) && $data['death_date'] !== null) {
            $score += 15;
        }
        
        if (!empty($data['death_place']) && $data['death_place'] !== null) {
            $score += 5;
        }
        
        if (!empty($data['gender']) && $data['gender'] !== null) {
            $score += 5;
        }
        
        $filledCount = 0;
        foreach ($data as $key => $value) {
            if (!empty($value) && !in_array($key, ['_confidence', '_raw_text'])) {
                $filledCount++;
            }
        }
        
        if ($filledCount >= 5) {
            $score = min(100, $score + 10);
        }
        
        if (!empty($data['deceased_name'])) {
            $score = max($score, 20);
        }
        
        return min(100, $score);
    }

    // ------------------------------------------------------------------------
    // UTILITY
    // ------------------------------------------------------------------------

    protected function saveRawText(string $filePath, string $text): void
    {
        try {
            Storage::disk('private')->put($filePath . '.ocr.txt', $text);
        } catch (\Exception $e) {
            // Silently fail – not critical
        }
    }

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

    protected function findTesseractPath(): string
    {
        $paths = [
            'C:\PROGRA~1\Tesseract-OCR\tesseract.exe',
            'C:\Program Files\Tesseract-OCR\tesseract.exe',
            'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe',
        ];
        
        $which = shell_exec('where tesseract 2>nul');
        if ($which && trim($which)) {
            return trim($which);
        }
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return 'C:\Program Files\Tesseract-OCR\tesseract.exe';
    }

    protected function findImageMagickPath(): ?string
    {
        $paths = [
            'C:\PROGRA~1\ImageMagick-7.1.2-Q16-HDRI\magick.exe',
            'C:\Program Files\ImageMagick-7.1.2-Q16-HDRI\magick.exe',
        ];
        
        $which = shell_exec('where magick 2>nul');
        if ($which && trim($which)) {
            return trim($which);
        }
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
}