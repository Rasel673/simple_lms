<?php

namespace App\Services;

use App\Models\Slider;
use App\Models\WebsiteSetting;
use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Collection;

class WebsiteSettingService
{
    public function get(string $key, $default = null)
    {
        return WebsiteSetting::get($key, $default);
    }

    public function getAllGrouped(): array
    {
        $settings = WebsiteSetting::all()->pluck('value', 'key')->toArray();

        return [
            'site_name' => $settings['site_name'] ?? config('app.name', 'LearnHub LMS'),
            'site_tagline' => $settings['site_tagline'] ?? 'Master In-Demand Skills with Expert-Led Video Courses',
            'site_logo' => $settings['site_logo'] ?? null,
            'site_favicon' => $settings['site_favicon'] ?? null,
            'contact_email' => $settings['contact_email'] ?? 'support@learnhub.com',
            'contact_phone' => $settings['contact_phone'] ?? '+880 1700 000000',
            'contact_address' => $settings['contact_address'] ?? 'Dhanmondi, Dhaka, Bangladesh',
            'about_heading' => $settings['about_heading'] ?? 'About LearnHub LMS',
            'about_content' => $settings['about_content'] ?? 'LearnHub LMS is a premier modern online learning platform empowering individuals to achieve their professional goals through high quality video courses and certified skill development.',
            'about_image' => $settings['about_image'] ?? null,
            'meta_title' => $settings['meta_title'] ?? 'LearnHub LMS - Learn In-Demand Skills Online',
            'meta_description' => $settings['meta_description'] ?? 'Join thousands of learners worldwide. Buy and watch courses, take MCQ exams, and earn verified certificates.',
            'privacy_policy' => $settings['privacy_policy'] ?? 'We respect your privacy. Your information is securely handled and will never be shared with third parties without consent.',
            'terms_conditions' => $settings['terms_conditions'] ?? 'By accessing and using LearnHub LMS, you agree to comply with our platform policies and course access guidelines.',
        ];
    }

    public function updateSettings(array $data, array $files = []): void
    {
        foreach ($data as $key => $value) {
            WebsiteSetting::set($key, $value);
        }

        foreach ($files as $key => $file) {
            if ($file) {
                $oldPath = WebsiteSetting::get($key);
                PublicImageStorage::delete($oldPath);
                $path = PublicImageStorage::store($file, 'settings');
                WebsiteSetting::set($key, $path);
            }
        }
    }

    public function getActiveSliders(): Collection
    {
        return Slider::active()->get();
    }

    public function getAllSliders(): Collection
    {
        return Slider::orderBy('order', 'asc')->get();
    }

    public function createSlider(array $data, $imageFile = null): Slider
    {
        if ($imageFile) {
            $data['image'] = PublicImageStorage::store($imageFile, 'sliders');
        }

        if (!isset($data['order']) || $data['order'] <= 0) {
            $data['order'] = (Slider::max('order') ?? 0) + 1;
        }

        return Slider::create($data);
    }

    public function updateSlider(Slider $slider, array $data, $imageFile = null): Slider
    {
        if ($imageFile) {
            PublicImageStorage::delete($slider->image);
            $data['image'] = PublicImageStorage::store($imageFile, 'sliders');
        }

        $slider->update($data);
        return $slider;
    }

    public function deleteSlider(Slider $slider): bool
    {
        PublicImageStorage::delete($slider->image);

        return $slider->delete();
    }
}
