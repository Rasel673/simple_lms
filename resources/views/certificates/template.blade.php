<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion - {{ $certificateNumber }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 30px;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .outer-border {
            border: 8px solid #0f172a;
            padding: 8px;
            background-color: #ffffff;
            height: 94%;
            box-sizing: border-box;
        }
        .inner-border {
            border: 2px solid #cbd5e1;
            padding: 35px 45px;
            height: 94%;
            box-sizing: border-box;
            text-align: center;
            position: relative;
        }
        .academy-name {
            font-size: 20px;
            font-weight: bold;
            color: #4338ca;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .cert-title {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .subtitle {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }
        .presented-to {
            font-size: 14px;
            font-style: italic;
            color: #64748b;
            margin-bottom: 12px;
        }
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #1e1b4b;
            text-decoration: underline;
            margin-bottom: 15px;
        }
        .description {
            font-size: 13px;
            color: #475569;
            max-width: 650px;
            margin: 0 auto 15px auto;
            line-height: 1.6;
        }
        .course-title {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 30px;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: bottom;
            font-size: 11px;
            color: #64748b;
        }
        .signature-line {
            border-top: 1px solid #94a3b8;
            width: 180px;
            margin-bottom: 5px;
        }
        .gold-badge {
            display: inline-block;
            background-color: #d97706;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 6px 14px;
            border-radius: 20px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="outer-border">
        <div class="inner-border">
            <div class="gold-badge">Official Certified Achievement</div>
            <div class="academy-name">{{ config('app.name', 'LearnHub LMS') }}</div>
            <div class="cert-title">Certificate of Completion</div>
            <div class="subtitle">Academic Quality Assurance &amp; Certification Directorate</div>

            <div class="presented-to">This certificate is awarded to</div>
            <div class="student-name">{{ $student->name }}</div>

            <div class="description">
                in recognition of successfully completing all required video lecture modules, practical exercises, and passing the comprehensive examination for the course:
            </div>

            <div class="course-title">{{ $course->title }}</div>

            <table class="footer-table">
                <tr>
                    <td style="text-align: left; width: 33%;">
                        <div class="signature-line"></div>
                        <strong>Date of Issue:</strong> {{ $issueDate }}<br>
                        <strong>Certificate ID:</strong> {{ $certificateNumber }}
                    </td>
                    <td style="text-align: center; width: 34%;">
                        <div style="font-size: 10px; color: #16a34a; font-weight: bold;">
                            [VERIFIED AUTHENTIC]<br>
                            Verification Portal: {{ url('/verify-certificate') }}
                        </div>
                    </td>
                    <td style="text-align: right; width: 33%;">
                        <div class="signature-line" style="margin-left: auto;"></div>
                        <strong>Academic Director</strong><br>
                        LearnHub Learning Board
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
