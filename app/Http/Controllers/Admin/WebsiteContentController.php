<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\WebsiteSettingService;
use Illuminate\Http\Request;

class WebsiteContentController extends Controller
{
    protected WebsiteSettingService $settingService;

    public function __construct(WebsiteSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function settings()
    {
        $settings = $this->settingService->getAllGrouped();
        return view('admin.content.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', 'site_logo', 'site_favicon', 'about_image']);
        $files = [
            'site_logo' => $request->file('site_logo'),
            'site_favicon' => $request->file('site_favicon'),
            'about_image' => $request->file('about_image'),
        ];

        $this->settingService->updateSettings($data, $files);

        return redirect()->back()->with('success', 'Website settings updated successfully.');
    }

    public function sliders()
    {
        $sliders = $this->settingService->getAllSliders();
        return view('admin.content.sliders', compact('sliders'));
    }

    public function storeSlider(Request $request)
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $this->settingService->createSlider($data, $request->file('image'));

        return redirect()->route('admin.content.sliders')
            ->with('success', 'Hero slide added successfully.');
    }

    public function updateSlider(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $this->settingService->updateSlider($slider, $data, $request->file('image'));

        return redirect()->route('admin.content.sliders')
            ->with('success', 'Hero slide updated successfully.');
    }

    public function destroySlider(Slider $slider)
    {
        $this->settingService->deleteSlider($slider);

        return redirect()->route('admin.content.sliders')
            ->with('success', 'Slide removed.');
    }
}
