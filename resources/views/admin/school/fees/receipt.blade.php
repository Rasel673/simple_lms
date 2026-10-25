<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ফি আদায়ের মানি রসিদ - {{ $invoice->invoice_number }}</title>
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
            .receipt-sheet { box-shadow: none !important; border: none !important; }
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
                মানি রসিদ প্রিন্ট করুন
            </button>
        </div>
    </div>

    <!-- Official Dual Receipt (Student Copy & Office Copy) -->
    <div class="receipt-sheet max-w-4xl w-full space-y-8">
        @php
            $copies = [
                ['title' => 'শিক্ষার্থী কপি (Student Copy)', 'badge_color' => 'bg-blue-100 text-blue-800'],
                ['title' => 'অফিস কপি (Office Copy)', 'badge_color' => 'bg-emerald-100 text-emerald-800'],
            ];
        @endphp

        @foreach($copies as $copy)
            <div class="bg-white border-2 border-dashed border-slate-300 rounded-2xl p-6 shadow-sm relative">
                <!-- Header -->
                <div class="flex items-start justify-between border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $schoolName }}</h2>
                        <p class="text-[11px] text-slate-500">{{ $schoolAddress }} | ফোন: {{ $schoolPhone }}</p>
                    </div>

                    <div class="text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $copy['badge_color'] }}">
                            {{ $copy['title'] }}
                        </span>
                        <div class="text-xs font-mono font-bold text-slate-700 mt-1">রসিদ নং: {{ $invoice->invoice_number }}</div>
                        <div class="text-[10px] text-slate-400">তারিখ: {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('d M, Y') : \Carbon\Carbon::now()->format('d M, Y') }}</div>
                    </div>
                </div>

                <!-- Student Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 py-3 text-xs border-b border-slate-100">
                    <div>
                        <span class="text-slate-400 block text-[10px]">শিক্ষার্থীর নাম:</span>
                        <span class="font-bold text-slate-900">{{ $invoice->student->name ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">শ্রেণি ও শাখা:</span>
                        <span class="font-semibold text-slate-800">{{ $invoice->student->academicClass->name ?? '' }} ({{ $invoice->student->section->name ?? 'A' }})</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">রোল নম্বর:</span>
                        <span class="font-bold text-slate-900">#{{ $invoice->student->roll_number ?? '' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">ভর্তি নম্বর:</span>
                        <span class="font-mono text-slate-700">{{ $invoice->student->admission_number ?? '' }}</span>
                    </div>
                </div>

                <!-- Fee Line Items Table -->
                <div class="py-3">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 text-[10px] uppercase">
                            <tr>
                                <th class="py-2 px-3">ফি বিবরণ (Fee Description)</th>
                                <th class="py-2 px-3 text-center">নির্ধারিত ফি</th>
                                <th class="py-2 px-3 text-center">পরিশোধিত টাকা</th>
                                <th class="py-2 px-3 text-right">অবশিষ্ট বকেয়া</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2.5 px-3">
                                    <div class="font-bold text-slate-800">{{ $invoice->feeType->name ?? 'ফি' }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $invoice->title }}</div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-bold text-slate-700">৳{{ number_format($invoice->amount, 2) }}</td>
                                <td class="py-2.5 px-3 text-center font-bold text-emerald-600">৳{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td class="py-2.5 px-3 text-right font-bold text-rose-600">৳{{ number_format(max(0, $invoice->amount - $invoice->paid_amount), 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="border-t-2 border-slate-300 font-bold bg-slate-50/50">
                            <tr>
                                <td class="py-2 px-3 text-right uppercase text-[10px]">সর্বমোট আদায়:</td>
                                <td colspan="2" class="py-2 px-3 text-center text-sm font-black text-emerald-700">৳{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td class="py-2 px-3 text-right text-xs">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $invoice->status === 'paid' ? 'সম্পূর্ণ পরিশোধিত' : 'আংশিক/বকেয়া' }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Payment Details & Signatures -->
                <div class="flex items-end justify-between pt-6 text-xs">
                    <div class="text-[10px] text-slate-500 space-y-0.5">
                        <div>পেমেন্ট মেথড: <span class="font-semibold text-slate-700">{{ $invoice->payment_method ?? 'Cash' }}</span></div>
                        @if($invoice->transaction_id)
                            <div>ট্রানজ্যাকশন আইডি: <span class="font-mono text-slate-700">{{ $invoice->transaction_id }}</span></div>
                        @endif
                        <div class="italic text-slate-400">* কম্পিউটার জেনারেটেড রসিদ, সিল ও স্বাক্ষর ছাড়া কার্যকর নয়।</div>
                    </div>

                    <div class="text-center">
                        <div class="border-t border-slate-400 pt-1 font-semibold text-slate-800 px-6">
                            হিসাবরক্ষক / আদায়কারীর স্বাক্ষর
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>
