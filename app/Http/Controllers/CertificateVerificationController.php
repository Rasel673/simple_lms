<?php

namespace App\Http\Controllers;

use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function verify(Request $request, ?string $number = null)
    {
        $certNumber = $number ?: $request->query('number');
        $certificate = null;
        $searched = false;

        if ($certNumber) {
            $searched = true;
            $certificate = $this->certificateService->verifyCertificate($certNumber);
        }

        return view('pages.verify-certificate', compact('certificate', 'certNumber', 'searched'));
    }
}
