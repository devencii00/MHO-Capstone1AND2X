<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Billing & Transactions</h1>
        <p class="text-sm text-slate-500">Record payments and review transaction history.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden">
    <div class="grid grid-cols-2 border-b border-slate-200">
        <button id="receptionBillingTabRecord" type="button" class="px-4 py-3 text-xs font-semibold text-white bg-green-500 border-b-2 border-green-600">
            Record payment
        </button>
        <button id="receptionBillingTabTransactions" type="button" class="px-4 py-3 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200">
            Transactions record
        </button>
    </div>

    <div id="receptionBillingPanelRecord" class="p-5">

        <div id="receptionPaymentError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionPaymentSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

        <form id="receptionPaymentForm" class="space-y-4">
            <div>
                <label for="reception_payment_appointment_display" class="block text-sm font-semibold text-slate-700 mb-1.5">Appointment</label>
                <div class="relative">
                    <input id="reception_payment_appointment_display" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-4 py-3 pr-28 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select an appointment">
                    <input id="reception_payment_appointment_id" type="hidden" required>
                    <button id="receptionPaymentBrowseBtn" type="button" class="absolute inset-y-1.5 right-1.5 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-100">Browse</button>
                </div>
            </div>

            <div id="receptionPaymentAppointmentPreview" class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm min-h-[220px] flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Appointment Summary</span>
                    <span id="receptionPaymentApptTypeBadge" class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200"></span>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div>
                        <span class="block text-xs text-slate-500 mb-0.5">Patient</span>
                        <span id="receptionPaymentSummaryPatient" class="text-base font-semibold text-slate-900">-</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 mb-0.5">Doctor</span>
                        <span id="receptionPaymentSummaryDoctor" class="text-base font-semibold text-slate-900">-</span>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-2 mb-2 flex-1">
                    <span class="block text-xs text-slate-500 mb-1.5">Services</span>
                    <div id="receptionPaymentServicesDisplay" class="text-sm text-slate-400">Select an appointment first</div>
                </div>
                <div class="border-t border-slate-100 pt-3 flex items-center justify-between gap-4">
                    <div class="text-center flex-1">
                        <span class="block text-xs text-slate-500">Original</span>
                        <span id="receptionPaymentAmountDisplay" class="block text-base font-bold text-slate-800">PHP 0.00</span>
                    </div>
                    <div class="text-center flex-1 border-x border-slate-100">
                        <span class="block text-xs text-slate-500">Net</span>
                        <span id="receptionPaymentNetAmountDisplay" class="block text-base font-bold text-emerald-700">PHP 0.00</span>
                    </div>
                    <div class="text-center flex-1">
                        <span class="block text-xs text-slate-500">Change</span>
                        <span id="receptionPaymentChangeDisplay" class="block text-base font-bold text-slate-800">PHP 0.00</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                <div class="text-sm font-semibold text-slate-800 uppercase tracking-wider mb-3">Patient Payment</div>
                <div class="mb-3">
                    <label for="reception_payment_money_paid" class="block text-sm font-semibold text-slate-700 mb-1.5">Money received</label>
                    <input id="reception_payment_money_paid" type="text" inputmode="decimal" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-lg text-slate-900 font-bold focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="0.00">
                    <div id="reception_payment_money_paid_error" class="hidden mt-1 text-sm text-red-600"></div>
                </div>
                <div class="mb-3">
                    <button id="receptionPaymentToggleDiscount" type="button" class="text-sm font-semibold text-green-700 hover:text-green-800 transition-colors">+ Add discount</button>
                </div>
                <div id="receptionPaymentDiscountWrap" class="hidden mb-3 grid grid-cols-2 gap-3">
                    <div>
                        <label for="reception_payment_discount_type" class="block text-sm font-semibold text-slate-700 mb-1.5">Discount type</label>
                        <select id="reception_payment_discount_type" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="none" selected>No discount</option>
                            <option value="pwd">PWD (15%)</option>
                            <option value="senior">Senior (5%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Discount</label>
                        <div id="receptionPaymentDiscountAmountDisplay" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">PHP 0.00</div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Payment mode</label>
                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">Cash</div>
                </div>
                <button id="receptionPaymentSubmit" type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-green-600 text-white text-base font-bold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600 shadow-sm">
                    <span id="receptionPaymentSubmitSpinner" class="hidden w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="receptionPaymentSubmitLabel">Record payment</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Appointment Selection Modal -->
    <div id="receptionPaymentAppointmentModal" class="hidden fixed inset-0 z-[70] bg-slate-900/50 items-center justify-center p-4">
        <div class="w-full max-w-4xl h-[90vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Select Today's Appointment</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Choose an appointment to record payment for.</p>
                </div>
                <button id="receptionPaymentApptModalClose" type="button" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <x-lucide-x class="w-4 h-4" />
                </button>
            </div>
            <div class="flex flex-1 min-h-0">
                <!-- Left panel: list of today's appointments -->
                <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
                    <div class="px-4 py-2 border-b border-slate-100 shrink-0 bg-slate-50/50 flex items-center gap-2">
                        <input id="receptionPaymentApptSearch" type="text" class="flex-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by name, email, or queue code...">
                        <button type="button" id="recPaymentApptRefreshBtn" class="shrink-0 inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                            <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                            Refresh
                        </button>
                    </div>
                    <div id="receptionPaymentApptList" class="flex-1 overflow-y-auto p-2 space-y-1">
                        <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading today's appointments...</div>
                    </div>
                </div>
                <!-- Right panel: details of selected appointment -->
                <div class="w-1/2 flex flex-col min-h-0 bg-slate-50/30">
                    <div class="px-4 py-3 border-b border-slate-200 shrink-0 bg-white">
                        <div class="text-sm font-semibold text-slate-900">Appointment Details</div>
                    </div>
                    <div id="receptionPaymentApptDetail" class="flex-1 overflow-y-auto p-4">
                        <div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment from the list.</div>
                    </div>
                    <div class="px-4 py-3 border-t border-slate-200 shrink-0 bg-white flex items-center justify-end gap-2.5">
                        <button type="button" id="receptionPaymentApptModalCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="button" id="receptionPaymentApptModalSelect" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed" disabled>Select Appointment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="receptionBillingPanelTransactions" class="hidden p-5">
        <div class="flex items-center justify-end mb-3 gap-3">
            <div class="flex items-center gap-2">
                <button id="receptionTransactionsTodayOnlyBtn" type="button" class="shrink-0 inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.75rem] font-semibold text-slate-700 hover:bg-slate-50">Show today only</button>
                <button type="button" id="recTransRefreshBtn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                    <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                    Refresh
                </button>
            </div>
        </div>

        <div id="receptionTransactionsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

        <div class="grid gap-3 grid-cols-1 md:grid-cols-6 items-start mb-4">
            <div class="md:col-span-2 min-w-0">
                <label for="receptionTransactionsSearch" class="block text-[0.7rem] text-slate-600 mb-1">Search</label>
                <input id="receptionTransactionsSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by patient name or ref #">
            </div>
            <div class="min-w-0">
                <label for="receptionTransactionsType" class="block text-[0.7rem] text-slate-600 mb-1">Type</label>
                <select id="receptionTransactionsType" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">All types</option>
                    <option value="walk-in">Walk-in</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>
            <div class="min-w-0">
                <label for="receptionTransactionsStatus" class="block text-[0.7rem] text-slate-600 mb-1">Status</label>
                <select id="receptionTransactionsStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">All statuses</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="min-w-0">
                <label for="receptionTransactionsDate" class="block text-[0.7rem] text-slate-600 mb-1">Date</label>
                <input id="receptionTransactionsDate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            </div>
            <div class="min-w-0">
                <label for="receptionTransactionsServiceSearch" class="block text-[0.7rem] text-slate-600 mb-1">Service</label>
                <div class="relative">
                    <input id="receptionTransactionsServiceSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="All services" autocomplete="off">
                    <input id="receptionTransactionsServiceId" type="hidden">
                    <div id="receptionTransactionsServiceResults" class="hidden absolute left-0 right-0 top-full mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto overscroll-contain z-50"></div>
                </div>
            </div>
        </div>

        <div class="w-full" style="display:grid;">
            <div class="rounded-2xl border border-slate-200 overflow-hidden">
               <div class="overflow-x-auto overflow-y-auto scrollbar-hidden mb-4 h-[470px]">
                    <table class="text-xs" style="min-width:700px;width:100%;table-layout:auto;">
                        <thead class="bg-slate-50 text-slate-600 sticky top-0">
                            <tr>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Date</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Reference</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Patient</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Type</th>
                                <th class="text-right px-3 py-2 font-semibold whitespace-nowrap">Net</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Status</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody id="receptionTransactionsTableBody" class="divide-y divide-slate-100 bg-white"></tbody>
                    </table>
                </div>
                <div id="receptionTransactionsPagination" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-white border-t border-slate-100"></div>
            </div>
        </div>
    </div>
</div>
<!-- Transaction History Modal -->
<div id="receptionTxHistoryOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">
        <!-- History list (left) -->
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Transaction History</div>
                    <div id="receptionTxHistorySubtitle" class="text-[0.72rem] text-slate-500">Loading…</div>
                </div>
                <button type="button" id="receptionTxHistoryClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div id="receptionTxHistoryBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>
            </div>
        </div>
        <!-- Detail panel (right) -->
        <div class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="text-sm font-semibold text-slate-900">Transaction Details</div>
            </div>
            <div id="receptionTxHistoryDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a transaction to view details.</div>
            </div>
        </div>
    </div>
</div>
<div id="receptionPaymentReviewOverlay" class="hidden fixed inset-0 z-[80] bg-black/70 items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-100 flex flex-col" style="max-height:90vh">
        <!-- Header (fixed) -->
        <div class="flex-shrink-0 px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shadow-sm flex-shrink-0">
                    <x-lucide-receipt class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="receptionPaymentReviewTitle" class="text-base font-semibold text-slate-800 tracking-tight">Review Payment Details</h3>
                    <p id="receptionPaymentReviewSubtitle" class="text-xs text-slate-500 mt-0.5">Please verify all payment information before confirming</p>
                </div>
                <button id="receptionPaymentReviewClose" type="button" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>
        </div>

        <!-- Receipt content area (scrollable) -->
        <div class="flex-1 overflow-y-auto px-5 py-4 bg-white">
            <div id="receptionPaymentReviewContent" class="bg-white rounded-xl border-2 border-slate-200 p-5 text-sm text-slate-700 font-mono leading-relaxed">
                <!-- Dynamic receipt content will be injected here -->
                <div class="text-center text-slate-400 py-4">Loading receipt data...</div>
            </div>
        </div>

        <!-- Footer buttons (fixed) -->
        <div id="receptionPaymentReviewFooter" class="flex-shrink-0 px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionPaymentReviewCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150">Cancel</button>
            <button type="button" id="receptionPaymentReviewConfirm" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 shadow-sm transition-all duration-150">Confirm Payment</button>
        </div>
    </div>
</div>

<!-- Separate Receipt Modal (after confirmation) -->
<div id="receptionPaymentReceiptOverlay" class="hidden fixed inset-0 z-[80] bg-black/70 items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-100 flex flex-col" style="max-height:90vh">
        <!-- Header (fixed) -->
        <div class="flex-shrink-0 px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shadow-sm flex-shrink-0">
                    <x-lucide-receipt class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="receptionPaymentReceiptTitle" class="text-base font-semibold text-slate-800 tracking-tight">Payment Receipt</h3>
                    <p id="receptionPaymentReceiptSubtitle" class="text-xs text-slate-500 mt-0.5">Payment has been recorded successfully</p>
                </div>
                <button id="receptionPaymentReceiptClose" type="button" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex-shrink-0">
                    <x-lucide-x class="w-[18px] h-[18px]" />
                </button>
            </div>
        </div>

        <!-- Receipt content area (scrollable) -->
        <div class="flex-1 overflow-y-auto px-5 py-4 bg-white">
            <div id="receptionPaymentReceiptContent" class="bg-white rounded-xl border-2 border-slate-200 p-5 text-sm text-slate-700 font-mono leading-relaxed">
                <div class="text-center text-slate-400 py-4">Loading receipt data...</div>
            </div>
        </div>

        <!-- Footer buttons (fixed) -->
        <div id="receptionPaymentReceiptFooter" class="flex-shrink-0 px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionPaymentReceiptCloseBtn" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150">Close</button>
            <button type="button" id="receptionPaymentReceiptPrintBtn" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 shadow-sm transition-all duration-150"><x-lucide-printer class="w-[18px] h-[18px]" /> Print</button>
        </div>
    </div>
</div>

<style>
    /* ── Receipt card styles (shared with print/payment_receipt.blade.php) ── */
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
    /* State toggling: set data-state="finalized" or data-state="review" on #receiptRoot */
    #receiptRoot[data-state="finalized"] .state-review { display: none; }
    #receiptRoot[data-state="review"] .state-finalized { display: none; }
    #receiptRoot[data-state="review"] .receipt-accent { color: rgb(180 83 9); }
    #receiptRoot[data-state="finalized"] .receipt-accent { color: rgb(4 120 87); }

    * {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    @media print {
        @page { size: A4 portrait; margin: 14mm 12mm; }

        body * { visibility: hidden; }
        body { background: white !important; }

        /* ── Receipt modal printing ── */
        #receptionPaymentReceiptOverlay.flex {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            z-index: 9999 !important;
            background: white !important;
            backdrop-filter: none !important;
            align-items: flex-start !important;
            justify-content: center !important;
            padding: 0.5in !important;
            visibility: visible !important;
        }
        #receptionPaymentReceiptOverlay.flex #receptionPaymentReceiptContent,
        #receptionPaymentReceiptOverlay.flex #receptionPaymentReceiptContent * { visibility: visible !important; }
        #receptionPaymentReceiptOverlay.flex .rounded-2xl > div:first-child { display: none !important; }
        #receptionPaymentReceiptOverlay.flex #receptionPaymentReceiptFooter { display: none !important; }
        #receptionPaymentReceiptOverlay.flex .rounded-2xl {
            box-shadow: none !important;
            border: none !important;
        }
        #receptionPaymentReceiptOverlay.flex .receipt-shell {
            border: 0 !important;
            box-shadow: none !important;
        }

        /* ── Transaction history detail printing ── */
        #receptionTxHistoryOverlay.flex {
            visibility: visible !important;
            background: white !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 0.5in !important;
            z-index: 9999 !important;
        }
        #receptionTxHistoryOverlay.flex > div {
            visibility: visible !important;
            width: 100% !important;
            max-width: 32rem !important;
            box-shadow: none !important;
            border: none !important;
            overflow: visible !important;
            background: transparent !important;
        }
        #receptionTxHistoryOverlay.flex > div > div:first-child { display: none !important; }
        #receptionTxHistoryOverlay.flex > div > div:last-child {
            visibility: visible !important;
            width: 100% !important;
            border: none !important;
            background: transparent !important;
        }
        #receptionTxHistoryOverlay.flex #receptionTxHistoryDetailBody {
            visibility: visible !important;
            overflow: visible !important;
            padding: 0 !important;
            background: transparent !important;
        }
        #receptionTxHistoryOverlay.flex #receptionTxHistoryDetailBody > div:first-child {
            visibility: visible !important;
        }
        #receptionTxHistoryOverlay.flex #receptionTxHistoryDetailBody button:not(.receipt-print-btn) {
            display: none !important;
        }
    }
