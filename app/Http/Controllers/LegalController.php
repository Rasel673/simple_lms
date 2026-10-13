<?php

namespace App\Http\Controllers;

use App\Services\WebsiteSettingService;

class LegalController extends Controller
{
    protected WebsiteSettingService $settingService;

    public function __construct(WebsiteSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function privacy()
    {
        $privacyPolicy = $this->settingService->get('privacy_policy');
        return view('pages.privacy-policy', compact('privacyPolicy'));
    }

    public function terms()
    {
        $termsConditions = $this->settingService->get('terms_conditions');
        return view('pages.terms-conditions', compact('termsConditions'));
    }
}
