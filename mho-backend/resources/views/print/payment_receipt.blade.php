<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt - Opol Primary Healthcare</title>
    @vite('resources/css/app.css')
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 12mm;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .receipt-shell {
                border: 0 !important;
                box-shadow: none !important;
            }
        }

        * {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .letterhead-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .receipt-figures {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum" 1;
        }

        .receipt-torn {
            height: 14px;
            background-image:
                linear-gradient(-45deg, transparent 8px, #fff 8px),
                linear-gradient(45deg, transparent 8px, #fff 8px);
            background-size: 16px 32px;
            background-position: left bottom;
            background-repeat: repeat-x;
        }

        @media print {
            .receipt-torn { background-image:
                linear-gradient(-45deg, transparent 8px, #fff 8px),
                linear-gradient(45deg, transparent 8px, #fff 8px);
            }
        }

        /* State toggling: set data-state="finalized" or data-state="review" on #receiptRoot */
        #receiptRoot[data-state="finalized"] .state-review { display: none; }
        #receiptRoot[data-state="review"] .state-finalized { display: none; }
        #receiptRoot[data-state="review"] .receipt-accent { color: rgb(180 83 9); } /* amber-700 */
        #receiptRoot[data-state="finalized"] .receipt-accent { color: rgb(4 120 87); } /* emerald-700 */
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

    <div class="no-print sticky top-0 z-10 bg-white/90 backdrop-blur border-b border-slate-200 px-4 py-3">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-900">Payment Receipt</div>
            <button type="button" id="receiptPrintBtn" class="px-3 py-2 rounded-xl bg-slate-900 text-white text-[0.78rem] font-semibold hover:bg-slate-800">Print</button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto p-4 md:p-6">
        <div id="receiptError" class="hidden mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[0.85rem] text-red-700"></div>

        {{-- data-state controls title + accent color: "finalized" or "review" --}}
        <div id="receiptRoot" data-state="finalized" class="max-w-sm mx-auto">
            <div class="receipt-shell bg-white border border-slate-200 rounded-t-2xl px-6 pt-6 pb-5">

                {{-- ===== HEADER ===== --}}
                <div class="flex flex-col items-center text-center">
                    <img src="{{ asset('images/MHOLogoV2.png') }}" alt="Opol MHO logo" class="letterhead-logo mb-2">
                    <div class="text-[0.68rem] uppercase tracking-[0.3em] text-slate-400">Opol Primary Healthcare</div>
                    <div class="text-[0.68rem] text-slate-400">Municipal Health Office</div>

                    <div class="mt-3 receipt-accent">
                        <span class="state-finalized text-base font-bold tracking-wide">OFFICIAL RECEIPT</span>
                        <span class="state-review text-base font-bold tracking-wide">PAYMENT REVIEW</span>
                    </div>

                    <div class="mt-1 flex items-center gap-2 text-[0.72rem] text-slate-400">
                        <span>Txn #{{ $transactionId }}</span>
                        <span id="receiptMeta">·  -</span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 my-4"></div>

                {{-- ===== PATIENT / DOCTOR ===== --}}
                <div class="space-y-1 text-[0.85rem]">
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">Patient</span>
                        <span id="receiptPatient" class="font-semibold text-slate-900 text-right">-</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-500">Doctor</span>
                        <span id="receiptDoctor" class="font-semibold text-slate-900 text-right">-</span>
                    </div>
                </div>

                {{-- ===== SERVICES ===== --}}
                <div class="border-t border-dashed border-slate-300 mt-4 pt-3">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Services</div>
                    <div id="receiptServices" class="space-y-1 text-[0.85rem] receipt-figures">
                        <div class="flex justify-between text-slate-400">
                            <span>-</span>
                            <span>-</span>
                        </div>
                    </div>
                </div>

                {{-- ===== AMOUNTS ===== --}}
                <div class="border-t border-dashed border-slate-300 mt-4 pt-3 space-y-1 text-[0.85rem] receipt-figures">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Gross Amount</span>
                        <span id="receiptGross" class="text-slate-700">PHP 0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Discount Type</span>
                        <span id="receiptDiscountType" class="text-slate-700">none</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Discount Amount</span>
                        <span id="receiptDiscountAmount" class="text-slate-700">PHP 0.00</span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 mt-3 pt-3 receipt-figures">
                    <div class="flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Net Amount</span>
                        <span id="receiptNet" class="text-base font-bold text-slate-900">PHP 0.00</span>
                    </div>
                </div>

                <div class="mt-3 space-y-1 text-[0.85rem] receipt-figures">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Payment Mode</span>
                        <span id="receiptMode" class="text-slate-700 capitalize">cash</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Transaction Date</span>
                        <span id="receiptDate" class="text-slate-700">-</span>
                    </div>
                </div>

                {{-- ===== PAID / CHANGE ===== --}}
                <div class="border-t-2 border-dashed border-slate-400 mt-4 pt-3 receipt-figures">
                    <div class="flex justify-between items-baseline">
                        <span class="text-slate-600">Paid</span>
                        <span id="receiptPaid" class="receipt-accent text-lg font-bold">PHP 0.00</span>
                    </div>
                    <div class="flex justify-between items-baseline mt-1">
                        <span class="text-slate-500">Change</span>
                        <span id="receiptChange" class="font-semibold text-slate-700">PHP 0.00</span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 mt-4 pt-3 text-center">
                    <div class="state-finalized text-[0.72rem] text-slate-400">Thank you for your payment!</div>
                    <div class="state-review text-[0.72rem] text-amber-700">Please verify before confirming.</div>
                </div>
            </div>

            {{-- torn perforated edge --}}
            <div class="receipt-torn" aria-hidden="true"></div>
        </div>
    </div>
</body>
</html>