</style>

{{-- Hidden receipt card template (cloned and populated by JS) --}}
<div id="receiptCardTemplate" class="hidden" aria-hidden="true">
    <x-receipt-card />
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var recordTab = document.getElementById('receptionBillingTabRecord')
        var txTab = document.getElementById('receptionBillingTabTransactions')
        var recordPanel = document.getElementById('receptionBillingPanelRecord')
        var txPanel = document.getElementById('receptionBillingPanelTransactions')

        var paymentError = document.getElementById('receptionPaymentError')
        var paymentSuccess = document.getElementById('receptionPaymentSuccess')
        var paymentForm = document.getElementById('receptionPaymentForm')
        var paymentSubmit = document.getElementById('receptionPaymentSubmit')
        var paymentSubmitSpinner = document.getElementById('receptionPaymentSubmitSpinner')
        var paymentSubmitLabel = document.getElementById('receptionPaymentSubmitLabel')

        var appointmentDisplay = document.getElementById('reception_payment_appointment_display')
        var appointmentIdInput = document.getElementById('reception_payment_appointment_id')
        var appointmentPreview = document.getElementById('receptionPaymentAppointmentPreview')
        var servicesDisplay = document.getElementById('receptionPaymentServicesDisplay')
        var amountDisplay = document.getElementById('receptionPaymentAmountDisplay')
        var discountDisplay = document.getElementById('receptionPaymentDiscountAmountDisplay')
        var netDisplay = document.getElementById('receptionPaymentNetAmountDisplay')
        var changeDisplay = document.getElementById('receptionPaymentChangeDisplay')
        var moneyPaidInput = document.getElementById('reception_payment_money_paid')
        var moneyPaidError = document.getElementById('reception_payment_money_paid_error')
        if (moneyPaidInput) {
            moneyPaidInput.addEventListener('input', function () {
                var raw = this.value.replace(/[^0-9.]/g, '')
                var parts = raw.split('.')
                if (parts.length > 2) parts = [parts[0], parts.slice(1).join('')]
                if (parts[0]) {
                    parts[0] = parseInt(parts[0], 10).toLocaleString('en-US')
                }
                this.value = parts[0] + (parts.length > 1 && parts[1] !== undefined ? '.' + parts[1] : '')
                // Clear inline error on input
                if (moneyPaidError) {
                    moneyPaidError.classList.add('hidden')
                    moneyPaidError.textContent = ''
                }
                refreshTotalsUI()
            })
        }
        var discountToggle = document.getElementById('receptionPaymentToggleDiscount')
        var discountWrap = document.getElementById('receptionPaymentDiscountWrap')
        var discountTypeSelect = document.getElementById('reception_payment_discount_type')

        var reviewOverlay = document.getElementById('receptionPaymentReviewOverlay')
        var reviewContent = document.getElementById('receptionPaymentReviewContent')
        var reviewTitle = document.getElementById('receptionPaymentReviewTitle')
        var reviewSubtitle = document.getElementById('receptionPaymentReviewSubtitle')
        var reviewCancel = document.getElementById('receptionPaymentReviewCancel')
        var reviewConfirm = document.getElementById('receptionPaymentReviewConfirm')
        var reviewCloseBtn = document.getElementById('receptionPaymentReviewClose')
        var reviewConfirmDefaultHtml = reviewConfirm ? reviewConfirm.innerHTML : ''
        var reviewResolver = null
        var reviewDelayTimer = null

        var receiptOverlay = document.getElementById('receptionPaymentReceiptOverlay')
        var receiptContent = document.getElementById('receptionPaymentReceiptContent')
        var receiptTitle = document.getElementById('receptionPaymentReceiptTitle')
        var receiptSubtitle = document.getElementById('receptionPaymentReceiptSubtitle')
        var receiptCloseBtn = document.getElementById('receptionPaymentReceiptCloseBtn')
        var receiptPrintBtn = document.getElementById('receptionPaymentReceiptPrintBtn')
        var receiptCloseX = document.getElementById('receptionPaymentReceiptClose')

        var apptModal = document.getElementById('receptionPaymentAppointmentModal')
        var apptModalClose = document.getElementById('receptionPaymentApptModalClose')
        var apptModalCancel = document.getElementById('receptionPaymentApptModalCancel')
        var apptModalSelect = document.getElementById('receptionPaymentApptModalSelect')
        var apptModalSearch = document.getElementById('receptionPaymentApptSearch')
        var apptModalRefreshBtn = document.getElementById('recPaymentApptRefreshBtn')
        var apptList = document.getElementById('receptionPaymentApptList')
        var apptDetail = document.getElementById('receptionPaymentApptDetail')
        var browseBtn = document.getElementById('receptionPaymentBrowseBtn')
        var todayAppointments = []
        var todayAppointmentsFiltered = []
        var apptModalSelectedAppt = null

        var txError = document.getElementById('receptionTransactionsError')
        var txSearch = document.getElementById('receptionTransactionsSearch')
        var txServiceSearch = document.getElementById('receptionTransactionsServiceSearch')
        var txServiceId = document.getElementById('receptionTransactionsServiceId')
        var txServiceResults = document.getElementById('receptionTransactionsServiceResults')
        var txSort = document.getElementById('receptionTransactionsSort')
        var txRefresh = document.getElementById('recTransRefreshBtn')
        var txTableBody = document.getElementById('receptionTransactionsTableBody')
        var txPagination = document.getElementById('receptionTransactionsPagination')

        var txAllRows = []
        var txCurrentPage = 1
        var txPerPage = 15
        var txVisibleCount = 5
        var txLastPage = 1
        var txTotal = 0
        var txSortOrder = 'latest'
        var txTodayBtn = document.getElementById('receptionTransactionsTodayOnlyBtn')
        var txType = document.getElementById('receptionTransactionsType')
        var txStatus = document.getElementById('receptionTransactionsStatus')
        var txDate = document.getElementById('receptionTransactionsDate')

        var selectedAppointment = null
        var transactionsSearchTimer = null
        var showDiscount = false
        var txTodayOnly = false
        var txServices = []
        var txServicesLoaded = false
        var txServicesLoading = false

        function setBillingTab(tab) {
            var isRecord = tab === 'record'
            if (recordPanel) recordPanel.classList.toggle('hidden', !isRecord)
            if (txPanel) txPanel.classList.toggle('hidden', isRecord)
            if (recordTab) {
                recordTab.classList.toggle('bg-green-500', isRecord)
                recordTab.classList.toggle('text-white', isRecord)
                recordTab.classList.toggle('border-b-2', isRecord)
                recordTab.classList.toggle('border-green-600', isRecord)
                recordTab.classList.toggle('bg-white', !isRecord)
                recordTab.classList.toggle('text-slate-900', !isRecord)
                recordTab.classList.toggle('hover:bg-slate-50', !isRecord)
            }
            if (txTab) {
                txTab.classList.toggle('bg-green-500', !isRecord)
                txTab.classList.toggle('text-white', !isRecord)
                txTab.classList.toggle('border-b-2', !isRecord)
                txTab.classList.toggle('border-green-600', !isRecord)
                txTab.classList.toggle('bg-white', isRecord)
                txTab.classList.toggle('text-slate-900', isRecord)
                txTab.classList.toggle('hover:bg-slate-50', isRecord)
            }
        }

        function normalizeText(v) {
            return String(v || '').toLowerCase().trim().replace(/\s+/g, ' ')
        }

        function escapeHtml(input) {
            return String(input == null ? '' : input)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function wordPrefixMatch(value, query) {
            var v = normalizeText(value)
            var q = normalizeText(query)
            if (!q) return true
            if (!v) return false
            if (v.indexOf(q) === 0) return true
            return v.split(/\s+/).some(function (part) { return part.indexOf(q) === 0 })
        }

        function showPaymentError(message) {
            if (moneyPaidError && message) {
                moneyPaidError.textContent = message
                moneyPaidError.classList.remove('hidden')
            } else if (moneyPaidError) {
                moneyPaidError.classList.add('hidden')
                moneyPaidError.textContent = ''
            }
            // Also clear any previous success
            if (paymentSuccess) {
                paymentSuccess.classList.add('hidden')
                paymentSuccess.textContent = ''
            }
        }

        function showPaymentSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function showTransactionsError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function money(value) {
            var n = parseFloat(value || 0)
            if (isNaN(n)) n = 0
            return 'PHP ' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        }

        function formatTime12h(hhmmss) {
            var t = String(hhmmss || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return t
            var parts = t.split(':')
            var h24 = parseInt(parts[0], 10)
            var m = parts[1]
            var ap = h24 >= 12 ? 'PM' : 'AM'
            var h12 = h24 % 12
            if (h12 === 0) h12 = 12
            return h12 + ':' + m + ' ' + ap
        }

        function currentSqlDatetime() {
            var now = new Date()
            var yyyy = now.getFullYear()
            var mm = String(now.getMonth() + 1).padStart(2, '0')
            var dd = String(now.getDate()).padStart(2, '0')
            var hh = String(now.getHours()).padStart(2, '0')
            var mi = String(now.getMinutes()).padStart(2, '0')
            var ss = String(now.getSeconds()).padStart(2, '0')
            return yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + mi + ':' + ss
        }

        function appointmentPatientName(appt) {
            var p = appt && appt.patient ? appt.patient : null
            if (!p) return 'Patient'
            var name = [p.firstname, p.middlename, p.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            if (!name) name = p.email || ''
            return name
        }

        function appointmentDoctorName(appt) {
            var d = appt && appt.doctor ? appt.doctor : null
            if (!d) return 'Doctor'
            var name = [d.firstname, d.middlename, d.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            if (!name) name = d.email || ''
            return name
        }

        function normalizeAppointmentType(value) {
            var raw = String(value || '').toLowerCase().trim()
            if (!raw) return ''
            raw = raw.replace(/[\s-]+/g, '_')
            if (raw === 'walk_in' || raw === 'walkin') return 'walk_in'
            if (raw === 'scheduled') return 'scheduled'
            return ''
        }

        function appointmentTypeLabel(appt) {
            var normalized = normalizeAppointmentType(appt && appt.appointment_type ? appt.appointment_type : '')
            if (normalized === 'walk_in') return 'walk-in'
            if (normalized === 'scheduled') return 'scheduled'
            return 'unknown'
        }

        function servicesFromAppointment(appt) {
            var list = appt && Array.isArray(appt.services) ? appt.services : []
            return list.map(function (s) {
                var name = s && s.service_name ? String(s.service_name).trim() : ''
                var description = s && s.description ? String(s.description).trim() : ''
                var price = s && s.price != null ? parseFloat(s.price) : 0
                if (isNaN(price)) price = 0
                return { name: name, description: description, price: price }
            }).filter(function (x) { return x.name !== '' })
        }

        function appointmentServicesHtml(appt) {
            var svcs = servicesFromAppointment(appt)
            if (!svcs.length) return ''
            return svcs.map(function (s) {
                var desc = s.description ? ' · ' + escapeHtml(s.description) : ''
                return '<div style="display:flex;justify-content:space-between;font-size:0.72rem;padding:1px 0;"><span>' + escapeHtml(s.name) + desc + '</span><span>' + escapeHtml(money(s.price)) + '</span></div>'
            }).join('')
        }

        function originalAmount(appt) {
            return servicesFromAppointment(appt).reduce(function (sum, s) { return sum + (parseFloat(s.price) || 0) }, 0)
        }

        function discountRate() {
            var type = discountTypeSelect && discountTypeSelect.value ? String(discountTypeSelect.value) : 'none'
            if (type === 'pwd') return 0.15
            if (type === 'pregnant') return 0.10
            if (type === 'senior') return 0.05
            return 0
        }

        function discountAmount(appt) {
            return originalAmount(appt) * discountRate()
        }

        function refreshTotalsUI() {
            var gross = originalAmount(selectedAppointment)
            var discount = discountAmount(selectedAppointment)
            var net = Math.max(0, gross - discount)
            if (amountDisplay) amountDisplay.textContent = money(gross)
            if (discountDisplay) discountDisplay.textContent = money(discount)
            if (netDisplay) netDisplay.textContent = money(net)
            // Update change display
            var paid = 0
            if (moneyPaidInput) {
                var raw = String(moneyPaidInput.value || '').replace(/[^0-9.]/g, '')
                paid = parseFloat(raw) || 0
            }
            if (changeDisplay) changeDisplay.textContent = money(Math.max(0, paid - net))
        }

        function resetAppointmentSelection() {
            selectedAppointment = null
            if (appointmentIdInput) appointmentIdInput.value = ''
            if (appointmentDisplay) appointmentDisplay.value = ''

            // Reset preview card to placeholder state
            var patientEl = document.getElementById('receptionPaymentSummaryPatient')
            var doctorEl = document.getElementById('receptionPaymentSummaryDoctor')
            var typeBadge = document.getElementById('receptionPaymentApptTypeBadge')
            if (patientEl) patientEl.textContent = '-'
            if (doctorEl) doctorEl.textContent = '-'
            if (typeBadge) typeBadge.textContent = ''
            if (servicesDisplay) servicesDisplay.innerHTML = '<span class="text-sm text-slate-400">Select an appointment first</span>'
            if (amountDisplay) amountDisplay.textContent = money(0)
            if (discountDisplay) discountDisplay.textContent = money(0)
            if (netDisplay) netDisplay.textContent = money(0)
            if (changeDisplay) changeDisplay.textContent = money(0)
            if (moneyPaidInput) moneyPaidInput.value = ''
        }

        function setAppointmentSelection(appt) {
            selectedAppointment = appt || null
            if (appointmentIdInput) {
                appointmentIdInput.value = appt && appt.appointment_id != null ? String(appt.appointment_id) : ''
            }
            if (!appt) {
                resetAppointmentSelection()
                return
            }

            if (appointmentDisplay) {
                var queueCode = appt && appt.queue && appt.queue.queue_code ? String(appt.queue.queue_code) : ('#' + String(appt.appointment_id))
                var patient = appointmentPatientName(appt)
                var fallback = ''
                if (!patient || patient === 'Patient' || patient.indexOf('User #') === 0) {
                    var p = appt && appt.patient ? appt.patient : null
                    fallback = p && p.email ? String(p.email) : ''
                    if (fallback.length > 18) fallback = fallback.slice(0, 15) + '...'
                }
                appointmentDisplay.value = queueCode + ' | ' + (fallback || patient)
            }

            var serviceRows = servicesFromAppointment(appt)
            if (servicesDisplay) {
                if (!serviceRows.length) {
                    servicesDisplay.textContent = 'No services linked to this appointment.'
                } else {
                    servicesDisplay.innerHTML = serviceRows.map(function (s) {
                        var desc = s.description ? '<span class="block text-[0.6rem] text-slate-400">' + escapeHtml(s.description) + '</span>' : ''
                        return '<div class="flex items-center justify-between gap-2 py-1 border-b border-slate-200/60 last:border-0"><span class="truncate">' + escapeHtml(s.name) + desc + '</span><span class="shrink-0 font-semibold">' + escapeHtml(money(s.price)) + '</span></div>'
                    }).join('')
                }
            }

            var gross = originalAmount(appt)
            if (appointmentPreview) {
                var patientEl = document.getElementById('receptionPaymentSummaryPatient')
                var doctorEl = document.getElementById('receptionPaymentSummaryDoctor')
                var typeBadge = document.getElementById('receptionPaymentApptTypeBadge')
                if (patientEl) patientEl.textContent = appointmentPatientName(appt)
                if (doctorEl) doctorEl.textContent = appointmentDoctorName(appt)
                if (typeBadge) typeBadge.textContent = appointmentTypeLabel(appt)
            }

            refreshTotalsUI()
        }

        function setPaymentSubmitting(isSubmitting) {
            if (paymentSubmit) paymentSubmit.disabled = !!isSubmitting
            if (paymentSubmitSpinner) paymentSubmitSpinner.classList.toggle('hidden', !isSubmitting)
            if (paymentSubmitLabel) paymentSubmitLabel.textContent = isSubmitting ? 'Saving...' : 'Record payment'
        }

        function cloneReceiptCard(txnId) {
            var tpl = document.getElementById('receiptCardTemplate')
            if (!tpl) return null
            // Clone the inner receipt card (first child of template wrapper)
            var inner = tpl.firstElementChild
            if (!inner) return null
            var clone = inner.cloneNode(true)
            // Set transaction ID
            var txnIdEl = clone.querySelector('.receipt-txn-id')
            if (txnIdEl && txnId != null) txnIdEl.textContent = txnId
            return clone
        }

        function populateReceiptCard(root, details) {
            if (!root || !details) return
            var patient = details['Patient'] || '-'
            var doctor = details['Doctor'] || '-'
            var servicesHtml = details['Services'] || ''
            var gross = details['Gross Amount'] || 'PHP 0.00'
            var discType = details['Discount Type'] || 'none'
            var discAmt = details['Discount Amount'] || 'PHP 0.00'
            var net = details['Net Amount'] || 'PHP 0.00'
            var mode = details['Payment Mode'] || 'cash'
            var txnDate = details['Transaction Date'] || '-'
            var paid = details['Paid'] || 'PHP 0.00'
            var change = details['Change'] || 'PHP 0.00'

            var patientEl = root.querySelector('.receipt-patient')
            var doctorEl = root.querySelector('.receipt-doctor')
            var servicesContainer = root.querySelector('.receipt-services')
            var grossEl = root.querySelector('.receipt-gross')
            var discTypeEl = root.querySelector('.receipt-discount-type')
            var discAmtEl = root.querySelector('.receipt-discount-amount')
            var netEl = root.querySelector('.receipt-net')
            var modeEl = root.querySelector('.receipt-mode')
            var dateEl = root.querySelector('.receipt-date')
            var paidEl = root.querySelector('.receipt-paid')
            var changeEl = root.querySelector('.receipt-change')
            var metaEl = root.querySelector('#receiptMeta')

            if (patientEl) patientEl.textContent = patient
            if (doctorEl) doctorEl.textContent = doctor
            if (servicesContainer) servicesContainer.innerHTML = servicesHtml || '<div class="flex justify-between text-slate-400"><span>-</span><span>-</span></div>'
            if (grossEl) grossEl.textContent = gross
            if (discTypeEl) discTypeEl.textContent = discType
            if (discAmtEl) discAmtEl.textContent = discAmt
            if (netEl) netEl.textContent = net
            if (modeEl) modeEl.textContent = mode
            if (dateEl) dateEl.textContent = txnDate
            if (paidEl) paidEl.textContent = paid
            if (changeEl) changeEl.textContent = change
            if (metaEl && txnDate) metaEl.textContent = '\u00B7  ' + txnDate
        }

        var reviewConfirming = false

        function closeReview(result) {
            if (reviewOverlay) {
                reviewOverlay.classList.add('hidden')
                reviewOverlay.classList.remove('flex')
            }
            if (reviewDelayTimer) {
                clearTimeout(reviewDelayTimer)
                reviewDelayTimer = null
            }
            if (reviewConfirm) {
                reviewConfirm.disabled = false
                reviewConfirm.innerHTML = reviewConfirmDefaultHtml || 'Confirm Payment'
            }
            if (reviewCancel) reviewCancel.classList.remove('hidden')
            reviewConfirm.classList.remove('hidden')
            reviewConfirming = false
            // Clear stale content so print CSS doesn't show old text
            if (reviewContent) reviewContent.innerHTML = '<div class="text-center text-slate-400 py-4"></div>'
            var resolver = reviewResolver
            reviewResolver = null
            if (typeof resolver === 'function') resolver(!!result)
        }

        function openReview(details) {
            return new Promise(function (resolve) {
                if (!reviewOverlay || !reviewContent || !reviewConfirm || !reviewCancel) {
                    resolve(window.confirm('Please review payment details before submitting.'))
                    return
                }

                if (reviewTitle) reviewTitle.textContent = 'Review Payment Details'
                if (reviewSubtitle) reviewSubtitle.textContent = 'Please verify all payment information before confirming'
                reviewCancel.textContent = 'Cancel'
                reviewCancel.classList.remove('hidden')
                reviewConfirm.classList.remove('hidden')
                reviewConfirm.disabled = true
                reviewConfirming = false
                reviewConfirm.innerHTML = reviewConfirmDefaultHtml || 'Confirm Payment'

                var rc = cloneReceiptCard(null)
                if (rc) {
                    rc.setAttribute('data-state', 'review')
                    populateReceiptCard(rc, details)
                    reviewContent.innerHTML = ''
                    reviewContent.appendChild(rc)
                } else {
                    reviewContent.innerHTML = '<div class="text-center text-slate-400 py-4">Receipt template not found.</div>'
                }
                reviewResolver = resolve
                reviewOverlay.classList.remove('hidden')
                reviewOverlay.classList.add('flex')

                // 3-second countdown on the confirm button
                var countdown = 3
                reviewConfirm.disabled = true
                reviewConfirm.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>' + String(countdown) + '</span></span>'
                reviewDelayTimer = setInterval(function () {
                    countdown--
                    if (countdown > 0) {
                        reviewConfirm.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>' + String(countdown) + '</span></span>'
                    } else {
                        clearInterval(reviewDelayTimer)
                        reviewDelayTimer = null
                        reviewConfirm.disabled = false
                        reviewConfirm.innerHTML = reviewConfirmDefaultHtml || 'Confirm Payment'
                    }
                }, 1000)
            })
        }

        function showReceipt(details) {
            if (!receiptOverlay || !receiptContent) return
            var rc = cloneReceiptCard(null)
            if (rc) {
                rc.setAttribute('data-state', 'finalized')
                populateReceiptCard(rc, details)
                receiptContent.innerHTML = ''
                receiptContent.appendChild(rc)
            } else {
                receiptContent.innerHTML = '<div class="text-center text-slate-400 py-4">Receipt template not found.</div>'
            }
            receiptOverlay.classList.remove('hidden')
            receiptOverlay.classList.add('flex')
        }

        function closeReceipt() {
            if (receiptOverlay) {
                receiptOverlay.classList.add('hidden')
                receiptOverlay.classList.remove('flex')
            }
        }

        // ── Appointment Modal Functions ──

        function loadTodayAppointments(query) {
            if (typeof apiFetch !== 'function') return
            if (apptList) apptList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading appointments...</div>'
            var url = "{{ url('/api/appointments') }}" + '?per_page=10&order=latest&today_only=1'
            var q = String(query || '').trim()
            if (q) url += '&search=' + encodeURIComponent(q)
            apiFetch(url, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) { return { ok: response.ok, data: data } }).catch(function () { return { ok: response.ok, data: null } })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        if (apptList) apptList.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Failed to load appointments.</div>'
                        return
                    }
                    var list = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    todayAppointments = list
                    renderApptList(list)
                })
                .catch(function () {
                    if (apptList) apptList.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Network error loading appointments.</div>'
                })
        }

        function renderApptList(list) {
            if (!apptList) return
            // Only show appointments with consulted or completed status
            list = list.filter(function (appt) {
                var s = appt && appt.status ? String(appt.status).toLowerCase() : ''
                return s === 'consulted' || s === 'completed'
            })
            // Sort paid items to the bottom
            list = list.slice().sort(function (a, b) {
                var aPaid = a && a.transaction && String(a.transaction.payment_status || '').toLowerCase() === 'paid'
                var bPaid = b && b.transaction && String(b.transaction.payment_status || '').toLowerCase() === 'paid'
                if (aPaid && !bPaid) return 1
                if (!aPaid && bPaid) return -1
                return 0
            })
            if (!list.length) {
                apptList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No appointments found for today.</div>'
                return
            }
            apptList.innerHTML = list.map(function (appt, idx) {
                var patient = appointmentPatientName(appt)
                var when = appt && appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
                var timeOnly = when.length >= 16 ? formatTime12h(when.slice(11, 16)) : ''
                var type = normalizeAppointmentType(appt && appt.appointment_type ? appt.appointment_type : '')
                var typeLabel = type === 'walk_in' ? 'Walk-in' : 'Scheduled'
                var typeColors = type === 'walk_in' ? 'bg-sky-50 text-sky-700 border-sky-200' : 'bg-purple-50 text-purple-700 border-purple-200'
                // Payment status
                var txn = appt && appt.transaction ? appt.transaction : null
                var payStatus = txn && txn.payment_status ? String(txn.payment_status).toLowerCase() : ''
                var statusColors = { pending: 'bg-amber-50 text-amber-700 border-amber-200', paid: 'bg-emerald-50 text-emerald-700 border-emerald-200', failed: 'bg-red-50 text-red-700 border-red-200' }
                var statusBadge = payStatus ? '<span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[0.6rem] font-medium border ' + (statusColors[payStatus] || 'bg-slate-50 text-slate-600 border-slate-200') + '">' + payStatus.charAt(0).toUpperCase() + payStatus.slice(1) + '</span>' : ''
                var greyedClass = payStatus === 'paid' ? 'opacity-50' : ''
                return '<button type="button" class="appt-list-item w-full text-left px-4 py-3.5 rounded-xl border border-slate-200 bg-white hover:border-green-300 hover:shadow-sm transition-all ' + greyedClass + '" data-index="' + idx + '">' +
                    '<div class="flex items-center justify-between gap-2 mb-1.5">' +
                        '<div class="text-sm text-slate-800 font-semibold truncate">' + escapeHtml(patient) + '</div>' +
                        '<div class="flex items-center gap-1.5 shrink-0">' +
                            '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.62rem] font-medium border ' + typeColors + '">' + escapeHtml(typeLabel) + '</span>' +
                            statusBadge +
                        '</div>' +
                    '</div>' +
                    '<div class="text-[0.75rem] text-slate-500">' + escapeHtml(timeOnly) + '</div>' +
                '</button>'
            }).join('')

            // Store full list for modal selection
            todayAppointmentsFiltered = list

            apptList.querySelectorAll('.appt-list-item').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-index'), 10)
                    var appt = list[idx]
                    if (appt) {
                        selectApptModalItem(idx)
                    }
                })
            })
        }

        function selectApptModalItem(index) {
            var appt = todayAppointmentsFiltered[index]
            if (!appt) return
            apptModalSelectedAppt = appt
            // Update left panel selection highlight
            apptList.querySelectorAll('.appt-list-item').forEach(function (el, i) {
                if (i === index) {
                    el.classList.add('bg-green-100', '!border-green-400', '!border-2')
                    el.classList.remove('hover:border-green-300')
                } else {
                    el.classList.remove('bg-green-100', '!border-green-400', '!border-2')
                    el.classList.add('hover:border-green-300')
                }
            })
            // Check if appointment is already paid — disallow selection but still show details
            var txn = appt && appt.transaction ? appt.transaction : null
            var isPaid = txn && String(txn.payment_status || '').toLowerCase() === 'paid'
            if (apptModalSelect) apptModalSelect.disabled = isPaid
            // Render details on right panel
            renderApptDetail(appt)
        }

        function renderApptDetail(appt) {
            if (!apptDetail) return
            var patient = appointmentPatientName(appt)
            var doctor = appointmentDoctorName(appt)
            var services = servicesFromAppointment(appt)
            var gross = originalAmount(appt)
            var when = appt && appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'

            // Payment status badge
            var txn = appt && appt.transaction ? appt.transaction : null
            var payStatus = txn && txn.payment_status ? String(txn.payment_status).toLowerCase() : ''
            var statusBadge = ''
            if (payStatus === 'pending') {
                statusBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-medium border bg-blue-50 text-blue-700 border-blue-200 ml-2">Pending Payment</span>'
            } else if (payStatus === 'paid') {
                statusBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-medium border bg-green-50 text-green-700 border-green-200 ml-2">Paid</span>'
            } else if (payStatus === 'failed') {
                statusBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-medium border bg-red-50 text-red-700 border-red-200 ml-2">Failed</span>'
            }

            var servicesHtml = services.length
                ? services.map(function (s) {
                    var desc = s.description ? '<span class="block text-[0.65rem] font-normal text-slate-400">' + escapeHtml(s.description) + '</span>' : ''
                    return '<div class="flex items-start justify-between py-1.5 border-b border-slate-100 last:border-0 text-[0.78rem]"><div>' + escapeHtml(s.name) + desc + '</div><span class="font-semibold shrink-0 ml-2">' + escapeHtml(money(s.price)) + '</span></div>'
                }).join('')
                : '<div class="text-[0.75rem] text-slate-400">No services</div>'

            apptDetail.innerHTML =
                '<div class="space-y-2 text-[0.78rem]">' +
                    '<div class="flex items-center"><span class="font-semibold text-slate-800">Patient:</span> <span class="text-slate-700 ml-1">' + escapeHtml(patient) + '</span></div>' +
                    '<div><span class="font-semibold text-slate-800">Doctor:</span> <span class="text-slate-700 ml-1">' + escapeHtml(doctor) + '</span></div>' +
                    '<div><span class="font-semibold text-slate-800">Date/Time:</span> <span class="text-slate-700 ml-1">' + escapeHtml(when) + '</span></div>' +
                    '<div><span class="font-semibold text-slate-800">Type:</span> <span class="text-slate-700 ml-1">' + escapeHtml(appointmentTypeLabel(appt)) + statusBadge + '</span></div>' +
                    '<div class="border-t border-slate-200 pt-2 mt-2">' +
                        '<div class="text-[0.72rem] font-semibold text-slate-600 mb-1">Services</div>' +
                        servicesHtml +
                        '<div class="border-t border-slate-200 mt-2 pt-2 flex items-center justify-between text-[0.82rem] font-bold">' +
                            '<span>Subtotal Fees:</span><span class="text-green-700">' + escapeHtml(money(gross)) + '</span>' +
                        '</div>' +
                    '</div>' +
                '</div>'
        }

        function openAppointmentModal() {
            if (apptModal) {
                apptModal.classList.remove('hidden')
                apptModal.classList.add('flex')
            }
            apptModalSelectedAppt = null
            if (apptModalSelect) apptModalSelect.disabled = true
            if (apptDetail) apptDetail.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment from the list.</div>'
            if (apptModalSearch) apptModalSearch.value = ''
            loadTodayAppointments('')
        }

        function closeAppointmentModal() {
            if (apptModal) {
                apptModal.classList.add('hidden')
                apptModal.classList.remove('flex')
            }
        }

        function confirmApptModalSelection() {
            if (!apptModalSelectedAppt) return
            setAppointmentSelection(apptModalSelectedAppt)
            closeAppointmentModal()
        }

        function updateDiscountUI() {
            if (discountWrap) discountWrap.classList.toggle('hidden', !showDiscount)
            if (discountToggle) discountToggle.textContent = showDiscount ? 'Hide discount' : 'Add discount'
            if (!showDiscount && discountTypeSelect) discountTypeSelect.value = 'none'
            refreshTotalsUI()
        }

        function txSetTodayButton() {
            if (!txTodayBtn) return
            if (txTodayOnly) {
                txTodayBtn.textContent = 'Showing today only'
                txTodayBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
                txTodayBtn.classList.add('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-green-700', 'hover:border-green-700')
            } else {
                txTodayBtn.textContent = 'Show today only'
                txTodayBtn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
                txTodayBtn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-green-700', 'hover:border-green-700')
            }
        }

        function txServiceSummary(tx) {
            var appt = tx && tx.appointment ? tx.appointment : null
            var services = appt && Array.isArray(appt.services) ? appt.services : []
            var names = services.map(function (s) { return String((s && s.service_name) ? s.service_name : '').trim() }).filter(function (v) { return v !== '' })
            if (!names.length) return '-'
            return names.join(', ')
        }

        function txServicesHtml(tx) {
            var appt = tx && tx.appointment ? tx.appointment : null
            var services = appt && Array.isArray(appt.services) ? appt.services : []
            var items = services.map(function (s) {
                var name = String((s && s.service_name) ? s.service_name : '').trim()
                var description = s && s.description ? String(s.description).trim() : ''
                var price = s && s.price != null ? parseFloat(s.price) : 0
                if (isNaN(price)) price = 0
                if (!name) return ''
                var desc = description ? ' · ' + escapeHtml(description) : ''
                return '<div class="flex justify-between text-[0.85rem] receipt-figures"><span class="text-slate-700">' + escapeHtml(name) + desc + '</span><span class="text-slate-800 font-medium">PHP ' + price.toLocaleString('en-US', {minimumFractionDigits:2}) + '</span></div>'
            }).filter(function (v) { return v !== '' }).join('')
            if (!items) return ''
            return items
        }

        function txPatientName(tx) {
            var appt = tx && tx.appointment ? tx.appointment : null
            return appointmentPatientName(appt)
        }

        function txDatePart(tx) {
            var raw = tx && tx.transaction_datetime ? String(tx.transaction_datetime) : ''
            if (!raw) raw = tx && tx.created_at ? String(tx.created_at) : ''
            return raw ? raw.replace('T', ' ').slice(0, 16) : '-'
        }

        function renderTransactions(rows) {
            if (!txTableBody) return
            var list = Array.isArray(rows) ? rows : []
            if (!list.length) {
                txTableBody.innerHTML = '<tr><td colspan="7" class="px-3 py-6 text-center text-[0.78rem] text-slate-500">No transactions found.</td></tr>'
                if (txPagination) txPagination.innerHTML = ''
                return
            }

            txTableBody.innerHTML = list.map(function (tx) {
                var appt = tx && tx.appointment ? tx.appointment : null
                var patient = appt && appt.patient ? appt.patient : null
                var patientId = patient && patient.user_id != null ? patient.user_id : ''
                var patientName = txPatientName(tx)
                var date = txDatePart(tx)
                var ref = tx && tx.reference_number ? String(tx.reference_number) : '-'
                var type = appointmentTypeLabel(appt)
                var gross = parseFloat(tx && tx.amount != null ? tx.amount : 0)
                var disc = parseFloat(tx && tx.discount_amount != null ? tx.discount_amount : 0)
                if (isNaN(gross)) gross = 0
                if (isNaN(disc)) disc = 0
                var net = Math.max(0, gross - disc)
                // Payment status from transaction
                var payStatus = tx && tx.payment_status ? String(tx.payment_status).toLowerCase() : ''
                var statusColors = { pending: 'border-amber-200 bg-amber-50 text-amber-700', paid: 'border-green-200 bg-green-50 text-green-700', failed: 'border-red-200 bg-red-50 text-red-700' }
                var statusClass = statusColors[payStatus] || 'border-slate-200 bg-slate-50 text-slate-600'
                var statusLabel = payStatus.charAt(0).toUpperCase() + payStatus.slice(1)
                return '<tr>' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(date) + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(ref) + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 min-w-[12rem] whitespace-nowrap">' + escapeHtml(patientName) + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(type) + '</td>' +
                    '<td class="px-3 py-2 text-right text-slate-700 whitespace-nowrap font-medium">' + escapeHtml(money(net)) + '</td>' +
                    '<td class="px-3 py-2 whitespace-nowrap"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusClass + '">' + escapeHtml(statusLabel) + '</span></td>' +
                    '<td class="px-3 py-2 whitespace-nowrap">' +
                        '<button type="button" class="tx-see-history-btn inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300" data-patient-id="' + escapeHtml(String(patientId)) + '" data-patient-name="' + escapeHtml(patientName) + '">See Details &amp; History</button>' +
                    '</td>' +
                '</tr>'
            }).join('')
            renderTxPagination()
        }

        function renderTxPagination() {
            if (!txPagination) return
            if (txTotal === 0) { txPagination.innerHTML = ''; return }
            var totalPages = txLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + txTotal + ' entries</span>'
            html += '<button type="button" class="' + (txCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (txCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var ws = Math.max(1, txCurrentPage - Math.floor(txVisibleCount / 2))
            var we = Math.min(ws + txVisibleCount - 1, totalPages)
            if (we - ws + 1 < txVisibleCount) ws = Math.max(1, we - txVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === txCurrentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>' }
            html += '<button type="button" class="' + (txCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (txCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            txPagination.innerHTML = html
            txPagination.querySelectorAll('button[data-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-page')
                    if (p === 'prev' && txCurrentPage > 1) { txCurrentPage--; loadTransactions(txCurrentPage) }
                    else if (p === 'next' && txCurrentPage < totalPages) { txCurrentPage++; loadTransactions(txCurrentPage) }
                    else if (p === 'next-window') { var ns = Math.min(we + 1, totalPages); txCurrentPage = ns; loadTransactions(txCurrentPage) }
                    else if (p !== 'prev' && p !== 'next') { txCurrentPage = parseInt(p, 10); loadTransactions(txCurrentPage) }
                })
            })
        }

        function loadTransactions(page) {
            if (typeof apiFetch !== 'function') return
            page = page || txCurrentPage
            showTransactionsError('')
            txTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading transactions…</td></tr>'

            var url = "{{ url('/api/transactions') }}" + '?per_page=10&page=' + page
            var order = txSort && txSort.value ? String(txSort.value) : 'latest'
            txSortOrder = order
            url += '&order=' + encodeURIComponent(order === 'oldest' ? 'oldest' : 'latest')

            var now = new Date()
            var yyyy = now.getFullYear()
            var mm = String(now.getMonth() + 1).padStart(2, '0')
            var dd = String(now.getDate()).padStart(2, '0')
            var today = yyyy + '-' + mm + '-' + dd
            if (txTodayOnly) {
                url += '&start_date=' + encodeURIComponent(today) + '&end_date=' + encodeURIComponent(today)
            } else {
                var start = yyyy + '-' + mm + '-01'
                var end = yyyy + '-' + mm + '-' + String(new Date(yyyy, now.getMonth() + 1, 0).getDate()).padStart(2, '0')
                url += '&start_date=' + encodeURIComponent(start) + '&end_date=' + encodeURIComponent(end)
            }

            var search = txSearch ? normalizeText(txSearch.value) : ''
            if (search) url += '&search=' + encodeURIComponent(search)
            var serviceId = txServiceId && txServiceId.value ? parseInt(txServiceId.value, 10) : 0
            if (serviceId) url += '&service_id=' + encodeURIComponent(serviceId)

            var type = txType ? txType.value : ''
            if (type) url += '&appointment_type=' + encodeURIComponent(type)

            var status = txStatus ? txStatus.value : ''
            if (status) url += '&payment_status=' + encodeURIComponent(status)

            var date = txDate ? txDate.value : ''
            if (date) url += '&transaction_date=' + encodeURIComponent(date)

            apiFetch(url, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) { return { ok: response.ok, data: data } }).catch(function () { return { ok: response.ok, data: null } })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        showTransactionsError((result.data && result.data.message) ? String(result.data.message) : 'Failed to load transactions.')
                        renderTransactions([])
                        return
                    }
                    var rows = Array.isArray(result.data.data) ? result.data.data.slice() : (Array.isArray(result.data) ? result.data.slice() : [])

                    // Keep only the most recent transaction per patient
                    rows.sort(function (a, b) {
                        var da = a && a.created_at ? String(a.created_at) : ''
                        var db = b && b.created_at ? String(b.created_at) : ''
                        if (da < db) return 1; if (da > db) return -1; return 0
                    })
                    var seenPatient = {}
                    rows = rows.filter(function (a) {
                        var pid = a && a.appointment && a.appointment.patient && a.appointment.patient.user_id != null ? String(a.appointment.patient.user_id) : ''
                        if (!pid) return true
                        if (seenPatient[pid]) return false
                        seenPatient[pid] = true
                        return true
                    })

                    txCurrentPage = result.data.current_page || page
                    txLastPage = result.data.last_page || 1
                    txTotal = rows.length
                    txAllRows = rows

                    renderTransactions(rows)
                })
                .catch(function () {
                    showTransactionsError('Network error while loading transactions.')
                    renderTransactions([])
                })
        }

        // ── Transaction History Modal ──
        var txHistoryPatientId = null
        var txHistoryTransactions = []

        function openTxHistoryModal(patientId, patientName) {
            txHistoryPatientId = patientId
            var subtitle = document.getElementById('receptionTxHistorySubtitle')
            if (subtitle) subtitle.textContent = patientName || 'Patient #' + patientId
            var body = document.getElementById('receptionTxHistoryBody')
            if (body) body.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>'
            var detailBody = document.getElementById('receptionTxHistoryDetailBody')
            if (detailBody) detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a transaction to view details.</div>'
            var overlay = document.getElementById('receptionTxHistoryOverlay')
            if (overlay) {
                overlay.classList.remove('hidden')
                overlay.classList.add('flex')
            }
            loadTxPatientHistory(patientId)
        }

        function closeTxHistoryModal() {
            var overlay = document.getElementById('receptionTxHistoryOverlay')
            if (overlay) {
                overlay.classList.add('hidden')
                overlay.classList.remove('flex')
            }
            txHistoryPatientId = null
            txHistoryTransactions = []
        }

        function loadTxPatientHistory(patientId) {
            if (!patientId) return
            apiFetch("{{ url('/api/transactions') }}?per_page=15&patient_id=" + patientId, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) { return { ok: response.ok, data: data } }).catch(function () { return { ok: false, data: null } })
                })
                .then(function (result) {
                    if (!result.ok) {
                        var body = document.getElementById('receptionTxHistoryBody')
                        if (body) body.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Failed to load history.</div>'
                        return
                    }
                    txHistoryTransactions = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var subtitle = document.getElementById('receptionTxHistorySubtitle')
                    if (subtitle) {
                        var first = txHistoryTransactions[0]
                        var label = first && first.appointment && first.appointment.patient ? txPatientName(first) : ('Patient #' + patientId)
                        subtitle.textContent = label + ' - ' + txHistoryTransactions.length + ' transaction(s)'
                    }
                    renderTxHistory()
                })
                .catch(function () {
                    var body = document.getElementById('receptionTxHistoryBody')
                    if (body) body.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Network error loading history.</div>'
                })
        }

        function renderTxHistory() {
            var body = document.getElementById('receptionTxHistoryBody')
            if (!body) return
            var list = txHistoryTransactions.slice()
            list.sort(function (a, b) {
                var da = (a.transaction_datetime || a.created_at || '')
                var db = (b.transaction_datetime || b.created_at || '')
                return da < db ? 1 : (da > db ? -1 : 0)
            })
            if (!list.length) {
                body.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No transactions found.</div>'
                return
            }
            var html = ''
            list.forEach(function (tx) {
                var appt = tx && tx.appointment ? tx.appointment : null
                var dt = tx.transaction_datetime ? String(tx.transaction_datetime).replace('T', ' ').slice(0, 16) : '-'
                var ref = tx && tx.reference_number ? String(tx.reference_number) : '-'
                var gross = parseFloat(tx && tx.amount != null ? tx.amount : 0)
                var disc = parseFloat(tx && tx.discount_amount != null ? tx.discount_amount : 0)
                if (isNaN(gross)) gross = 0
                if (isNaN(disc)) disc = 0
                var net = Math.max(0, gross - disc)
                var payStatus = tx && tx.payment_status ? String(tx.payment_status).toLowerCase() : ''
                var statusColors = { pending: 'border-amber-200 bg-amber-50 text-amber-700', paid: 'border-green-200 bg-green-50 text-green-700', failed: 'border-red-200 bg-red-50 text-red-700' }
                var statusClass = statusColors[payStatus] || 'border-slate-200 bg-slate-50 text-slate-600'
                var statusLabel = payStatus.charAt(0).toUpperCase() + payStatus.slice(1)
                html += '<div class="rounded-xl border border-slate-200 bg-white p-3 hover:border-green-200 transition-colors cursor-pointer tx-history-row" data-tx-id="' + (tx.transaction_id || '') + '">' +
                    '<div class="flex items-center justify-between mb-1">' +
                        '<span class="text-[0.78rem] font-semibold text-slate-800">' + escapeHtml(dt) + '</span>' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusClass + '">' + escapeHtml(statusLabel) + '</span>' +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500">Ref: ' + escapeHtml(ref) + ' · Net: ' + escapeHtml(money(net)) + '</div>' +
                '</div>'
            })
            body.innerHTML = html
            // Auto-select first
            if (list.length) {
                var firstId = list[0].transaction_id
                selectTxHistoryItem(firstId)
            }
            body.querySelectorAll('.tx-history-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    var txId = this.getAttribute('data-tx-id')
                    selectTxHistoryItem(txId)
                })
            })
        }

        function selectTxHistoryItem(txId) {
            if (!txId) return
            // Highlight selected
            var rows = document.querySelectorAll('#receptionTxHistoryBody .tx-history-row')
            rows.forEach(function (r) { r.classList.remove('border-green-500', 'ring-1', 'ring-green-200'); r.classList.add('border-slate-200') })
            var selected = document.querySelector('#receptionTxHistoryBody .tx-history-row[data-tx-id="' + txId + '"]')
            if (selected) { selected.classList.remove('border-slate-200'); selected.classList.add('border-green-500', 'ring-1', 'ring-green-200') }
            var tx = txHistoryTransactions.find(function (t) { return String(t.transaction_id) === String(txId) })
            if (tx) renderTxHistoryDetail(tx)
        }

        function renderTxHistoryDetail(tx) {
            var detailBody = document.getElementById('receptionTxHistoryDetailBody')
            if (!detailBody) return
            if (!tx) {
                detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a transaction to view details.</div>'
                return
            }
            var appt = tx && tx.appointment ? tx.appointment : null
            var patient = appt && appt.patient ? appt.patient : null
            var doctor = appt && appt.doctor ? appt.doctor : null
            var patientName = txPatientName(tx)
            var doctorName = doctor ? (doctor.firstname || '') + ' ' + (doctor.lastname || '') : '-'
            var gross = parseFloat(tx && tx.amount != null ? tx.amount : 0)
            var disc = parseFloat(tx && tx.discount_amount != null ? tx.discount_amount : 0)
            if (isNaN(gross)) gross = 0
            if (isNaN(disc)) disc = 0
            var net = Math.max(0, gross - disc)
            var discType = tx && tx.discount_type ? String(tx.discount_type) : 'none'
            var mode = tx && tx.payment_mode ? String(tx.payment_mode).toUpperCase() : 'CASH'
            var txnDate = tx.transaction_datetime ? String(tx.transaction_datetime).replace('T', ' ').slice(0, 16) : '-'
            var payStatus = tx && tx.payment_status ? String(tx.payment_status).toLowerCase() : ''
            var paid = parseFloat(tx && tx.money_paid != null ? tx.money_paid : (tx.amount || 0))
            if (isNaN(paid)) paid = gross
            var change = parseFloat(tx && tx.money_change != null ? tx.money_change : Math.max(0, paid - net))
            if (isNaN(change)) change = Math.max(0, paid - net)

            var details = {
                'Patient': patientName,
                'Doctor': doctorName,
                'Services': txServicesHtml(tx),
                'Gross Amount': money(gross),
                'Discount Type': discType,
                'Discount Amount': money(disc),
                'Net Amount': money(net),
                'Payment Mode': mode,
                'Transaction Date': txnDate,
                'Paid': money(paid),
                'Change': money(change),
            }
            // For pending transactions, show a blank receipt (no numbers)
            if (payStatus === 'pending') {
                details['Gross Amount'] = '\u2014'
                details['Discount Type'] = '\u2014'
                details['Discount Amount'] = '\u2014'
                details['Net Amount'] = '\u2014'
                details['Payment Mode'] = '\u2014'
                details['Paid'] = '\u2014'
                details['Change'] = '\u2014'
            }
            var rc = cloneReceiptCard(tx.transaction_id)
            if (rc) {
                rc.setAttribute('data-state', tx.payment_status === 'paid' ? 'finalized' : 'review')
                populateReceiptCard(rc, details)
                detailBody.innerHTML = '<div class="max-w-sm mx-auto"></div>'
                var wrapper = detailBody.querySelector('.max-w-sm')
                if (wrapper) {
                    wrapper.appendChild(rc)
                    var printBtn = document.createElement('div')
                    printBtn.className = 'text-center mt-3'
                    printBtn.innerHTML = '<button type="button" class="receipt-print-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300" onclick="window.print()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print / PDF</button>'
                    wrapper.appendChild(printBtn)
                }
            } else {
                detailBody.innerHTML = '<div class="text-center text-slate-400 py-8">Receipt template not found.</div>'
            }
        }

        // ── Event delegation for See Details & History button ──
        var txTableBody = document.getElementById('receptionTransactionsTableBody')
        if (txTableBody) {
            txTableBody.addEventListener('click', function (e) {
                var btn = e.target.closest('.tx-see-history-btn')
                if (btn) {
                    var pid = btn.getAttribute('data-patient-id')
                    var pname = btn.getAttribute('data-patient-name')
                    if (pid) openTxHistoryModal(pid, pname)
                }
            })
        }

        // ── Modal close ──
        var txHistOverlay = document.getElementById('receptionTxHistoryOverlay')
        var txHistClose = document.getElementById('receptionTxHistoryClose')
        if (txHistOverlay) {
            txHistOverlay.addEventListener('click', function (e) {
                if (e.target === txHistOverlay) closeTxHistoryModal()
            })
        }
        if (txHistClose) {
            txHistClose.addEventListener('click', closeTxHistoryModal)
        }

        function renderTxServiceResults() {
            if (!txServiceResults || !txServiceSearch) return
            var q = String(txServiceSearch.value || '').trim()
            var filtered = (Array.isArray(txServices) ? txServices : []).filter(function (s) {
                return wordPrefixMatch(s && s.service_name ? s.service_name : '', q)
            }).slice(0, 25)

            if (!filtered.length) {
                txServiceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
            } else {
                txServiceResults.innerHTML = filtered.map(function (s) {
                    var id = s && s.service_id != null ? s.service_id : ''
                    var name = s && s.service_name ? s.service_name : ('Service #' + id)
                    return '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 text-[0.78rem] text-slate-700" data-service-id="' + escapeHtml(id) + '">' + escapeHtml(name) + '</button>'
                }).join('')
            }
            txServiceResults.classList.remove('hidden')
        }

        function loadTxServices() {
            if (txServicesLoaded || txServicesLoading || typeof apiFetch !== 'function') return
            txServicesLoading = true
            apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data } }).catch(function () { return { ok: response.ok, data: null } }) })
                .then(function (result) {
                    if (!result.ok) return
                    txServices = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    txServicesLoaded = true
                })
                .catch(function () {})
                .finally(function () {
                    txServicesLoading = false
                })
        }

        function setTxServiceSelection(service) {
            if (txServiceId) txServiceId.value = service && service.service_id != null ? String(service.service_id) : ''
            if (txServiceSearch) {
                txServiceSearch.value = service && service.service_name ? String(service.service_name) : ''
                if (!service) txServiceSearch.placeholder = 'All services'
            }
            if (txServiceResults) txServiceResults.classList.add('hidden')
        }

        if (recordTab) recordTab.addEventListener('click', function () { setBillingTab('record') })
        if (txTab) txTab.addEventListener('click', function () { setBillingTab('transactions') })
        setBillingTab('record')

        // Auto-switch to Transactions tab if navigated with ?tab=transactions
        ;(function () {
            var tabParam = null
            try {
                tabParam = new URL(window.location.href).searchParams.get('tab')
            } catch (_) {}
            if (tabParam === 'transactions') {
                setBillingTab('transactions')
            }
        })()

        // Auto-select appointment if navigated with ?select_appt=ID
        ;(function () {
            var apptId = null
            try {
                apptId = new URL(window.location.href).searchParams.get('select_appt')
            } catch (_) {}
            if (apptId && typeof apiFetch === 'function') {
                apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(apptId), { method: 'GET' })
                    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }) })
                    .then(function (result) {
                        if (result.ok) {
                            var appt = result.data && result.data.data ? result.data.data : result.data
                            if (appt && appt.appointment_id) {
                                setAppointmentSelection(appt)
                            }
                        }
                    })
                    .catch(function () {})
            }
        })()

        if (discountToggle) {
            discountToggle.addEventListener('click', function () {
                showDiscount = !showDiscount
                updateDiscountUI()
            })
        }
        if (discountTypeSelect) discountTypeSelect.addEventListener('change', refreshTotalsUI)
        updateDiscountUI()
        resetAppointmentSelection()

        // ── Browse button opens appointment modal ──
        if (browseBtn) {
            browseBtn.addEventListener('click', function () {
                showPaymentError('')
                showPaymentSuccess('')
                openAppointmentModal()
            })
        }
        if (appointmentDisplay) {
            appointmentDisplay.addEventListener('click', function () {
                showPaymentError('')
                showPaymentSuccess('')
                openAppointmentModal()
            })
        }

        // ── Appointment Modal events ──
        if (apptModalClose) apptModalClose.addEventListener('click', closeAppointmentModal)
        if (apptModalCancel) apptModalCancel.addEventListener('click', closeAppointmentModal)
        if (apptModalSelect) apptModalSelect.addEventListener('click', confirmApptModalSelection)
        if (apptModal) {
            apptModal.addEventListener('click', function (e) {
                if (e.target === apptModal) closeAppointmentModal()
            })
        }
        if (apptModalSearch) {
            var apptModalSearchTimer = null
            apptModalSearch.addEventListener('input', function () {
                if (apptModalSearchTimer) clearTimeout(apptModalSearchTimer)
                apptModalSearchTimer = setTimeout(function () {
                    loadTodayAppointments(String(apptModalSearch.value || '').trim())
                }, 250)
            })
        }
        if (apptModalRefreshBtn) {
            apptModalRefreshBtn.addEventListener('click', function (e) {
                e.preventDefault()
                if (apptModalSearch) apptModalSearch.value = ''
                todayAppointments = []
                todayAppointmentsFiltered = []
                apptModalSelectedAppt = null
                if (apptDetail) apptDetail.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment from the list.</div>'
                if (apptModalSelect) apptModalSelect.disabled = true
                loadTodayAppointments('')
            })
        }

        // ── Review overlay events ──
        if (reviewCancel) reviewCancel.addEventListener('click', function () { closeReview(false) })
        if (reviewConfirm) {
            reviewConfirm.addEventListener('click', function () {
                if (reviewConfirming) return
                reviewConfirming = true
                reviewConfirm.disabled = true
                reviewConfirm.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>Processing...</span></span>'
                closeReview(true)
            })
        }
        if (reviewCloseBtn) {
            reviewCloseBtn.addEventListener('click', function () { closeReview(false) })
        }
        if (reviewOverlay) {
            reviewOverlay.addEventListener('click', function (e) {
                if (e.target === reviewOverlay) closeReview(false)
            })
        }

        // ── Receipt modal events ──
        if (receiptPrintBtn) {
            receiptPrintBtn.addEventListener('click', function () { window.print() })
        }
        if (receiptCloseBtn) {
            receiptCloseBtn.addEventListener('click', closeReceipt)
        }
        if (receiptCloseX) {
            receiptCloseX.addEventListener('click', closeReceipt)
        }
        if (receiptOverlay) {
            receiptOverlay.addEventListener('click', function (e) {
                if (e.target === receiptOverlay) closeReceipt()
            })
        }

        // ── Payment form submit ──
        if (paymentForm) {
            paymentForm.addEventListener('submit', function (e) {
                e.preventDefault()
                showPaymentError('')
                showPaymentSuccess('')
                if (typeof apiFetch !== 'function') {
                    showPaymentError('API client is not available.')
                    return
                }

                var appointmentId = appointmentIdInput ? parseInt(appointmentIdInput.value || '0', 10) : 0
                if (!appointmentId || !selectedAppointment) {
                    showPaymentError('Please select an appointment.')
                    return
                }

                var gross = originalAmount(selectedAppointment)
                if (gross <= 0) {
                    showPaymentError('Selected appointment has no billable services.')
                    return
                }
                var discountType = discountTypeSelect && showDiscount ? String(discountTypeSelect.value || 'none') : 'none'
                var discount = showDiscount ? discountAmount(selectedAppointment) : 0
                var net = Math.max(0, gross - discount)
                var transactionDatetime = currentSqlDatetime()
                var amountPaid = moneyPaidInput ? parseFloat((moneyPaidInput.value || '0').replace(/,/g, '')) : 0
                if (isNaN(amountPaid)) amountPaid = 0
                var changeAmount = Math.max(0, amountPaid - net)

                // Validate money_paid: required and must be >= net amount
                if (!moneyPaidInput || !moneyPaidInput.value || parseFloat((moneyPaidInput.value || '0').replace(/,/g, '')) <= 0) {
                    showPaymentError('Please enter the amount paid.')
                    moneyPaidInput && moneyPaidInput.focus()
                    return
                }
                if (amountPaid < net) {
                    showPaymentError('Amount paid (' + money(amountPaid) + ') is less than the total amount due (' + money(net) + ').')
                    moneyPaidInput && moneyPaidInput.focus()
                    return
                }

                var details = {
                    'Appointment ID': String(appointmentId),
                    'Patient': appointmentPatientName(selectedAppointment),
                    'Doctor': appointmentDoctorName(selectedAppointment),
                    'Services': appointmentServicesHtml(selectedAppointment),
                    'Gross Amount': money(gross),
                    'Discount Type': discountType,
                    'Discount Amount': money(discount),
                    'Net Amount': money(net),
                    'Payment Mode': 'cash',
                    'Transaction Date': transactionDatetime,
                    'Paid': money(amountPaid),
                    'Change': money(changeAmount),
                }

                setPaymentSubmitting(true)
                openReview(details)
                    .then(function (confirmed) {
                        if (!confirmed) {
                            setPaymentSubmitting(false)
                            return
                        }

                        return apiFetch("{{ url('/api/transactions') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                appointment_id: appointmentId,
                                amount: gross,
                                discount_type: discountType,
                                payment_mode: 'cash',
                                payment_status: 'paid',
                                money_paid: amountPaid,
                                transaction_datetime: transactionDatetime
                            })
                        })
                            .then(function (response) {
                                return response.json().then(function (data) { return { ok: response.ok, data: data } }).catch(function () { return { ok: response.ok, data: null } })
                            })
                            .then(function (result) {
                                if (!result.ok) {
                                    showPaymentError((result.data && result.data.message) ? String(result.data.message) : 'Failed to record payment.')
                                    return
                                }
                                // Use server response data for accurate Paid and Change values
                                var sv = result && result.data && result.data.data ? result.data.data : result.data
                                if (sv) {
                                    var svPaid = parseFloat(sv.money_paid != null ? sv.money_paid : amountPaid)
                                    var svChange = parseFloat(sv.money_change != null ? sv.money_change : changeAmount)
                                    if (isNaN(svPaid)) svPaid = amountPaid
                                    if (isNaN(svChange)) svChange = changeAmount
                                    details['Paid'] = money(svPaid)
                                    details['Change'] = money(svChange)
                                }
                                showReceipt(details)

                                // Walk-in: mark queue "done" + appointment "completed"
                                // Scheduled: mark appointment "completed"
                                var apptType = normalizeAppointmentType(selectedAppointment && selectedAppointment.appointment_type)
                                if (apptType === 'walk_in') {
                                    var queueId = selectedAppointment && selectedAppointment.queue && selectedAppointment.queue.queue_id
                                    if (queueId) {
                                        apiFetch("{{ url('/api/queues') }}/" + encodeURIComponent(queueId), {
                                            method: 'PUT',
                                            headers: { 'Content-Type': 'application/json' },
                                            body: JSON.stringify({ status: 'done' })
                                        })
                                    }
                                    apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(appointmentId), {
                                        method: 'PATCH',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ status: 'completed' })
                                    })
                                } else {
                                    apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(appointmentId), {
                                        method: 'PATCH',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ status: 'completed' })
                                    })
                                }

                                resetAppointmentSelection()
                                showDiscount = false
                                updateDiscountUI()
                                loadTransactions()
                            })
                    })
                    .catch(function () {
                        showPaymentError('Network error while recording payment.')
                    })
                    .finally(function () {
                        setPaymentSubmitting(false)
                    })
            })
        }

        if (txTodayBtn) {
            txSetTodayButton()
            txTodayBtn.addEventListener('click', function () {
                txTodayOnly = !txTodayOnly
                txSetTodayButton()
                txCurrentPage = 1
                loadTransactions()
            })
        }
        if (txRefresh) txRefresh.addEventListener('click', function () { txCurrentPage = 1; loadTransactions() })
        if (txType) txType.addEventListener('change', function () { txCurrentPage = 1; loadTransactions() })
        if (txStatus) txStatus.addEventListener('change', function () { txCurrentPage = 1; loadTransactions() })
        if (txDate) txDate.addEventListener('input', function () { txCurrentPage = 1; loadTransactions() })
        if (txSearch) {
            txSearch.addEventListener('input', function () {
                if (transactionsSearchTimer) clearTimeout(transactionsSearchTimer)
                transactionsSearchTimer = setTimeout(function () { txCurrentPage = 1; loadTransactions() }, 250)
            })
        }
        if (txServiceSearch) {
            txServiceSearch.addEventListener('focus', function () {
                loadTxServices()
                renderTxServiceResults()
            })
            txServiceSearch.addEventListener('input', function () {
                if (txServiceId && txServiceId.value) {
                    var picked = txServices.find(function (s) { return String(s.service_id) === String(txServiceId.value) }) || null
                    var pickedName = picked && picked.service_name ? String(picked.service_name) : ''
                    if (normalizeText(txServiceSearch.value) !== normalizeText(pickedName)) {
                        setTxServiceSelection(null)
                        txCurrentPage = 1
                        loadTransactions()
                    }
                }
                loadTxServices()
                renderTxServiceResults()
            })
        }
        if (txServiceResults) {
            txServiceResults.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-service-id]') : null
                if (!btn) return
                var id = btn.getAttribute('data-service-id')
                var picked = txServices.find(function (s) { return String(s.service_id) === String(id) }) || null
                setTxServiceSelection(picked)
                txCurrentPage = 1
                loadTransactions()
            })
        }

        document.addEventListener('click', function (e) {
            var target = e.target
            if (txServiceResults && !txServiceResults.classList.contains('hidden')) {
                if (!(txServiceResults.contains(target) || (txServiceSearch && txServiceSearch.contains(target)))) {
                    txServiceResults.classList.add('hidden')
                }
            }
        })

        // ── Reverb: real-time updates for appointment modal ──
        if (window.Echo) {
            window.Echo.private('appointments.all')
                .listen('.appointment.updated', function (e) {
                    // Refresh the appointment modal list if open
                    if (apptModal && !apptModal.classList.contains('hidden')) {
                        var searchTerm = apptModalSearch ? String(apptModalSearch.value || '').trim() : ''
                        loadTodayAppointments(searchTerm)
                    }
                    // If the currently selected appointment was updated, refresh details
                    if (apptModalSelectedAppt && e && e.slotData) {
                        var updatedId = String(e.slotData.id || e.slotData.appointment_id || '')
                        var selectedId = String(apptModalSelectedAppt.id || apptModalSelectedAppt.appointment_id || '')
                        if (updatedId && selectedId && updatedId === selectedId) {
                            apptModalSelectedAppt = e.slotData
                            if (apptModal && !apptModal.classList.contains('hidden')) {
                                renderApptDetail(apptModalSelectedAppt)
                            }
                        }
                    }
                })
        }

        loadTransactions(1)
    })
</script>
