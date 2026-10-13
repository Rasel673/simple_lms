<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Slider;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Students
        $admin = User::firstOrCreate(
            ['email' => 'admin@lms.test'],
            [
                'name' => 'LMS Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+880 1711 000001',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@lms.test'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '+880 1722 000002',
            ]
        );

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Build robust and modern web applications with cutting-edge technologies.',
                'image' => null,
            ],
            [
                'name' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'description' => 'Create cross-platform iOS and Android mobile apps from scratch.',
                'image' => null,
            ],
            [
                'name' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'description' => 'Master wireframing, prototyping, and modern design systems in Figma.',
                'image' => null,
            ],
            [
                'name' => 'Data Science & AI',
                'slug' => 'data-science-ai',
                'description' => 'Harness data analysis, machine learning algorithms, and intelligent systems.',
                'image' => null,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Courses
        $webCat = $categories['web-development'];
        $course1 = Course::firstOrCreate(
            ['slug' => 'complete-laravel-masterclass'],
            [
                'category_id' => $webCat->id,
                'title' => 'Complete Laravel & Modern PHP Masterclass',
                'slug' => 'complete-laravel-masterclass',
                'short_description' => 'Master modern Laravel, service architecture, Eloquent ORM, and payment integration from scratch.',
                'description' => "<p>Welcome to the <strong>Complete Laravel & Modern PHP Masterclass</strong>! In this in-depth course, you will learn to build enterprise-grade web applications with Laravel.</p><p>We will cover architectural design, MVC principles, clean service layers, secure authentication, database migrations, and real-world payment gateway integrations.</p>",
                'price' => 3500.00,
                'discount_price' => 2450.00,
                'duration' => '14 Hours',
                'passing_score' => 70,
                'learning_outcomes' => [
                    'Architect production-grade web applications using the Laravel Service Pattern.',
                    'Deep dive into Eloquent ORM relationships, scopes, and migrations.',
                    'Implement secure authentication, role-based authorization, and custom middleware.',
                    'Integrate payment gateways such as SSLCommerz and process webhook callbacks.',
                    'Generate automated verified PDF completion certificates.',
                ],
                'status' => 'published',
                'meta_title' => 'Complete Laravel & Modern PHP Masterclass | LearnHub',
                'meta_description' => 'Learn Laravel from scratch to advanced. Build real-world projects and earn a verified certificate.',
            ]
        );

        $course2 = Course::firstOrCreate(
            ['slug' => 'full-stack-javascript-vue-node'],
            [
                'category_id' => $webCat->id,
                'title' => 'Full-Stack JavaScript: Modern Frontends & APIs',
                'slug' => 'full-stack-javascript-vue-node',
                'short_description' => 'Learn modern JavaScript, asynchronous programming, RESTful APIs, and responsive UI components.',
                'description' => "<p>Take your JavaScript skills to professional level. This course guides you step-by-step through ES6+, reactive components, state management, and API design.</p>",
                'price' => 2800.00,
                'discount_price' => 1999.00,
                'duration' => '10 Hours',
                'passing_score' => 70,
                'learning_outcomes' => [
                    'Write clean, modular, and maintainable modern JavaScript code.',
                    'Understand async/await, Promises, and AJAX HTTP communications.',
                    'Build interactive client-side components with Alpine.js and Vue.',
                    'Deploy secure and high-performance web applications.',
                ],
                'status' => 'published',
                'meta_title' => 'Full-Stack JavaScript Course | LearnHub',
                'meta_description' => 'Master full stack JavaScript development with real-world practice projects.',
            ]
        );

        $mobileCat = $categories['mobile-app-development'];
        $course3 = Course::firstOrCreate(
            ['slug' => 'flutter-dart-mobile-development'],
            [
                'category_id' => $mobileCat->id,
                'title' => 'Flutter & Dart: Cross-Platform iOS & Android Apps',
                'slug' => 'flutter-dart-mobile-development',
                'short_description' => 'Build gorgeous native-speed mobile applications for iOS and Android using Flutter 3.',
                'description' => "<p>Discover how to create responsive cross-platform apps with Flutter and Dart. Build real mobile interfaces, handle local storage, and connect to remote backends.</p>",
                'price' => 4200.00,
                'discount_price' => 3150.00,
                'duration' => '18 Hours',
                'passing_score' => 75,
                'learning_outcomes' => [
                    'Master Dart syntax, object-oriented concepts, and Flutter widget architecture.',
                    'Build responsive UIs that look native on iOS and Android.',
                    'Manage state efficiently using Provider and Bloc patterns.',
                    'Publish apps to the Google Play Store and Apple App Store.',
                ],
                'status' => 'published',
                'meta_title' => 'Flutter & Dart Mobile App Course | LearnHub',
                'meta_description' => 'Learn Flutter 3 and Dart to build professional cross-platform mobile apps.',
            ]
        );

        $designCat = $categories['ui-ux-design'];
        $course4 = Course::firstOrCreate(
            ['slug' => 'mastering-figma-ui-ux-design-systems'],
            [
                'category_id' => $designCat->id,
                'title' => 'Mastering Figma: Modern UI/UX & Design Systems',
                'slug' => 'mastering-figma-ui-ux-design-systems',
                'short_description' => 'Design sleek web and mobile interfaces, auto layouts, interactive prototypes, and design tokens in Figma.',
                'description' => "<p>Elevate your design career. Learn the fundamentals of visual hierarchy, accessibility, wireframing, and creating reusable design systems in Figma.</p>",
                'price' => 2500.00,
                'discount_price' => 1750.00,
                'duration' => '8 Hours',
                'passing_score' => 70,
                'learning_outcomes' => [
                    'Master Figma components, variants, auto layout, and design tokens.',
                    'Design responsive landing pages and web app interfaces.',
                    'Build clickable interactive prototypes for user testing.',
                    'Hand off production-ready design files to frontend engineers.',
                ],
                'status' => 'published',
                'meta_title' => 'Mastering Figma UI/UX Design | LearnHub',
                'meta_description' => 'Learn Figma and UI/UX design to create modern high-converting digital products.',
            ]
        );

        // 4. Lessons for Course 1 (Laravel)
        $lessons = [
            [
                'title' => 'Introduction to Modern Laravel Architecture',
                'slug' => 'introduction-to-modern-laravel-architecture',
                'video_url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                'duration' => '15 mins',
                'order' => 1,
                'is_preview' => true,
                'is_published' => true,
                'description' => 'Get an overview of the Laravel application lifecycle, directory structure, and environment configuration.',
            ],
            [
                'title' => 'Routing, Controllers & Blade Templating Engine',
                'slug' => 'routing-controllers-and-blade',
                'video_url' => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
                'duration' => '22 mins',
                'order' => 2,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'Learn how to define RESTful routes, create controllers, and build reusable Blade components with Tailwind CSS.',
            ],
            [
                'title' => 'Database Migrations, Schema Design & Eloquent ORM',
                'slug' => 'database-migrations-and-eloquent',
                'video_url' => 'https://www.youtube.com/watch?v=2eebpx74UxI',
                'duration' => '30 mins',
                'order' => 3,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'Understand relational database design, one-to-many and many-to-many Eloquent relationships, and query optimization.',
            ],
            [
                'title' => 'Service Pattern & Clean Architecture in Laravel',
                'slug' => 'service-pattern-and-clean-architecture',
                'video_url' => 'https://www.youtube.com/watch?v=pfaSUYaSgRo',
                'duration' => '25 mins',
                'order' => 4,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'Extract business logic into reusable service classes to keep controllers lean and testable.',
            ],
        ];

        foreach ($lessons as $lessonData) {
            $lessonData['course_id'] = $course1->id;
            Lesson::firstOrCreate(
                ['course_id' => $course1->id, 'slug' => $lessonData['slug']],
                $lessonData
            );
        }

        // Add 2 lessons to course 2
        Lesson::firstOrCreate(
            ['course_id' => $course2->id, 'slug' => 'javascript-foundations'],
            [
                'course_id' => $course2->id,
                'title' => 'JavaScript Foundations & ES6+ Features',
                'slug' => 'javascript-foundations',
                'video_url' => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
                'duration' => '20 mins',
                'order' => 1,
                'is_preview' => true,
                'is_published' => true,
                'description' => 'Modern JavaScript features including arrow functions, destructuring, and modules.',
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $course2->id, 'slug' => 'async-await-and-fetch'],
            [
                'course_id' => $course2->id,
                'title' => 'Async/Await & Communicating with APIs',
                'slug' => 'async-await-and-fetch',
                'video_url' => 'https://www.youtube.com/watch?v=2eebpx74UxI',
                'duration' => '28 mins',
                'order' => 2,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'Handling asynchronous requests, handling promises, and managing responses.',
            ]
        );

        // 5. MCQ Exam for Course 1
        $exam = Exam::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'Laravel Masterclass Certification Assessment'],
            [
                'course_id' => $course1->id,
                'title' => 'Laravel Masterclass Certification Assessment',
                'description' => 'Answer the following multiple-choice questions to demonstrate your mastery of Laravel concepts and qualify for your course certificate.',
                'passing_percentage' => 70,
                'duration_minutes' => 20,
                'max_attempts' => 3,
                'is_required' => true,
                'is_published' => true,
            ]
        );

        $questionsData = [
            [
                'question_text' => 'What is the primary role of the Laravel Service Pattern?',
                'explanation' => 'The Service Pattern separates complex business logic from HTTP controllers, keeping controllers lean and code reusable.',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'To handle database migrations automatically', 'is_correct' => false],
                    ['option_text' => 'To encapsulate business logic away from controllers into dedicated service classes', 'is_correct' => true],
                    ['option_text' => 'To replace Blade templates with client-side JavaScript', 'is_correct' => false],
                    ['option_text' => 'To style the application using CSS utilities', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Which Eloquent method defines a Many-to-Many relationship between two models?',
                'explanation' => 'belongsToMany() defines a many-to-many relationship using an intermediate pivot table.',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'hasManyThrough()', 'is_correct' => false],
                    ['option_text' => 'belongsToMany()', 'is_correct' => true],
                    ['option_text' => 'hasMany()', 'is_correct' => false],
                    ['option_text' => 'morphToMany()', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'How can you protect a Laravel route group so only authenticated admins can access it?',
                'explanation' => 'Custom middleware (e.g. AdminMiddleware) verifies auth status and admin role before forwarding the request.',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'By applying an Admin middleware or policy to the route group', 'is_correct' => true],
                    ['option_text' => 'By changing the .env APP_ENV to admin', 'is_correct' => false],
                    ['option_text' => 'By setting database column is_admin to NULL', 'is_correct' => false],
                    ['option_text' => 'By disabling CSRF verification', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Why must payment verification be executed server-to-server rather than relying on browser redirects?',
                'explanation' => 'Browser redirects can easily be spoofed or manipulated by malicious clients; only server-side validation with the gateway verifies authentic payment.',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'Because browsers do not support HTTPS connections', 'is_correct' => false],
                    ['option_text' => 'Because browser redirects can be spoofed, whereas server-side verification confirms actual payment receipt with the gateway API', 'is_correct' => true],
                    ['option_text' => 'Because Laravel cannot read GET query parameters', 'is_correct' => false],
                    ['option_text' => 'Because databases cannot store transactions created by browsers', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What is the purpose of database transactions (DB::transaction) during critical operations?',
                'explanation' => 'Database transactions ensure atomicity: either all database queries succeed or all are rolled back if an error occurs.',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'To encrypt user passwords using bcrypt', 'is_correct' => false],
                    ['option_text' => 'To ensure all related operations succeed together or roll back completely on failure, preventing partial inconsistent state', 'is_correct' => true],
                    ['option_text' => 'To cache queries in Redis memory', 'is_correct' => false],
                    ['option_text' => 'To compile Tailwind CSS styles', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $index => $qData) {
            $question = Question::firstOrCreate(
                ['exam_id' => $exam->id, 'question_text' => $qData['question_text']],
                [
                    'exam_id' => $exam->id,
                    'question_text' => $qData['question_text'],
                    'explanation' => $qData['explanation'],
                    'marks' => $qData['marks'],
                    'order' => $index + 1,
                ]
            );

            // Recreate options
            $question->options()->delete();
            foreach ($qData['options'] as $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['option_text'],
                    'is_correct' => $opt['is_correct'],
                ]);
            }
        }

        // 6. Sliders
        $sliders = [
            [
                'heading' => 'Master In-Demand Tech Skills Online',
                'description' => 'Explore industry-crafted video courses, take interactive MCQ assessments, and earn verified certificates recognized by employers.',
                'button_text' => 'Browse All Courses',
                'button_url' => '#courses-section',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'heading' => 'Become a Certified Laravel Developer',
                'description' => 'From fundamentals to enterprise architecture and payment gateways. Step up your development career with hands-on learning.',
                'button_text' => 'View Laravel Masterclass',
                'button_url' => url('/courses/' . $course1->slug),
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slide) {
            Slider::firstOrCreate(['heading' => $slide['heading']], $slide);
        }

        // 7. Website Settings
        $settings = [
            'site_name' => 'LearnHub LMS',
            'site_tagline' => 'Quality Video Courses & Certified Learning',
            'contact_email' => 'support@learnhub.com',
            'contact_phone' => '+880 1711 223344',
            'contact_address' => 'House 42, Road 11, Banani, Dhaka - 1213, Bangladesh',
            'about_heading' => 'Empowering Careers Through Accessible Quality Education',
            'about_content' => "LearnHub LMS is dedicated to delivering high quality, practical, and project-based technology education. We believe in learning by building, verified knowledge testing through MCQ assessments, and credible certificate issuance.\n\nWhether you are beginning your journey or upgrading your professional expertise, our structured curriculum gives you the exact skills you need to succeed.",
            'meta_title' => 'LearnHub LMS - Sell & Learn Online Courses',
            'meta_description' => 'Minimal, fast, and SEO-optimized LMS platform for video courses, online exams, and instant verified certificates.',
            'privacy_policy' => "We value your privacy and are committed to protecting your personal data. We collect only necessary details such as your name and email to manage your student account, track course progress, and process course orders. We never sell or share your personal data with unauthorized third parties.",
            'terms_conditions' => "By using LearnHub LMS and purchasing courses, you agree to watch the content for personal educational use. Course access is granted upon verified payment. Certificates are issued exclusively upon fulfilling both course lesson completion and passing score requirements.",
        ];

        foreach ($settings as $key => $val) {
            WebsiteSetting::firstOrCreate(['key' => $key], ['value' => $val, 'group' => 'general']);
        }
    }
}
