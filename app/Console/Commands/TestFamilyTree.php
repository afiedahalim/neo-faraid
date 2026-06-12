<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\FamilyTreeService;

class TestFamilyTree extends Command
{
    protected $signature = 'familytree:test';
    protected $description = 'Test Family Tree generation';

    public function handle()
    {
        $this->info('Testing Family Tree generation...');
        
        // Test data similar to your tinker test
        $deceasedData = [
            'name' => 'Test Deceased',
            'gender' => 'male'
        ];
        
        $heirsData = [
            'wife_count' => 1,
            'husband_count' => 0,
            'father_status' => 'alive',
            'mother_status' => 'alive',
            'son_count' => 2,
            'daughter_count' => 1,
        ];
        
        $distributionResults = [
            [
                'heir' => 'Wife',
                'relationship' => 'wife',
                'share' => '1/8',
                'amount' => 10250,
                'fraction' => 0.125
            ],
            [
                'heir' => 'Father',
                'relationship' => 'father',
                'share' => '1/6',
                'amount' => 13666.67,
                'fraction' => 0.1667
            ],
            [
                'heir' => 'Mother',
                'relationship' => 'mother',
                'share' => '1/6',
                'amount' => 13666.67,
                'fraction' => 0.1667
            ],
            [
                'heir' => 'Son 1',
                'relationship' => 'son',
                'share' => 'Residual',
                'amount' => 22208.33,
                'fraction' => 0.2708
            ],
            [
                'heir' => 'Son 2',
                'relationship' => 'son',
                'share' => 'Residual',
                'amount' => 22208.33,
                'fraction' => 0.2708
            ],
        ];
        
        $this->info('Test Data:');
        $this->line('- Deceased: ' . $deceasedData['name']);
        $this->line('- Gender: ' . $deceasedData['gender']);
        $this->line('- Wife Count: ' . $heirsData['wife_count']);
        $this->line('- Father Status: ' . $heirsData['father_status']);
        $this->line('- Mother Status: ' . $heirsData['mother_status']);
        $this->line('- Son Count: ' . $heirsData['son_count']);
        $this->line('- Daughter Count: ' . $heirsData['daughter_count']);
        
        $this->newLine();
        $this->info('Creating FamilyTreeService instance...');
        
        try {
            $familyTreeService = new FamilyTreeService(
                $deceasedData,
                $heirsData,
                $distributionResults
            );
            
            $this->info('Generating family tree...');
            
            $filename = 'test_tree_' . time();
            $result = $familyTreeService->generate($filename);
            
            if ($result) {
                $this->info('✓ Family tree generated successfully!');
                $this->line('URL: ' . $result);
                
                // Check file type
                if (strpos($result, '.svg') !== false) {
                    $this->info('Format: SVG (Graphviz)');
                } elseif (strpos($result, '.html') !== false) {
                    $this->info('Format: HTML (Fallback)');
                }
                
                // Extract path from URL
                $path = parse_url($result, PHP_URL_PATH);
                $storagePath = str_replace('/storage/', 'app/public/', $path);
                $fullPath = storage_path($storagePath);
                
                if (file_exists($fullPath)) {
                    $this->info('File saved at: ' . $fullPath);
                    $this->info('File size: ' . filesize($fullPath) . ' bytes');
                    
                    // Try to open the file
                    if (strpos($result, '.svg') !== false) {
                        $content = file_get_contents($fullPath);
                        if (strpos($content, '<svg') !== false) {
                            $this->info('✓ SVG file is valid');
                        } else {
                            $this->error('✗ SVG file is invalid - no SVG tag found');
                        }
                    }
                } else {
                    $this->error('✗ File not found at expected location');
                }
            } else {
                $this->error('✗ Family tree generation failed');
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            $this->line('Trace: ' . $e->getTraceAsString());
            
            Log::error('Family tree test failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
}