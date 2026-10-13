<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CertificateService
{
    public function getOrCreateCertificate(int $userId, int $courseId): Certificate
    {
        $existing = Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) {
            return $existing;
        }

        $certificateNumber = $this->generateUniqueCertificateNumber();

        return Certificate::create([
            'certificate_number' => $certificateNumber,
            'user_id' => $userId,
            'course_id' => $courseId,
            'issue_date' => now()->toDateString(),
        ]);
    }

    public function generateUniqueCertificateNumber(): string
    {
        do {
            $number = 'LMS-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . date('Y');
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    public function verifyCertificate(string $number): ?Certificate
    {
        return Certificate::with(['user', 'course'])
            ->where('certificate_number', trim($number))
            ->first();
    }

    public function generatePdf(Certificate $certificate)
    {
        $certificate->load(['user', 'course.category']);

        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate,
            'student' => $certificate->user,
            'course' => $certificate->course,
            'issueDate' => $certificate->issue_date->format('F d, Y'),
            'certificateNumber' => $certificate->certificate_number,
        ])->setPaper('a4', 'landscape');

        return $pdf;
    }

    public function downloadPdf(Certificate $certificate): Response
    {
        $pdf = $this->generatePdf($certificate);
        $fileName = 'Certificate-' . Str::slug($certificate->course->title) . '-' . $certificate->certificate_number . '.pdf';
        return $pdf->download($fileName);
    }

    public function streamPdf(Certificate $certificate): Response
    {
        $pdf = $this->generatePdf($certificate);
        return $pdf->stream('certificate.pdf');
    }
}
