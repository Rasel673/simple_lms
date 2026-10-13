<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index()
    {
        $user = auth()->user();
        $certificates = Certificate::with(['course.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('student.certificates', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        $user = auth()->user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $certificate->load(['course', 'user']);

        return view('student.certificate-view', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        $user = auth()->user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        return $this->certificateService->downloadPdf($certificate);
    }
}
