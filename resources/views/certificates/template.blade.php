<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>সনদপত্র - {{ $certificateNumber }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
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
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .presented-to {
            font-size: 13px;
            font-style: italic;
            color: #64748b;
            margin-bottom: 10px;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e1b4b;
            text-decoration: underline;
            margin-bottom: 12px;
        }
        .description {
            font-size: 12px;
            color: #475569;
            max-width: 650px;
            margin: 0 auto 12px auto;
            line-height: 1.6;
        }
        .course-title {
            font-size: 22px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 25px;
        }
        .footer-table {
            width: 100%;
            margin-top: 25px;
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
            padding: 5px 14px;
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
            <div class="academy-name">{{ config('app.name', 'সহজ এলএমএস') }}</div>
            <div class="cert-title">Certificate of Completion</div>
            <div class="subtitle">Academic Quality Assurance &amp; Certification Council</div>

            <div class="presented-to">This certificate is proudly awarded to / গর্বের সাথে প্রদান করা হচ্ছে</div>
            <div class="student-name">{{ $student->name }}</div>

            <div class="description">
                for successfully completing all curriculum modules, projects, and passing the required certification examination for:
            </div>

            <div class="course-title">{{ $course->title }}</div>

            <table class="footer-table">
                <tr>
                    <td style="text-align: left; width: 33%;">
                        <div class="signature-line"></div>
                        <strong>Issue Date / ইস্যুর তারিখ:</strong> {{ $issueDate }}<br>
                        <strong>Certificate ID / সনদ নং:</strong> {{ $certificateNumber }}
                    </td>
                    <td style="text-align: center; width: 34%;">
                        <div style="font-size: 10px; color: #16a34a; font-weight: bold;">
                            [VERIFIED AUTHENTIC]<br>
                            যাচাই পোর্টাল: {{ url('/verify-certificate') }}
                        </div>
                    </td>
                    <td style="text-align: right; width: 33%;">
                        <div class="signature-line" style="margin-left: auto;"></div>
                        <strong>Academic Director</strong><br>
                        {{ config('app.name', 'সহজ এলএমএস') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
