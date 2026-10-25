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
                'name' => 'এডমিনিস্ট্রেটর',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+880 1711 000001',
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@lms.test'],
            [
                'name' => 'তানভীর আহমেদ',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '+880 1722 000002',
            ]
        );

        // 2. Categories in Bangla
        $categoriesData = [
            [
                'name' => 'ওয়েব ডেভেলপমেন্ট',
                'slug' => 'web-development',
                'description' => 'আধুনিক প্রযুক্তি ব্যবহার করে প্রফেশনাল ও ডায়নামিক ওয়েব অ্যাপ্লিকেশন তৈরি শিখুন।',
                'image' => null,
            ],
            [
                'name' => 'মোবাইল অ্যাপ ডেভেলপমেন্ট',
                'slug' => 'mobile-app-development',
                'description' => 'অ্যান্ড্রয়েড এবং আইওএস প্ল্যাটফর্মের জন্য ক্রস-প্ল্যাটফর্ম মোবাইল অ্যাপ তৈরি।',
                'image' => null,
            ],
            [
                'name' => 'ইউআই/ইউএক্স ডিজাইন',
                'slug' => 'ui-ux-design',
                'description' => 'ফিগমা দিয়ে আধুনিক ইউজার ইন্টারফেস, ওয়্যারফ্রেম ও প্রোটোটাইপিং ডিজাইন।',
                'image' => null,
            ],
            [
                'name' => 'ডাটা সায়েন্স ও এআই',
                'slug' => 'data-science-ai',
                'description' => 'ডাটা অ্যানালাইসিস, মেশিন লার্নিং অ্যালগরিদম ও আধুনিক কৃত্রিম বুদ্ধিমত্তা।',
                'image' => null,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $existing = Category::where('slug', $cat['slug'])->first();
            if ($existing) {
                $existing->update($cat);
                $categories[$cat['slug']] = $existing;
            } else {
                $categories[$cat['slug']] = Category::create($cat);
            }
        }

        // 3. Courses in Bangla
        $webCat = $categories['web-development'];
        $course1Data = [
            'category_id' => $webCat->id,
            'title' => 'কমপ্লিট লারাভেল ও পিএইচপি মাস্টারক্লাস',
            'slug' => 'complete-laravel-masterclass',
            'short_description' => 'লারাভেল ফ্রেমওয়ার্ক, সার্ভিস আর্কিটেকচার, ডেটাবেজ ও পেমেন্ট গেটওয়ে ইন্টিগ্রেশন একদম শুরু থেকে শিখুন।',
            'description' => "<p><strong>কমপ্লিট লারাভেল ও পিএইচপি মাস্টারক্লাস</strong> এ আপনাকে স্বাগতম! এই কোর্সে আপনি লারাভেলের মাধ্যমে এন্টারপ্রাইজ লেভেলের আধুনিক ওয়েব অ্যাপ্লিকেশন তৈরি শিখবেন।</p><p>এমভিসি প্যাটার্ন, সার্ভিস লেয়ার, অথেন্টিকেশন, ডাটাবেজ মাইগ্রেশন ও এসএসএলকমার্স পেমেন্ট গেটওয়ে ইন্টিগ্রেশন বিস্তারিতভাবে কভার করা হয়েছে।</p>",
            'price' => 3500.00,
            'discount_price' => 2450.00,
            'duration' => '১৪ ঘণ্টা',
            'passing_score' => 70,
            'learning_outcomes' => [
                'লারাভেল সার্ভিস প্যাটার্ন দিয়ে ক্লিন ও স্কেলেবল কোড তৈরি।',
                'ইলোক্যোয়েন্ট ওআরএম রিলেশনশিপ, স্কোপ ও মাইগ্রেশন প্র্যাকটিস।',
                'নিরাপদ অথেন্টিকেশন, রোল-ভিত্তিক এক্সেস ও মিডলওয়্যার তৈরি।',
                'এসএসএলকমার্স পেমেন্ট গেটওয়ে ও ওয়েবহুক ইন্টিগ্রেশন।',
                'স্বয়ংক্রিয় ভেরিফাইড পিডিএফ সার্টিফিকেট জেনারেশন।',
            ],
            'status' => 'published',
            'meta_title' => 'কমপ্লিট লারাভেল ও পিএইচপি মাস্টারক্লাস | সহজ এলএমএস',
            'meta_description' => 'বাংলায় লারাভেল ফ্রেমওয়ার্ক শিখুন একদম শুরু থেকে। রিয়েল লাইফ প্রজেক্ট ও সার্টিফিকেট অর্জন করুন।',
        ];

        $course1 = Course::where('slug', 'complete-laravel-masterclass')->first();
        if ($course1) {
            $course1->update($course1Data);
        } else {
            $course1 = Course::create($course1Data);
        }

        $course2Data = [
            'category_id' => $webCat->id,
            'title' => 'ফুল-স্ট্যাক জাভাস্ক্রিপ্ট: আধুনিক ফ্রন্টএন্ড ও এপিআই',
            'slug' => 'full-stack-javascript-vue-node',
            'short_description' => 'আধুনিক জাভাস্ক্রিপ্ট ES6+, রেস্টফুল এপিআই এবং রেসপনসিভ ইউজার ইন্টারফেস তৈরির পূর্ণাঙ্গ কোর্স।',
            'description' => "<p>আপনার জাভাস্ক্রিপ্ট দক্ষতাকে প্রফেশনাল লেভেলে নিয়ে যান। ES6+, রিঅ্যাকটিভ কম্পোনেন্ট, স্টেট ম্যানেজমেন্ট এবং এপিআই ডিজাইন শিখুন ধাপে ধাপে।</p>",
            'price' => 2800.00,
            'discount_price' => 1999.00,
            'duration' => '১০ ঘণ্টা',
            'passing_score' => 70,
            'learning_outcomes' => [
                'মডার্ন ও ক্লিন জাভাস্ক্রিপ্ট কোড লেখার দক্ষতা অর্জন।',
                'অ্যাসিঙ্ক/অ্যাওয়েট, প্রমিজ ও এজ্যাক্স এইচটিটিপি রিকোয়েস্ট পরিচালনা।',
                'ইন্টারেক্টিভ ক্লায়েন্ট-সাইড কম্পোনেন্ট তৈরি।',
                'নিরাপদ ও হাই-পারফরম্যান্স ওয়েব অ্যাপ ডেপ্লয়মেন্ট।',
            ],
            'status' => 'published',
            'meta_title' => 'ফুল-স্ট্যাক জাভাস্ক্রিপ্ট কোর্স | সহজ এলএমএস',
            'meta_description' => 'বাস্তবমুখী প্রজেক্টের সাথে আধুনিক জাভাস্ক্রিপ্ট ডেভেলপমেন্ট শিখুন।',
        ];

        $course2 = Course::where('slug', 'full-stack-javascript-vue-node')->first();
        if ($course2) {
            $course2->update($course2Data);
        } else {
            $course2 = Course::create($course2Data);
        }

        $mobileCat = $categories['mobile-app-development'];
        $course3Data = [
            'category_id' => $mobileCat->id,
            'title' => 'ফ্লাটার ও ডার্ট: ক্রস-প্ল্যাটফর্ম মোবাইল অ্যাপ ডেভেলপমেন্ট',
            'slug' => 'flutter-dart-mobile-development',
            'short_description' => 'ফ্লাটার ৩ এবং ডার্ট ব্যবহার করে অ্যান্ড্রয়েড ও আইওএস এর জন্য প্রফেশনাল অ্যাপ তৈরি করুন।',
            'description' => "<p>ফ্লাটার ও ডার্ট দিয়ে চমৎকার মোবাইল অ্যাপ্লিকেশন তৈরি শিখুন। আধুনিক ইউআই তৈরি, লোকাল স্টোরেজ হ্যান্ডলিং এবং রিমোট ব্যাকএন্ড কানেক্টিভিটি কভার করা হয়েছে।</p>",
            'price' => 4200.00,
            'discount_price' => 3150.00,
            'duration' => '১৮ ঘণ্টা',
            'passing_score' => 75,
            'learning_outcomes' => [
                'ডার্ট সিনট্যাক্স, অবজেক্ট ওরিয়েন্টেড কনসেপ্ট ও ফ্লাটার উইজেট আর্কিটেকচার।',
                'অ্যান্ড্রয়েড ও আইওএস উভয় প্ল্যাটফর্মের জন্য রেসপনসিভ ইউআই ডিজাইন।',
                'স্টেট ম্যানেজমেন্টের কার্যকর ব্যবহার।',
                'গুগল প্লে স্টোর ও অ্যাপল অ্যাপ স্টোরে পাবলিশ করার প্রস্তুতি।',
            ],
            'status' => 'published',
            'meta_title' => 'ফ্লাটার ও ডার্ট মোবাইল অ্যাপ কোর্স | সহজ এলএমএস',
            'meta_description' => 'ফ্লাটার ৩ দিয়ে ক্রস-প্ল্যাটফর্ম মোবাইল অ্যাপ তৈরি শিখুন।',
        ];

        $course3 = Course::where('slug', 'flutter-dart-mobile-development')->first();
        if ($course3) {
            $course3->update($course3Data);
        } else {
            $course3 = Course::create($course3Data);
        }

        $designCat = $categories['ui-ux-design'];
        $course4Data = [
            'category_id' => $designCat->id,
            'title' => 'ফিগমা মাস্টারি: ইউআই/ইউএক্স ও ডিজাইন সিস্টেম',
            'slug' => 'mastering-figma-ui-ux-design-systems',
            'short_description' => 'ফিগমা দিয়ে আকর্ষণীয় ওয়েব ও মোবাইল ইন্টারফেস, অটো লেআউট ও ইন্টারেক্টিভ প্রোটোটাইপ ডিজাইন।',
            'description' => "<p>আপনার ডিজাইন ক্যারিয়ারকে এগিয়ে নিন। ভিজ্যুয়াল হায়ারার্কি, ওয়্যারফ্রেমিং এবং রিইউজেবল ডিজাইন সিস্টেম তৈরি শিখুন শুরু থেকে।</p>",
            'price' => 2500.00,
            'discount_price' => 1750.00,
            'duration' => '৮ ঘণ্টা',
            'passing_score' => 70,
            'learning_outcomes' => [
                'ফিগমা কম্পোনেন্ট, ভ্যারিয়েন্ট, অটো লেআউট ও ডিজাইন টোকেন তৈরি।',
                'রেসপনসিভ ল্যান্ডিং পেজ ও অ্যাপ ইন্টারফেস ডিজাইন।',
                'ক্লিকেবল ইন্টারেক্টিভ প্রোটোটাইপ প্রস্তুতকরণ।',
                'ডেভেলপারদের জন্য প্রোডাকশন-রেডি ফাইল হ্যান্ডঅফ।',
            ],
            'status' => 'published',
            'meta_title' => 'ফিগমা ইউআই/ইউএক্স ডিজাইন কোর্স | সহজ এলএমএস',
            'meta_description' => 'ফিগমা দিয়ে আধুনিক ডিজিটাল প্রডাক্ট ডিজাইন শিখুন।',
        ];

        $course4 = Course::where('slug', 'mastering-figma-ui-ux-design-systems')->first();
        if ($course4) {
            $course4->update($course4Data);
        } else {
            $course4 = Course::create($course4Data);
        }

        // 4. Lessons for Course 1
        $lessons = [
            [
                'title' => 'লারাভেল আর্কিটেকচার ও পরিচিতি',
                'slug' => 'introduction-to-modern-laravel-architecture',
                'video_url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                'duration' => '১৫ মিনিট',
                'order' => 1,
                'is_preview' => true,
                'is_published' => true,
                'description' => 'লারাভেল অ্যাপ্লিকেশনের লাইফসাইকেল, ডিরেক্টরি স্ট্রাকচার এবং এনভায়রনমেন্ট কনফিগারেশনের প্রাথমিক ধারণা।',
            ],
            [
                'title' => 'রাউটিং, কন্ট্রোলার ও ব্লেড টেমপ্লেটিং',
                'slug' => 'routing-controllers-and-blade',
                'video_url' => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
                'duration' => '২২ মিনিট',
                'order' => 2,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'লারাভেলে রেস্টফুল রাউট তৈরি, কন্ট্রোলার মেথড এবং ব্লেড কম্পোনেন্ট ব্যবহারের নিয়মাবলী।',
            ],
            [
                'title' => 'ডাটাবেজ মাইগ্রেশন ও ইলোক্যোয়েন্ট ওআরএম',
                'slug' => 'database-migrations-and-eloquent',
                'video_url' => 'https://www.youtube.com/watch?v=2eebpx74UxI',
                'duration' => '৩০ মিনিট',
                'order' => 3,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'রিলেশনাল ডাটাবেজ মডেলিং, ওয়ান-টু-মেনি ও মেনি-টু-মেনি রিলেশন এবং কুয়েরি অপটিমাইজেশন।',
            ],
            [
                'title' => 'লারাভেলে সার্ভিস প্যাটার্ন ও ক্লিন আর্কিটেকচার',
                'slug' => 'service-pattern-and-clean-architecture',
                'video_url' => 'https://www.youtube.com/watch?v=pfaSUYaSgRo',
                'duration' => '২৫ মিনিট',
                'order' => 4,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'বিজনেস লজিক সার্ভিস ক্লাসে আলাদা করে কন্ট্রোলার হালকা এবং টেস্টিং উপযোগী রাখা।',
            ],
        ];

        foreach ($lessons as $lessonData) {
            $lessonData['course_id'] = $course1->id;
            $existingL = Lesson::where('course_id', $course1->id)->where('slug', $lessonData['slug'])->first();
            if ($existingL) {
                $existingL->update($lessonData);
            } else {
                Lesson::create($lessonData);
            }
        }

        // Add 2 lessons to course 2
        Lesson::updateOrCreate(
            ['course_id' => $course2->id, 'slug' => 'javascript-foundations'],
            [
                'title' => 'জাভাস্ক্রিপ্ট বেসিক ও ES6+ ফিচার',
                'video_url' => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
                'duration' => '২০ মিনিট',
                'order' => 1,
                'is_preview' => true,
                'is_published' => true,
                'description' => 'অ্যারো ফাংশন, ডিস্ট্রাকচারিং ও মডিউলসহ আধুনিক জাভাস্ক্রিপ্টের গুরুত্বপূর্ণ ফিচারসমূহ।',
            ]
        );

        Lesson::updateOrCreate(
            ['course_id' => $course2->id, 'slug' => 'async-await-and-fetch'],
            [
                'title' => 'অ্যাসিঙ্ক/অ্যাওয়েট ও এপিআই কানেকশন',
                'video_url' => 'https://www.youtube.com/watch?v=2eebpx74UxI',
                'duration' => '২৮ মিনিট',
                'order' => 2,
                'is_preview' => false,
                'is_published' => true,
                'description' => 'অ্যাসিঙ্ক্রোনাস রিকোয়েস্ট পাঠানো, প্রমিজ হ্যান্ডলিং এবং ডাটা রেসপন্স প্রসেসিং।',
            ]
        );

        // 5. MCQ Exam for Course 1
        $exam = Exam::updateOrCreate(
            ['course_id' => $course1->id, 'title' => 'লারাভেল মাস্টারক্লাস সার্টিফিকেট মূল্যায়ন পরীক্ষা'],
            [
                'description' => 'লারাভেলের মৌলিক ও গুরুত্বপূর্ণ বিষয়গুলোর ওপর আপনার দক্ষতা যাচাই করতে নিচের প্রশ্নগুলোর সঠিক উত্তর দিন এবং সার্টিফিকেট অর্জন করুন।',
                'passing_percentage' => 70,
                'duration_minutes' => 20,
                'max_attempts' => 3,
                'is_required' => true,
                'is_published' => true,
            ]
        );

        $questionsData = [
            [
                'question_text' => 'লারাভেলে সার্ভিস প্যাটার্ন ব্যবহারের প্রধান সুবিধা কী?',
                'explanation' => 'সার্ভিস প্যাটার্ন জটিল বিজনেস লজিককে কন্ট্রোলার থেকে আলাদা রাখে, ফলে কোড পরিষ্কার ও পুনঃব্যবহারযোগ্য থাকে।',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'স্বয়ংক্রিয়ভাবে ডাটাবেজ তৈরি করার জন্য', 'is_correct' => false],
                    ['option_text' => 'কন্ট্রোলার থেকে বিজনেস লজিক আলাদা করে কোড রিইউজেবল রাখার জন্য', 'is_correct' => true],
                    ['option_text' => 'ব্লেড টেমপ্লেট মুছে ফেলার জন্য', 'is_correct' => false],
                    ['option_text' => 'সিএসএস স্টাইল করার জন্য', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'দুটি মডেলের মধ্যে Many-to-Many রিলেশনশিপ ডিফাইন করতে কোন মেথডটি ব্যবহার করা হয়?',
                'explanation' => 'belongsToMany() মেথডটি পিভট টেবিলের মাধ্যমে মেনি-টু-মেনি রিলেশনশিপ তৈরি করে।',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'hasManyThrough()', 'is_correct' => false],
                    ['option_text' => 'belongsToMany()', 'is_correct' => true],
                    ['option_text' => 'hasMany()', 'is_correct' => false],
                    ['option_text' => 'morphToMany()', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'লারাভেলে শুধুমাত্র অ্যাডমিনরা যাতে একটি রাউট গ্রুপে ঢুকতে পারে তা নিশ্চিত করার সেরা উপায় কোনটি?',
                'explanation' => 'কাস্টম মিডলওয়্যার (যেমন AdminMiddleware) ইউজারের অথেন্টিকেশন ও অ্যাডমিন রোল যাচাই করে।',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'রাউট গ্রুপে অ্যাডমিন মিডলওয়্যার বা পলিসি প্রয়োগ করে', 'is_correct' => true],
                    ['option_text' => '.env ফাইলে APP_ENV=admin লিখে', 'is_correct' => false],
                    ['option_text' => 'ডাটাবেজের ফিল্ড মুছে দিয়ে', 'is_correct' => false],
                    ['option_text' => 'CSRF ভেরিফিকেশন বন্ধ করে দিয়ে', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'পেমেন্ট গেটওয়ের ক্ষেত্রে সার্ভার-টু-সার্ভার যাচাইকরণ কেন অত্যন্ত জরুরি?',
                'explanation' => 'ব্রাউজার রিডাইরেক্ট যেকোনো ক্লায়েন্ট নকল করতে পারে, তাই সার্ভার থেকে পেমেন্ট গেটওয়েতে যাচাই করা নিরাপদ।',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'কারণ ব্রাউজার কখনো HTTPS সমর্থন করে না', 'is_correct' => false],
                    ['option_text' => 'কারণ ব্রাউজার রিডাইরেক্ট স্পুফ বা জালিয়াতি করা সম্ভব, সার্ভার যাচাইকরণে আসল পেমেন্ট নিশ্চিত হয়', 'is_correct' => true],
                    ['option_text' => 'কারণ লারাভেল GET প্যারামিটার পড়তে পারে না', 'is_correct' => false],
                    ['option_text' => 'কারণ ডাটাবেজে ডাটা রাখা যায় না', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'গুরুত্বপূর্ণ ডাটাবেজ অপারেশনের সময় DB::transaction ব্যবহারের উদ্দেশ্য কী?',
                'explanation' => 'ডাটাবেজ ট্রানজ্যাকশন নিশ্চিত করে যেন সব কুয়েরি একসাথে সফল হয়, কোনো একটি ফেইল করলে সবগুলো আগের অবস্থায় ফিরে যায়।',
                'marks' => 1,
                'options' => [
                    ['option_text' => 'পাসওয়ার্ড হ্যাশ করার জন্য', 'is_correct' => false],
                    ['option_text' => 'সবগুলো অপারেশন একসাথে সফল করতে বা ব্যর্থ হলে রোলব্যাক করতে', 'is_correct' => true],
                    ['option_text' => 'রেডিস ক্যাশিং করার জন্য', 'is_correct' => false],
                    ['option_text' => 'টেইলউইন্ড সিএসএস কম্পাইল করার জন্য', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $index => $qData) {
            $question = Question::updateOrCreate(
                ['exam_id' => $exam->id, 'question_text' => $qData['question_text']],
                [
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

        // 6. Sliders in Bangla
        Slider::truncate();
        Slider::create([
            'image' => 'sliders/banner1.jpg',
            'heading' => 'অনলাইনে সহজে শিখুন আধুনিক প্রযুক্তি ও স্কিল',
            'description' => 'বাংলায় প্র্যাকটিক্যাল ভিডিও কোর্স, অনলাইন কুইজ পরীক্ষা এবং ইন্ডাস্ট্রি স্বীকৃত ভেরিফাইড সার্টিফিকেট।',
            'button_text' => 'সকল কোর্স দেখুন',
            'button_url' => '#courses-section',
            'order' => 1,
            'is_active' => true,
        ]);

        Slider::create([
            'image' => 'sliders/banner2.jpg',
            'heading' => null,
            'description' => null,
            'button_text' => null,
            'button_url' => '#courses-section',
            'order' => 2,
            'is_active' => true,
        ]);

        // 7. Website Settings in Bangla
        $settings = [
            'site_name' => 'সহজ এলএমএস',
            'site_tagline' => 'সহজে বাংলায় শিখুন আধুনিক প্রযুক্তি ও স্কিল',
            'contact_email' => 'support@learnhub.com',
            'contact_phone' => '+880 1711 223344',
            'contact_address' => 'বাড়ি ৪২, রোড ১১, বনানী, ঢাকা - ১২১৩, বাংলাদেশ',
            'about_heading' => 'সহজ ও মানসম্মত অনলাইন শিক্ষার মাধ্যমে ক্যারিয়ার গড়ুন',
            'about_content' => "সহজ এলএমএস-এর উদ্দেশ্য হলো সবার কাছে সহজে ও বাংলায় বিশ্বমানের প্র্যাকটিক্যাল প্রযুক্তি শিক্ষা পৌঁছে দেওয়া।\n\nপ্রজেক্টভিত্তিক ভিডিও লেসনের মাধ্যমে হাতে-কলমে শিখুন, এমসিকিউ কুইজের মাধ্যমে দক্ষতা যাচাই করুন এবং প্রতিটি কোর্স সফলভাবে শেষ করে অর্জন করুন গ্রহণযোগ্য ভেরিফাইড সার্টিফিকেট।",
            'meta_title' => 'সহজ এলএমএস - বাংলায় অনলাইন কোর্স ও সার্টিফিকেট',
            'meta_description' => 'সহজ, দ্রুত এবং কার্যকর অনলাইন লার্নিং প্ল্যাটফর্ম। ভিডিও কোর্স, অনলাইন পরীক্ষা এবং ভেরিফাইড সার্টিফিকেট।',
            'privacy_policy' => "আমরা আপনার তথ্যের গোপনীয়তাকে সর্বোচ্চ প্রাধান্য দিই। আপনার নাম, ইমেইল ও অ্যাকাউন্টের তথ্যাদি শুধুমাত্র কোর্স পরিচালনা ও সার্টিফিকেট ইস্যুর প্রয়োজনে সংরক্ষিত থাকে।",
            'terms_conditions' => "সহজ এলএমএস-এ রেজিস্ট্রেশন এবং কোর্স ক্রয়ের মাধ্যমে আপনি শিক্ষামূলক উদ্দেশ্যে এই প্ল্যাটফর্মটি ব্যবহার করতে সম্মত হচ্ছেন। কোর্স সফলভাবে শেষ এবং কুইজে পাস করার পর সার্টিফিকেট প্রদান করা হবে।",
        ];

        foreach ($settings as $key => $val) {
            WebsiteSetting::updateOrCreate(['key' => $key], ['value' => $val, 'group' => 'general']);
        }

        // Call School ERP Seeder
        $this->call(SchoolErpSeeder::class);
    }
}
