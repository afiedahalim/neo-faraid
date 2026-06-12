<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Calculation;
use App\Services\FamilyTreeService;

echo "=== FINAL FAMILY TREE SYSTEM TEST ===\n\n";

// Test Case 1: Standard family with all heirs
echo "TEST CASE 1: Standard Family\n";
echo "=============================\n";

$testData1 = [
    'deceased' => ['name' => 'Ahmad bin Abdullah', 'gender' => 'male'],
    'heirs' => [
        'wife_count' => 1,
        'father_status' => 'alive',
        'mother_status' => 'alive',
        'son_count' => 2,
        'daughter_count' => 1,
    ],
    'distribution' => [
        ['heir' => 'Wife', 'relationship' => 'wife', 'share' => '1/8', 'amount' => 26250],
        ['heir' => 'Father', 'relationship' => 'father', 'share' => '1/6', 'amount' => 35000],
        ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/6', 'amount' => 35000],
        ['heir' => 'Son 1', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 56875],
        ['heir' => 'Son 2', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 56875],
        ['heir' => 'Daughter', 'relationship' => 'daughter', 'share' => '1/2 of son', 'amount' => 28437.50],
    ]
];

runTest($testData1, 'test1_standard');

// Test Case 2: Female deceased with husband
echo "\n\nTEST CASE 2: Female Deceased with Husband\n";
echo "=========================================\n";

$testData2 = [
    'deceased' => ['name' => 'Siti binti Ali', 'gender' => 'female'],
    'heirs' => [
        'wife_count' => 0,
        'husband_count' => 1,
        'father_status' => 'alive',
        'mother_status' => 'alive',
        'son_count' => 1,
        'daughter_count' => 2,
    ],
    'distribution' => [
        ['heir' => 'Husband', 'relationship' => 'husband', 'share' => '1/4', 'amount' => 50000],
        ['heir' => 'Father', 'relationship' => 'father', 'share' => '1/6', 'amount' => 33333.33],
        ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/6', 'amount' => 33333.33],
        ['heir' => 'Son', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 50000],
        ['heir' => 'Daughter 1', 'relationship' => 'daughter', 'share' => '1/2 of son', 'amount' => 25000],
        ['heir' => 'Daughter 2', 'relationship' => 'daughter', 'share' => '1/2 of son', 'amount' => 25000],
    ]
];

runTest($testData2, 'test2_female');

// Test Case 3: Multiple wives
echo "\n\nTEST CASE 3: Multiple Wives\n";
echo "============================\n";

$testData3 = [
    'deceased' => ['name' => 'Mohammed bin Yusuf', 'gender' => 'male'],
    'heirs' => [
        'wife_count' => 3,
        'father_status' => 'deceased',
        'mother_status' => 'alive',
        'son_count' => 1,
    ],
    'distribution' => [
        ['heir' => 'Wife 1', 'relationship' => 'wife', 'share' => '1/8 shared', 'amount' => 10000],
        ['heir' => 'Wife 2', 'relationship' => 'wife', 'share' => '1/8 shared', 'amount' => 10000],
        ['heir' => 'Wife 3', 'relationship' => 'wife', 'share' => '1/8 shared', 'amount' => 10000],
        ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/6', 'amount' => 13333.33],
        ['heir' => 'Son', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 46666.67],
    ]
];

runTest($testData3, 'test3_multiple_wives');

// Test Case 4: No children
echo "\n\nTEST CASE 4: No Children\n";
echo "=========================\n";

$testData4 = [
    'deceased' => ['name' => 'Abdul Rahman', 'gender' => 'male'],
    'heirs' => [
        'wife_count' => 1,
        'father_status' => 'alive',
        'mother_status' => 'alive',
        'son_count' => 0,
        'daughter_count' => 0,
    ],
    'distribution' => [
        ['heir' => 'Wife', 'relationship' => 'wife', 'share' => '1/4', 'amount' => 25000],
        ['heir' => 'Father', 'relationship' => 'father', 'share' => 'Residual', 'amount' => 50000],
        ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/3 of residual', 'amount' => 25000],
    ]
];

runTest($testData4, 'test4_no_children');

echo "\n\n=== TEST COMPLETED ===\n";
echo "All family trees should be accessible at:\n";
echo "http://neo-faraid.test/storage/family_trees/\n";

function runTest($testData, $prefix) {
    echo "Deceased: {$testData['deceased']['name']}\n";
    echo "Gender: {$testData['deceased']['gender']}\n";
    echo "Heirs: ";
    foreach ($testData['heirs'] as $key => $value) {
        if ($value) {
            echo "$key: $value, ";
        }
    }
    echo "\n";
    
    try {
        $service = new FamilyTreeService(
            $testData['deceased'],
            $testData['heirs'],
            $testData['distribution']
        );
        
        $filename = $prefix . '_' . time();
        $result = $service->generate($filename);
        
        if ($result) {
            echo "✓ Generated: {$result}\n";
            
            // Extract path and verify file
            $path = parse_url($result, PHP_URL_PATH);
            $storagePath = str_replace('/storage/', 'app/public/', $path);
            $fullPath = storage_path($storagePath);
            
            if (file_exists($fullPath)) {
                echo "✓ File saved: " . basename($fullPath) . "\n";
                echo "✓ File size: " . filesize($fullPath) . " bytes\n";
                
                // Check content
                $content = file_get_contents($fullPath);
                if (strpos($result, '.svg') !== false) {
                    echo "✓ Format: SVG\n";
                    
                    // Verify SVG structure
                    if (strpos($content, 'Deceased') !== false) {
                        echo "✓ Contains deceased information\n";
                    }
                    
                    // Check for heirs
                    $heirsFound = 0;
                    foreach ($testData['distribution'] as $heir) {
                        if (strpos($content, $heir['heir']) !== false) {
                            $heirsFound++;
                        }
                    }
                    echo "✓ Found {$heirsFound} heirs in SVG\n";
                }
            }
        } else {
            echo "✗ Generation failed\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}