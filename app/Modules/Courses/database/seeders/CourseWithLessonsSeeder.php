<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\Lesson;
use Illuminate\Support\Str;

class CourseWithLessonsSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Digital Marketing for MSMEs',
                'description' => 'Learn how to market your business online.',
                'estimated_minutes' => 120,
                'lessons' => [
                    ['title' => 'Introduction to Digital Marketing', 'content' => 'Basics of online marketing'],
                    ['title' => 'Social Media Strategy', 'content' => 'Using Facebook and Instagram effectively'],
                    ['title' => 'Content Creation', 'content' => 'Creating engaging posts'],
                ],
            ],
            [
                'title' => 'Basic Financial Management',
                'description' => 'Manage your business finances better.',
                'estimated_minutes' => 90,
                'lessons' => [
                    ['title' => 'Understanding Cash Flow', 'content' => 'Tracking income and expenses'],
                    ['title' => 'Budgeting for MSMEs', 'content' => 'Setting realistic budgets'],
                    ['title' => 'Profit vs Revenue', 'content' => 'Key financial concepts'],
                ],
            ],
            [
                'title' => 'Customer Service Essentials',
                'description' => 'Improve your customer handling skills.',
                'estimated_minutes' => 80,
                'lessons' => [
                    ['title' => 'Active Listening', 'content' => 'Understanding customer needs'],
                    ['title' => 'Handling Complaints', 'content' => 'De-escalation techniques'],
                    ['title' => 'Building Loyalty', 'content' => 'Keeping customers happy'],
                ],
            ],
            [
                'title' => 'E-commerce Basics',
                'description' => 'Sell your products online.',
                'estimated_minutes' => 110,
                'lessons' => [
                    ['title' => 'Choosing a Platform', 'content' => 'Shopee, Lazada, Shopify'],
                    ['title' => 'Product Listing Tips', 'content' => 'Good photos and descriptions'],
                    ['title' => 'Order Fulfillment', 'content' => 'Packaging and delivery'],
                ],
            ],
            [
                'title' => 'Business Registration Guide',
                'description' => 'How to legally register your MSME in the Philippines.',
                'estimated_minutes' => 100,
                'lessons' => [
                    ['title' => 'DTI Registration', 'content' => 'Single proprietorship steps'],
                    ['title' => 'Barangay Clearance', 'content' => 'Local permits'],
                    ['title' => 'Mayor’s Permit', 'content' => 'City compliance'],
                ],
            ],
        ];

        foreach ($courses as $index => $data) {

            $course = Course::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => 'published',
                'estimated_minutes' => $data['estimated_minutes'],
                'slug' => Str::slug($data['title']),
            ]);

            foreach ($data['lessons'] as $order => $lesson) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => $lesson['title'],
                    'content' => $lesson['content'],
                    'slug' => Str::slug($lesson['title']),
                    'order_index' => $order,
                ]);
            }
        }
    }
}
