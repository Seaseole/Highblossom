<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories for posts (idempotent - use firstOrCreate)
        $categoryData = [
            ['name' => 'Company News', 'slug' => 'company-news'],
            ['name' => 'Services', 'slug' => 'services'],
            ['name' => 'Tips & Advice', 'slug' => 'tips-advice'],
            ['name' => 'Industry Updates', 'slug' => 'industry-updates'],
            ['name' => 'Success Stories', 'slug' => 'success-stories'],
        ];

        foreach ($categoryData as $data) {
            Category::firstOrCreate(
                ['slug' => $data['slug'], 'type' => 'post'],
                array_merge($data, ['type' => 'post'])
            );
        }
        $categories = Category::forPosts()->get();

        // Create tags (idempotent - use firstOrCreate)
        $tagNames = ['Maintenance', 'Repair', 'Installation', 'Safety', 'Technology', 'Sustainability', 'Efficiency', 'Commercial', 'Residential', 'Industrial'];
        foreach ($tagNames as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
        $tags = Tag::all();

        // Get first user as author, or create one if none exists
        $author = User::first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@highblossom.co.bw',
        ]);

        // Create sample posts with blocks
        $posts = [
            [
                'title' => 'Welcome to Our New Blog',
                'category' => 'Company News',
                'featured' => true,
                'blocks' => [
                    ['type' => 'hero', 'content' => [
                        'heading' => 'Welcome to Our New Blog',
                        'subheading' => 'Stay updated with the latest news, tips, and insights from our team.',
                        'image' => 'https://picsum.photos/seed/welcome/1200/600',
                        'cta_text' => 'Explore Articles',
                        'cta_url' => '/blog',
                        'alignment' => 'center',
                    ]],
                    ['type' => 'rich-text', 'content' => [
                        'content' => '<p>We are excited to launch our new blog! Here you will find valuable information about our services, industry insights, maintenance tips, and company updates.</p><p>Our team of experts will be sharing their knowledge and experience to help you make informed decisions about your maintenance and repair needs.</p>',
                    ]],
                    ['type' => 'spacer', 'content' => ['height' => 'medium']],
                    ['type' => 'quote', 'content' => [
                        'quote' => 'Quality service is not an act, it is a habit. We strive to deliver excellence in every project we undertake.',
                        'author' => 'Highblossom Team',
                        'title' => 'Company Values',
                        'style' => 'large',
                    ]],
                ],
            ],
            [
                'title' => '5 Essential Maintenance Tips for Commercial Properties',
                'category' => 'Tips & Advice',
                'featured' => false,
                'blocks' => [
                    ['type' => 'image', 'content' => [
                        'image' => 'https://picsum.photos/seed/maintenance/800/400',
                        'alt' => 'Commercial property maintenance',
                        'caption' => 'Regular maintenance keeps your property in top condition',
                        'alignment' => 'full',
                    ]],
                    ['type' => 'rich-text', 'content' => [
                        'content' => '<p>Maintaining a commercial property requires consistent effort and attention to detail. Here are five essential tips to keep your property in excellent condition:</p><h3>1. Regular Inspections</h3><p>Schedule monthly inspections to identify potential issues before they become major problems. Check plumbing, electrical systems, HVAC, and structural elements.</p><h3>2. Preventive Maintenance Schedule</h3><p>Create a preventive maintenance calendar that includes seasonal tasks like gutter cleaning, HVAC filter replacement, and exterior painting.</p><h3>3. Emergency Preparedness</h3><p>Have a plan in place for emergencies. Keep contact information for reliable contractors and maintenance services readily available.</p><h3>4. Document Everything</h3><p>Maintain detailed records of all maintenance activities, repairs, and inspections. This documentation is valuable for warranty claims and resale.</p><h3>5. Professional Partnerships</h3><p>Build relationships with trusted maintenance professionals who can provide quality service when you need it most.</p>',
                    ]],
                ],
            ],
            [
                'title' => 'Industry Trends: The Future of Facility Management',
                'category' => 'Industry Updates',
                'featured' => true,
                'blocks' => [
                    ['type' => 'hero', 'content' => [
                        'heading' => 'The Future of Facility Management',
                        'subheading' => 'Technology and sustainability are reshaping how we maintain commercial spaces.',
                        'image' => 'https://picsum.photos/seed/future/1200/600',
                        'cta_text' => 'Learn More',
                        'cta_url' => '#',
                        'alignment' => 'left',
                    ]],
                    ['type' => 'rich-text', 'content' => [
                        'content' => '<p>The facility management industry is undergoing a significant transformation. Smart building technology, IoT sensors, and predictive maintenance are becoming standard practices.</p><p>These innovations help property managers:</p><ul><li>Reduce operational costs</li><li>Improve energy efficiency</li><li>Extend equipment lifespan</li><li>Enhance occupant comfort</li><li>Meet sustainability goals</li></ul><p>Staying ahead of these trends is essential for modern facility management.</p>',
                    ]],
                    ['type' => 'spacer', 'content' => ['height' => 'small']],
                    ['type' => 'image', 'content' => [
                        'image' => 'https://picsum.photos/seed/technology/600/400',
                        'alt' => 'Smart building technology',
                        'alignment' => 'center',
                    ]],
                ],
            ],
            [
                'title' => 'Success Story: Major Renovation Project Completed',
                'category' => 'Success Stories',
                'featured' => false,
                'blocks' => [
                    ['type' => 'rich-text', 'content' => [
                        'content' => '<p>We recently completed a major renovation project for a valued client in Gaborone. The project involved comprehensive repairs, painting, and system upgrades.</p>',
                    ]],
                    ['type' => 'quote', 'content' => [
                        'quote' => 'The team at Highblossom exceeded our expectations. Their professionalism and attention to detail transformed our facility.',
                        'author' => 'Client Testimonial',
                        'title' => 'Satisfied Customer',
                        'style' => 'testimonial',
                    ]],
                    ['type' => 'image', 'content' => [
                        'image' => 'https://picsum.photos/seed/project/800/500',
                        'alt' => 'Completed renovation project',
                        'caption' => 'The completed project exceeded client expectations',
                        'alignment' => 'full',
                    ]],
                    ['type' => 'spacer', 'content' => ['height' => 'medium']],
                    ['type' => 'rich-text', 'content' => [
                        'content' => '<p>Key achievements of this project included:</p><ul><li>Completed 2 weeks ahead of schedule</li><li>15% under budget</li><li>Zero safety incidents</li><li>98% client satisfaction rating</li></ul><p>We are proud of our team\'s dedication to delivering excellence on every project.</p>',
                    ]],
                ],
            ],
            [
                'title' => 'Understanding Our Painting Services',
                'category' => 'Services',
                'featured' => false,
                'blocks' => [
                    ['type' => 'hero', 'content' => [
                        'heading' => 'Professional Painting Services',
                        'subheading' => 'Transform your space with our expert painting solutions.',
                        'image' => 'https://picsum.photos/seed/painting/1200/600',
                        'cta_text' => 'Get a Quote',
                        'cta_url' => '/quote',
                        'alignment' => 'center',
                    ]],
                    ['type' => 'rich-text', 'content' => [
                        'content' => '<p>Our painting services cover both interior and exterior applications for residential, commercial, and industrial properties. We use premium paints and proven techniques to ensure lasting results.</p><h3>Our Process</h3><ol><li><strong>Consultation:</strong> We assess your needs and provide color recommendations.</li><li><strong>Preparation:</strong> Surfaces are properly cleaned, repaired, and primed.</li><li><strong>Application:</strong> Expert painters apply paint with precision and care.</li><li><strong>Inspection:</strong> We ensure quality results before project completion.</li></ol>',
                    ]],
                    ['type' => 'spacer', 'content' => ['height' => 'large']],
                    ['type' => 'quote', 'content' => [
                        'quote' => 'A fresh coat of paint can completely transform a space. We bring expertise and quality to every brushstroke.',
                        'author' => 'Lead Painter',
                        'style' => 'default',
                    ]],
                ],
            ],
        ];

        foreach ($posts as $postData) {
            $category = $categories->firstWhere('name', $postData['category']);
            $slug = Str::slug($postData['title']);

            // Skip if post already exists
            if (Post::where('slug', $slug)->exists()) {
                continue;
            }

            $post = Post::factory()
                ->published()
                ->create([
                    'title' => $postData['title'],
                    'slug' => $slug,
                    'category_id' => $category?->id,
                    'author_id' => $author->id,
                    'is_featured' => $postData['featured'],
                    'featured_image' => $postData['blocks'][0]['content']['image'] ?? null,
                ]);

            // Attach random tags
            $post->tags()->attach($tags->random(rand(2, 4))->pluck('id'));

            // Add content blocks
            $sortOrder = 0;
            foreach ($postData['blocks'] as $blockData) {
                $post->contentBlocks()->create([
                    'type' => $blockData['type'],
                    'content' => $blockData['content'],
                    'sort_order' => $sortOrder++,
                    'is_visible' => true,
                ]);
            }
        }

        // Create a few draft posts
        Post::factory()
            ->count(3)
            ->draft()
            ->create([
                'author_id' => $author->id,
                'category_id' => $categories->random()->id,
            ])
            ->each(function ($post) use ($tags) {
                $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
            });

        $this->command->info('Blog seeded successfully!');
        $this->command->info("Created: {$categories->count()} categories, {$tags->count()} tags, " . (count($posts) + 3) . " posts");
    }
}
