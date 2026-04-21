<?php

namespace Database\Seeders;

use App\Domains\Content\Models\CompanySetting;
use App\Domains\Content\Models\GalleryImage;
use App\Domains\Content\Models\Service;
use App\Domains\Content\Models\Testimonial;
use Illuminate\Database\Seeder;

class CompanyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCompanySettings();
        $this->seedTestimonials();
        $this->seedServices();
    }

    private function seedCompanySettings(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'Highblossom PTY LTD', 'type' => 'text'],
            ['key' => 'logo_text', 'value' => 'Highblossom PTY LTD', 'type' => 'text'],
            ['key' => 'primary_phone', 'value' => '+2673117480', 'type' => 'text'],
            ['key' => 'whatsapp_number', 'value' => '+2677773262', 'type' => 'text'],
            ['key' => 'primary_email', 'value' => 'sales@highblossom.net', 'type' => 'text'],
            ['key' => 'address', 'value' => 'Plot 123, Main Road, Broadhurst, Gaborone, Botswana', 'type' => 'text'],
            ['key' => 'smtp_host', 'value' => '', 'type' => 'text'],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'number'],
            ['key' => 'smtp_username', 'value' => '', 'type' => 'text'],
            ['key' => 'smtp_password', 'value' => '', 'type' => 'text'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            CompanySetting::set($setting['key'], $setting['value'], $setting['type']);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Thabo Molefe',
                'company' => 'Operations Manager, Local Mining Co.',
                'rating' => 5,
                'comment' => 'Highblossom handled my mining fleet glass replacements with incredible speed. The on-time delivery isn\'t just a marketing slogan; it\'s their standard. Exceptional service in Gaborone.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sarah Johnson',
                'company' => 'Fleet Manager, Express Logistics',
                'rating' => 5,
                'comment' => 'We\'ve been using Highblossom for our entire fleet for over 3 years. Their mobile service saves us downtime and their quality is unmatched.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'David Kgosi',
                'company' => 'Construction Site Supervisor',
                'rating' => 5,
                'comment' => 'When our excavator\'s cabin glass was damaged, Highblossom came to our site and replaced it the same day. Professional and efficient!',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }

    private function seedServices(): void
    {
        $services = [
            [
                'title' => 'Windscreen Repair & Replacement',
                'slug' => 'windscreen-repair-replacement',
                'icon' => 'directions_car',
                'short_description' => 'OEM-quality windscreen installations for all vehicle makes and models.',
                'full_description' => 'We provide professional windscreen repair and replacement services for all vehicle types. Our technicians use OEM-quality glass and ensure proper ADAS calibration for modern vehicles.',
                'features' => ['Same-day service available', 'OEM and aftermarket options', 'ADAS calibration included'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Side & Rear Window Glass',
                'slug' => 'side-rear-window-glass',
                'icon' => 'sensor_window',
                'short_description' => 'Full replacement of tempered and laminated side windows.',
                'full_description' => 'Complete replacement services for side windows and rear windshields. We handle all glass types including tempered safety glass and heated rear screens.',
                'features' => ['Tempered safety glass', 'Heated rear screen options', 'Privacy tinting available'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Heavy Machinery Glass',
                'slug' => 'heavy-machinery-glass',
                'icon' => 'agriculture',
                'short_description' => 'Specialized toughened glass for mining and construction equipment.',
                'full_description' => 'Expert glass solutions for heavy machinery including excavators, bulldozers, cranes, and agricultural equipment. Custom cuts for any cabin or enclosure.',
                'features' => ['Excavators & bulldozers', 'Cranes & lift equipment', 'Agricultural machinery'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Fleet Services',
                'slug' => 'fleet-services',
                'icon' => 'local_shipping',
                'short_description' => 'Dedicated fleet maintenance programs with volume discounts.',
                'full_description' => 'Comprehensive fleet maintenance programs for commercial vehicle operators. We offer volume discounts, priority scheduling, and dedicated account management.',
                'features' => ['Volume pricing', 'Priority scheduling', 'Account management'],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Mobile Glass Service',
                'slug' => 'mobile-glass-service',
                'icon' => 'home_repair_service',
                'short_description' => 'On-site replacement service throughout Gaborone area.',
                'full_description' => 'Can\'t come to us? We\'ll come to you. Our mobile units serve Gaborone and surrounding areas with full replacement capabilities and same-day service.',
                'features' => ['On-site replacement', 'Gaborone metro area', 'Same-day service'],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Emergency Repairs',
                'slug' => 'emergency-repairs',
                'icon' => 'emergency',
                'short_description' => '24/7 emergency response for urgent glass repairs.',
                'full_description' => 'Broken glass can\'t wait. Our emergency response team provides rapid assessment and temporary or permanent solutions for all vehicle types.',
                'features' => ['24/7 availability', 'Rapid response', 'Temporary solutions'],
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
