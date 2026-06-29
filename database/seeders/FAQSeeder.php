<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What is the Neo Faraid Calculator?',
                'answer' => 'The Neo Faraid Calculator is a digital tool that calculates Islamic inheritance (Faraid) automatically based on user input such as heirs, assets, and liabilities. It provides accurate, Shariah-compliant results along with a visual family tree.',
                'category' => 'gettingStarted', // Changed from 'Getting started'
                'order' => 1,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Learn about the Neo Faraid Calculator - a digital tool for Islamic inheritance calculations',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Is the calculator Shariah-compliant?',
                'answer' => 'Yes. The system follows the principles and rules of Islamic inheritance based on the Quran, Sunnah, and recognized Faraid methodologies. Our calculations are verified by Islamic scholars to ensure compliance.',
                'category' => 'gettingStarted', // Changed from 'Getting started'
                'order' => 2,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about Shariah compliance of the Neo Faraid Calculator',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Who can use this calculator?',
                'answer' => 'Anyone can use it, including individuals, families, students, researchers, educators, and legal practitioners. The interface is designed to be user-friendly for both beginners and experts.',
                'category' => 'gettingStarted', // Changed from 'Getting started'
                'order' => 3,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Details about who can use the Neo Faraid Calculator',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'How accurate are the results?',
                'answer' => 'Calculations are generated using established Faraid rules. For complex cases, we recommend verifying with a certified Faraid expert. Our system is regularly updated to reflect the latest scholarly consensus.',
                'category' => 'calculations', // Changed from 'Calculations'
                'order' => 4,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about accuracy of Faraid calculations',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What information do I need to enter?',
                'answer' => 'You need to provide: deceased\'s details, list of heirs (alive or deceased), total assets, debts, funeral costs, and optional wasiyyah/liabilities. The system guides you through each step.',
                'category' => 'calculations', // Changed from 'Calculations'
                'order' => 5,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'List of required information for Faraid calculation',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Support for predeceased heirs with grandchildren?',
                'answer' => 'Yes. Mark heir as deceased, enter surviving children; appropriate substitution rules apply automatically. The system handles complex family structures.',
                'category' => 'calculations', // Changed from 'Calculations'
                'order' => 6,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about handling predeceased heirs and grandchildren',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Is my data stored or shared?',
                'answer' => 'Your data is confidential and used only for calculation purposes unless you choose to save or export. We use bank-level encryption and do not share data with third parties.',
                'category' => 'securityPrivacy', // Changed from 'Security privacy'
                'order' => 7,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about data security and privacy',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Can I export the results?',
                'answer' => 'Yes. You can save the report, export as PDF, or download the distribution breakdown. All exports include detailed calculations and family tree visualization.',
                'category' => 'calculations', // Changed from 'Calculations'
                'order' => 8,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about exporting calculation results',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Does this replace legal or religious consultation?',
                'answer' => 'No. The calculator aids understanding but does not replace official advice. Consult certified authorities for official cases. Our tool is for educational and planning purposes.',
                'category' => 'gettingStarted', // Changed from 'Getting started'
                'order' => 9,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Clarification on the role of the calculator vs professional advice',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Is the family tree generated automatically?',
                'answer' => 'Yes. A visual family tree is automatically created from the heirs you enter. It helps visualize relationships and inheritance distribution clearly.',
                'category' => 'calculations', // Changed from 'Calculations'
                'order' => 10,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about automatic family tree generation',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Is there a mobile app available?',
                'answer' => 'Currently, Neo Faraid is available as a web application that works perfectly on all mobile devices. We are developing native mobile apps for iOS and Android.',
                'category' => 'gettingStarted', // Changed from 'Getting started'
                'order' => 11,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about mobile app availability',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What if I make a mistake in my input?',
                'answer' => 'You can easily edit any information at any step before finalizing. The system also has validation checks to prevent common input errors.',
                'category' => 'calculations', // Changed from 'Calculations'
                'order' => 12,
                'is_published' => true,
                'is_verified' => true,
                'meta_description' => 'Information about correcting input mistakes',
                'read_time' => '2 min read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert FAQs into database
        DB::table('faqs')->insert($faqs);

        $this->command->info('12 FAQ entries added successfully!');
    }
}