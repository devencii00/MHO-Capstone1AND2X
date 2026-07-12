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
                <span>Txn #<span class="receipt-txn-id"></span></span>
                <span id="receiptMeta">·  -</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-4"></div>

        {{-- ===== PATIENT / DOCTOR ===== --}}
        <div class="space-y-1 text-[0.85rem]">
            <div class="flex justify-between gap-3">
                <span class="text-slate-500">Patient</span>
                <span class="receipt-patient font-semibold text-slate-900 text-right">-</span>
            </div>
            <div class="flex justify-between gap-3">
                <span class="text-slate-500">Doctor</span>
                <span class="receipt-doctor font-semibold text-slate-900 text-right">-</span>
            </div>
        </div>

        {{-- ===== SERVICES ===== --}}
        <div class="border-t border-dashed border-slate-300 mt-4 pt-3">
            <div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Services</div>
            <div class="receipt-services space-y-1 text-[0.85rem] receipt-figures">
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
                <span class="receipt-gross text-slate-700">PHP 0.00</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Discount Type</span>
                <span class="receipt-discount-type text-slate-700">none</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Discount Amount</span>
                <span class="receipt-discount-amount text-slate-700">PHP 0.00</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 mt-3 pt-3 receipt-figures">
            <div class="flex justify-between items-baseline">
                <span class="text-sm font-bold text-slate-900">Net Amount</span>
                <span class="receipt-net text-base font-bold text-slate-900">PHP 0.00</span>
            </div>
        </div>

        <div class="mt-3 space-y-1 text-[0.85rem] receipt-figures">
            <div class="flex justify-between">
                <span class="text-slate-500">Payment Mode</span>
                <span class="receipt-mode text-slate-700 capitalize">cash</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Transaction Date</span>
                <span class="receipt-date text-slate-700">-</span>
            </div>
        </div>

        {{-- ===== PAID / CHANGE ===== --}}
        <div class="border-t-2 border-dashed border-slate-400 mt-4 pt-3 receipt-figures">
            <div class="flex justify-between items-baseline">
                <span class="text-slate-600">Paid</span>
                <span class="receipt-paid receipt-accent text-lg font-bold">PHP 0.00</span>
            </div>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-slate-500">Change</span>
                <span class="receipt-change font-semibold text-slate-700">PHP 0.00</span>
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
