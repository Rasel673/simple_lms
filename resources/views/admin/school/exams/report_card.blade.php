<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>একাডেমিক প্রগ্রেস রিপোর্ট কার্ড - {{ $student->name }} ({{ $student->admission_number }})</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Hind Siliguri', 'Inter', system-ui, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { box-shadow: none !important; border: 2px solid #000 !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center justify-start antialiased text-slate-800">

    <!-- Action Bar -->
    <div class="no-print max-w-4xl w-full mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow-xs border border-slate-200">
        <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            পেছনে যান
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                রিপোর্ট কার্ড প্রিন্ট করুন
            </button>
        </div>
    </div>

    <!-- Official Report Card Sheet -->
    <div class="print-card max-w-4xl w-full bg-white border border-slate-300 rounded-2xl p-8 shadow-lg space-y-6">
        <!-- School Header -->
        <div class="text-center border-b-2 border-slate-800 pb-5">
            <div class="flex items-center justify-center gap-3 mb-1">
                <div class="w-12 h-12 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xl">
                    {{ mb_substr($schoolName, 0, 1) }}
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $schoolName }}</h1>
            </div>
            <p class="text-xs text-slate-600">{{ $schoolAddress }} | ফোন: {{ $schoolPhone }}</p>
            <div class="mt-3 inline-block px-4 py-1 rounded-full bg-slate-900 text-white font-bold text-xs uppercase tracking-wider">
                একাডেমিক প্রগ্রেস রিপোর্ট কার্ড (ACADEMIC REPORT CARD)
            </div>
            <p class="text-xs font-semibold text-slate-700 mt-1">{{ $exam->name }} - শিক্ষাবর্ষ: {{ $exam->session_year }}</p>
        </div>

        <!-- Student Information Grid -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-500 block">শিক্ষার্থীর নাম:</span>
                <span class="font-bold text-slate-900 text-sm">{{ $student->name }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">ভর্তি নম্বর:</span>
                <span class="font-mono font-bold text-slate-800">{{ $student->admission_number }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">শ্রেণি ও শাখা:</span>
                <span class="font-bold text-slate-800">{{ $student->academicClass->name ?? '' }} ({{ $student->section->name ?? 'A' }})</span>
            </div>
            <div>
                <span class="text-slate-500 block">রোল নম্বর:</span>
                <span class="font-bold text-slate-900 text-sm">#{{ $student->roll_number }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">গ্রুপ / বিভাগ:</span>
                <span class="font-semibold text-slate-800">{{ $student->group }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">অভিভাবকের নাম:</span>
                <span class="font-semibold text-slate-800">{{ $student->guardian_name ?? '—' }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">যোগাযোগ:</span>
                <span class="font-semibold text-slate-800">{{ $student->guardian_phone ?? '—' }}</span>
            </div>
            <div>
                <span class="text-slate-500 block">ফলাফল প্রকাশের তারিখ:</span>
                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::now()->format('d M, Y') }}</span>
            </div>
        </div>

        <!-- Marks Details Table -->
        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-800 font-bold uppercase text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2.5">ক্রম</th>
                        <th class="px-4 py-2.5">বিষয়ের নাম (Subject)</th>
                        <th class="px-4 py-2.5 text-center">পূর্ণমান</th>
                        <th class="px-4 py-2.5 text-center">পাস নম্বর</th>
                        <th class="px-4 py-2.5 text-center">প্রাপ্ত নম্বর</th>
                        <th class="px-4 py-2.5 text-center">লেটার গ্রেড</th>
                        <th class="px-4 py-2.5 text-center">গ্রেড পয়েন্ট (GP)</th>
                        <th class="px-4 py-2.5">মন্তব্য</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($subjects as $index => $sub)
                        @php $m = $marks->get($sub->id); @endphp
                        <tr class="{{ $m && $m->grade === 'F' ? 'bg-rose-50/50' : '' }}">
                            <td class="px-4 py-2.5 text-slate-500 font-mono">{{ $index + 1 }}</td>
                            <td class="px-4 py-2.5 font-semibold text-slate-900">{{ $sub->name }}</td>
                            <td class="px-4 py-2.5 text-center">{{ (int)$sub->total_marks }}</td>
                            <td class="px-4 py-2.5 text-center text-slate-500">{{ (int)$sub->pass_marks }}</td>
                            <td class="px-4 py-2.5 text-center font-bold text-slate-900">{{ $m ? $m->marks_obtained : '0' }}</td>
                            <td class="px-4 py-2.5 text-center font-bold {{ $m && $m->grade === 'F' ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $m ? $m->grade : 'F' }}
                            </td>
                            <td class="px-4 py-2.5 text-center font-bold font-mono">{{ $m ? number_format($m->grade_point, 2) : '0.00' }}</td>
                            <td class="px-4 py-2.5 text-slate-500">{{ $m ? $m->remarks : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 font-bold border-t-2 border-slate-300">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right uppercase">সর্বমোট প্রাপ্ত নম্বর ও জিপিএ:</td>
                        <td class="px-4 py-3 text-center text-sm font-black text-slate-900">{{ $totalMarks }}</td>
                        <td class="px-4 py-3 text-center text-sm font-black text-amber-700">{{ $finalGrade }}</td>
                        <td class="px-4 py-3 text-center text-sm font-black font-mono text-blue-700">{{ number_format($gpa, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $hasFailed ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $hasFailed ? 'অনুত্তীর্ণ (Failed)' : 'উত্তীর্ণ (PASSED)' }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Grading Scale Legend & Signatures -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div class="border border-slate-200 rounded-xl p-3 text-[10px]">
                <span class="font-bold text-slate-800 uppercase block mb-1">গ্রেডিং স্কেল নির্দেশিকা</span>
                <div class="grid grid-cols-2 gap-x-2 gap-y-0.5 text-slate-600">
                    <span>৮০ - ১০০: <strong>A+ (5.00)</strong></span>
                    <span>৭০ - ৭৯: <strong>A (4.00)</strong></span>
                    <span>৬০ - ৬৯: <strong>A- (3.50)</strong></span>
                    <span>৫০ - ৫৯: <strong>B (3.00)</strong></span>
                    <span>৪০ - ৪৯: <strong>C (2.00)</strong></span>
                    <span>৩৩ - ৩৯: <strong>D (1.00)</strong></span>
                    <span>০০ - ৩২: <strong class="text-rose-600">F (0.00)</strong></span>
                </div>
            </div>

            <div class="md:col-span-2 border border-slate-200 rounded-xl p-3 text-xs flex flex-col justify-between">
                <div>
                    <span class="font-bold text-slate-800 block mb-1">শিক্ষকের সার্বিক মূল্যায়ন ও মন্তব্য:</span>
                    <p class="text-slate-600 italic">
                        {{ $hasFailed ? 'বিশেষ যত্ন ও নিয়মিত উপস্থিতি প্রয়োজন।' : 'ধারাবাহিক প্রচেষ্টা ও পড়ালেখায় চমৎকার অগ্রগতি পরিলক্ষিত হয়েছে।' }}
                    </p>
                </div>
                <div class="text-[10px] text-slate-400 mt-2">
                    * এটি একটি কম্পিউটার জেনারেটেড অফিসিয়াল প্রগ্রেস রিপোর্ট কার্ড।
                </div>
            </div>
        </div>

        <!-- Signature Lines -->
        <div class="pt-16 grid grid-cols-3 gap-8 text-center text-xs">
            <div>
                <div class="border-t border-slate-400 pt-1 font-semibold text-slate-700">শ্রেণি শিক্ষকের স্বাক্ষর</div>
            </div>
            <div>
                <div class="border-t border-slate-400 pt-1 font-semibold text-slate-700">অভিভাবকের স্বাক্ষর</div>
            </div>
            <div>
                <div class="border-t border-slate-400 pt-1 font-bold text-slate-900">প্রধান শিক্ষকের স্বাক্ষর ও সিল</div>
            </div>
        </div>
    </div>

</body>
</html>
