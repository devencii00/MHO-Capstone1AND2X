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
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Record payment</h2>
                <p class="text-xs text-slate-500">Select an appointment, review totals, and confirm payment.</p>
            </div>
            <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Billing</span>
        </div>

        <div id="receptionPaymentError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionPaymentSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

        <form id="receptionPaymentForm" class="grid gap-3 grid-cols-1 md:grid-cols-4 items-end mb-4">
            <div class="min-w-0">
                <label for="reception_payment_appointment_id" class="block text-[0.7rem] text-slate-600 mb-1">Appointment</label>
                <div class="relative">
                    <input id="reception_payment_appointment_display" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select appointment">
                    <input id="reception_payment_appointment_id" type="hidden" required>
                    <button id="receptionPaymentBrowseBtn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                        Browse
                    </button>
                </div>
            </div>

            <div id="receptionPaymentAppointmentPreview" class="hidden md:col-span-4 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.72rem] font-semibold text-slate-800 uppercase tracking-wider">Appointment Summary</span>
                    <span id="receptionPaymentApptTypeBadge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-medium bg-blue-50 text-blue-700 border border-blue-200"></span>
                </div>
                <div class="grid grid-cols-3 gap-3 text-[0.78rem]">
                    <div>
                        <span class="block text-[0.65rem] text-slate-500">Patient</span>
                        <span id="receptionPaymentSummaryPatient" class="font-semibold text-slate-800"></span>
                    </div>
                    <div>
                        <span class="block text-[0.65rem] text-slate-500">Doctor</span>
                        <span id="receptionPaymentSummaryDoctor" class="font-semibold text-slate-800"></span>
                    </div>
                    <div>
                        <span class="block text-[0.65rem] text-slate-500">Subtotal Fees</span>
                        <span id="receptionPaymentSummarySubtotal" class="font-semibold text-green-700"></span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[0.7rem] text-slate-600 mb-1">Services in appointment</label>
                <div id="receptionPaymentServicesDisplay" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 min-h-[2.5rem]">Select an appointment first</div>
            </div>
            <div>
                <label class="block text-[0.7rem] text-slate-600 mb-1">Original amount</label>
                <div id="receptionPaymentAmountDisplay" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800">PHP 0.00</div>
            </div>
            <div>
                <label class="block text-[0.7rem] text-slate-600 mb-1">Net amount</label>
                <div id="receptionPaymentNetAmountDisplay" class="w-full rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-800">PHP 0.00</div>
            </div>

            <div class="md:col-span-4 border-t border-slate-200 pt-3 mt-1">
                <div class="text-[0.65rem] font-semibold text-slate-500 uppercase tracking-wider mb-2">Patient Payment</div>
                <div class="grid gap-3 grid-cols-1 md:grid-cols-2">
                    <div>
                        <label for="reception_payment_money_paid" class="block text-[0.7rem] text-slate-600 mb-1">Money paid</label>
                        <input id="reception_payment_money_paid" type="text" inputmode="decimal" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="md:col-span-4">
                <button id="receptionPaymentToggleDiscount" type="button" class="inline-flex items-center text-[0.75rem] font-semibold text-green-700 hover:text-green-800">Add discount</button>
            </div>

            <div id="receptionPaymentDiscountWrap" class="hidden md:col-span-4 grid gap-3 grid-cols-1 md:grid-cols-2">
                <div>
                    <label for="reception_payment_discount_type" class="block text-[0.7rem] text-slate-600 mb-1">Discount type</label>
                    <select id="reception_payment_discount_type" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="none" selected>No discount</option>
                        <option value="pwd">PWD (15%)</option>
                        <!-- <option value="pregnant">Pregnant (10%)</option> -->
                        <option value="senior">Senior (5%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[0.7rem] text-slate-600 mb-1">Discount amount</label>
                    <div id="receptionPaymentDiscountAmountDisplay" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800">PHP 0.00</div>
                </div>
            </div>

            <div>
                <label class="block text-[0.7rem] text-slate-600 mb-1">Payment mode</label>
                <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">Cash</div>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button id="receptionPaymentSubmit" type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                    <span id="receptionPaymentSubmitSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="receptionPaymentSubmitLabel">Record payment</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Appointment Selection Modal -->
    <div id="receptionPaymentAppointmentModal" class="hidden fixed inset-0 z-[70] bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4">
        <div class="w-full max-w-4xl h-[80vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] overflow-hidden flex flex-col">
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
                    <div class="px-4 py-2 border-b border-slate-100 shrink-0 bg-slate-50/50">
                        <input id="receptionPaymentApptSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search today's appointments...">
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
        <div class="flex items-center justify-between mb-3 gap-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Transactions record</h3>
                <p class="text-xs text-slate-500">Search and review billing transactions.</p>
            </div>
            <div class="flex items-center gap-2">
                <button id="receptionTransactionsTodayOnlyBtn" type="button" class="shrink-0 inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.75rem] font-semibold text-slate-700 hover:bg-slate-50">Show today only</button>
                <button type="button" id="recTransRefreshBtn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                    <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                    Refresh
                </button>
            </div>
        </div>

        <div id="receptionTransactionsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

        <div class="grid gap-3 grid-cols-1 md:grid-cols-5 items-start mb-4">
            <div class="md:col-span-2 min-w-0">
                <label for="receptionTransactionsSearch" class="block text-[0.7rem] text-slate-600 mb-1">Search</label>
                <input id="receptionTransactionsSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by patient name or ref #">
            </div>
            <div class="min-w-0">
                <label for="receptionTransactionsServiceSearch" class="block text-[0.7rem] text-slate-600 mb-1">Service</label>
                <div class="relative">
                    <input id="receptionTransactionsServiceSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="All services" autocomplete="off">
                    <input id="receptionTransactionsServiceId" type="hidden">
                    <div id="receptionTransactionsServiceResults" class="hidden absolute left-0 right-0 top-full mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto overscroll-contain z-50"></div>
                </div>
            </div>
            <div class="min-w-0">
                <label for="receptionTransactionsSort" class="block text-[0.7rem] text-slate-600 mb-1">Sort by date</label>
                <select id="receptionTransactionsSort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="latest">Latest first</option>
                    <option value="oldest">Oldest first</option>
                </select>
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
<div id="receptionPaymentReviewOverlay" class="hidden fixed inset-0 z-[80] bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <!-- Header -->
        <div class="px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shadow-sm flex-shrink-0">
                    <x-lucide-receipt class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="receptionPaymentReviewTitle" class="text-base font-semibold text-slate-800 tracking-tight">Review Payment Details</h3>
                    <p id="receptionPaymentReviewSubtitle" class="text-xs text-slate-500 mt-0.5">Please verify all payment information before confirming</p>
                </div>
            </div>
        </div>

        <!-- Receipt content area -->
        <div class="px-5 py-4 bg-white">
            <div id="receptionPaymentReviewContent" class="bg-white rounded-xl border-2 border-slate-200 p-5 text-sm text-slate-700 font-mono leading-relaxed">
                <!-- Dynamic receipt content will be injected here -->
                <div class="text-center text-slate-400 py-4">Loading receipt data...</div>
            </div>
        </div>

        <!-- Footer buttons -->
        <div id="receptionPaymentReviewFooter" class="px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionPaymentReviewCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150">Cancel</button>
            <button type="button" id="receptionPaymentReviewConfirm" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 shadow-sm transition-all duration-150">Confirm Payment</button>
            <button type="button" id="receptionPaymentPrintBtn" class="hidden px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 shadow-sm transition-all duration-150">Print</button>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #receptionPaymentReviewOverlay,
        #receptionPaymentReviewOverlay * { visibility: visible; }
        #receptionPaymentReviewOverlay {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            z-index: 9999 !important;
            background: white !important;
            backdrop-filter: none !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;
            padding: 0.5in !important;
        }
        #receptionPaymentReviewOverlay .rounded-2xl {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
        #receptionPaymentReviewOverlay #receptionPaymentReviewFooter { display: none !important; }
        #receptionPaymentReviewOverlay #receptionPaymentReviewContent {
            border-color: #ccc !important;
        }
    }
</style>

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
        var moneyPaidInput = document.getElementById('reception_payment_money_paid')
        if (moneyPaidInput) {
            moneyPaidInput.addEventListener('input', function () {
                var raw = this.value.replace(/[^0-9.]/g, '')
                var parts = raw.split('.')
                if (parts.length > 2) parts = [parts[0], parts.slice(1).join('')]
                if (parts[0]) {
                    parts[0] = parseInt(parts[0], 10).toLocaleString('en-US')
                }
                this.value = parts[0] + (parts.length > 1 && parts[1] !== undefined ? '.' + parts[1] : '')
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
        var reviewPrintBtn = document.getElementById('receptionPaymentPrintBtn')
        var reviewConfirmDefaultHtml = reviewConfirm ? reviewConfirm.innerHTML : ''
        var reviewResolver = null
        var reviewDelayTimer = null

        var apptModal = document.getElementById('receptionPaymentAppointmentModal')
        var apptModalClose = document.getElementById('receptionPaymentApptModalClose')
        var apptModalCancel = document.getElementById('receptionPaymentApptModalCancel')
        var apptModalSelect = document.getElementById('receptionPaymentApptModalSelect')
        var apptModalSearch = document.getElementById('receptionPaymentApptSearch')
        var apptList = document.getElementById('receptionPaymentApptList')
        var apptDetail = document.getElementById('receptionPaymentApptDetail')
        var browseBtn = document.getElementById('receptionPaymentBrowseBtn')
        var todayAppointments = []
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
        var txSortOrder = 'latest'
        var txTodayBtn = document.getElementById('receptionTransactionsTodayOnlyBtn')

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
            if (message && typeof showToast === 'function') showToast(message, 'error')
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
            if (!name) name = 'User #' + (p.user_id || '')
            return name
        }

        function appointmentDoctorName(appt) {
            var d = appt && appt.doctor ? appt.doctor : null
            if (!d) return 'Doctor'
            var name = [d.firstname, d.middlename, d.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            if (!name) name = 'User #' + (d.user_id || '')
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
                var price = s && s.price != null ? parseFloat(s.price) : 0
                if (isNaN(price)) price = 0
                return { name: name, price: price }
            }).filter(function (x) { return x.name !== '' })
        }

        function appointmentServicesHtml(appt) {
            var svcs = servicesFromAppointment(appt)
            if (!svcs.length) return ''
            return svcs.map(function (s) {
                return '<div style="display:flex;justify-content:space-between;font-size:0.72rem;padding:1px 0;"><span>' + escapeHtml(s.name) + '</span><span>' + escapeHtml(money(s.price)) + '</span></div>'
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
        }

        function resetAppointmentSelection() {
            selectedAppointment = null
            if (appointmentIdInput) appointmentIdInput.value = ''
            if (appointmentDisplay) appointmentDisplay.value = ''
            if (appointmentPreview) {
                appointmentPreview.textContent = ''
                appointmentPreview.classList.add('hidden')
            }
            if (servicesDisplay) servicesDisplay.textContent = 'Select an appointment first'
            if (moneyPaidInput) moneyPaidInput.value = ''
            refreshTotalsUI()
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
                appointmentDisplay.value = '#' + String(appt.appointment_id) + ' - ' + appointmentPatientName(appt)
            }

            var serviceRows = servicesFromAppointment(appt)
            if (servicesDisplay) {
                if (!serviceRows.length) {
                    servicesDisplay.textContent = 'No services linked to this appointment.'
                } else {
                    servicesDisplay.innerHTML = serviceRows.map(function (s) {
                        return '<div class="flex items-center justify-between gap-2 py-1 border-b border-slate-200/60 last:border-0"><span class="truncate">' + escapeHtml(s.name) + '</span><span class="shrink-0 font-semibold">' + escapeHtml(money(s.price)) + '</span></div>'
                    }).join('')
                }
            }

            var gross = originalAmount(appt)
            if (appointmentPreview) {
                var patientEl = document.getElementById('receptionPaymentSummaryPatient')
                var doctorEl = document.getElementById('receptionPaymentSummaryDoctor')
                var subtotalEl = document.getElementById('receptionPaymentSummarySubtotal')
                var typeBadge = document.getElementById('receptionPaymentApptTypeBadge')
                if (patientEl) patientEl.textContent = appointmentPatientName(appt)
                if (doctorEl) doctorEl.textContent = appointmentDoctorName(appt)
                if (subtotalEl) subtotalEl.textContent = money(gross)
                if (typeBadge) typeBadge.textContent = appointmentTypeLabel(appt)
                appointmentPreview.classList.remove('hidden')
            }

            refreshTotalsUI()
        }

        function setPaymentSubmitting(isSubmitting) {
            if (paymentSubmit) paymentSubmit.disabled = !!isSubmitting
            if (paymentSubmitSpinner) paymentSubmitSpinner.classList.toggle('hidden', !isSubmitting)
            if (paymentSubmitLabel) paymentSubmitLabel.textContent = isSubmitting ? 'Saving...' : 'Record payment'
        }

        function formatReceiptHtml(details, isFinalized) {
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
            var title = isFinalized ? 'OFFICIAL RECEIPT' : 'PAYMENT REVIEW'
            var separator = '─'.repeat(40)

            var html = ''
            html += '<div style="text-align:center;font-weight:700;font-size:1rem;margin-bottom:8px;">' + escapeHtml(title) + '</div>'
            html += '<div style="text-align:center;font-size:0.72rem;color:#888;margin-bottom:12px;">Opol Municipal Health Office</div>'
            html += '<div style="border-top:2px dashed #aaa;margin:8px 0;"></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Patient:</span><span style="font-weight:600;">' + escapeHtml(patient) + '</span></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Doctor:</span><span style="font-weight:600;">' + escapeHtml(doctor) + '</span></div>'
            if (servicesHtml) {
                html += '<div style="border-top:1px dashed #ccc;margin:6px 0;"></div>'
                html += '<div style="font-size:0.72rem;color:#555;margin-bottom:2px;">Services:</div>'
                html += servicesHtml
            }
            html += '<div style="border-top:1px dashed #ccc;margin:6px 0;"></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Gross Amount:</span><span>' + escapeHtml(gross) + '</span></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Discount Type:</span><span>' + escapeHtml(discType) + '</span></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Discount Amount:</span><span>' + escapeHtml(discAmt) + '</span></div>'
            html += '<div style="border-top:1px dashed #ccc;margin:6px 0;"></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;font-weight:700;"><span>Net Amount:</span><span>' + escapeHtml(net) + '</span></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Payment Mode:</span><span>' + escapeHtml(mode) + '</span></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Transaction Date:</span><span>' + escapeHtml(txnDate) + '</span></div>'
            html += '<div style="border-top:2px dashed #aaa;margin:8px 0;"></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;font-size:1.05rem;"><span>Paid:</span><span style="font-weight:700;">' + escapeHtml(paid) + '</span></div>'
            html += '<div style="display:flex;justify-content:space-between;padding:2px 0;"><span>Change:</span><span style="font-weight:600;">' + escapeHtml(change) + '</span></div>'
            html += '<div style="border-top:2px dashed #aaa;margin:8px 0;"></div>'
            if (isFinalized) {
                html += '<div style="text-align:center;font-size:0.68rem;color:#888;margin-top:4px;">Thank you for your payment!</div>'
            } else {
                html += '<div style="text-align:center;font-size:0.68rem;color:#e67e22;margin-top:4px;">Please verify before confirming.</div>'
            }
            return html
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
            if (reviewPrintBtn) reviewPrintBtn.classList.add('hidden')
            if (reviewCancel) reviewCancel.classList.remove('hidden')
            reviewConfirm.classList.remove('hidden')
            reviewConfirming = false
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
                reviewPrintBtn.classList.add('hidden')
                reviewCancel.classList.remove('hidden')
                reviewConfirm.classList.remove('hidden')
                reviewConfirm.disabled = true
                reviewConfirming = false
                reviewConfirm.innerHTML = reviewConfirmDefaultHtml || 'Confirm Payment'

                reviewContent.innerHTML = formatReceiptHtml(details, false)
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
            if (!reviewOverlay || !reviewContent) return
            if (reviewTitle) reviewTitle.textContent = 'Payment Receipt'
            if (reviewSubtitle) reviewSubtitle.textContent = 'Payment has been recorded successfully'
            reviewConfirm.classList.add('hidden')
            reviewCancel.classList.add('hidden')
            reviewPrintBtn.classList.remove('hidden')
            reviewContent.innerHTML = formatReceiptHtml(details, true)
            reviewOverlay.classList.remove('hidden')
            reviewOverlay.classList.add('flex')
        }

        // ── Appointment Modal Functions ──

        function loadTodayAppointments(query) {
            if (typeof apiFetch !== 'function') return
            if (apptList) apptList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading appointments...</div>'
            var url = "{{ url('/api/appointments') }}" + '?per_page=15&order=latest&today_only=1&status=consulted'
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
            if (!list.length) {
                apptList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No appointments found for today.</div>'
                return
            }
            apptList.innerHTML = list.map(function (appt, idx) {
                var id = appt && appt.appointment_id != null ? appt.appointment_id : ''
                var patient = appointmentPatientName(appt)
                var when = appt && appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
                var type = appointmentTypeLabel(appt)
                var timeOnly = when.slice(11, 16)
                return '<button type="button" class="appt-list-item w-full text-left px-3 py-2 rounded-lg hover:bg-green-50 border border-transparent hover:border-green-200 transition-colors" data-index="' + idx + '">' +
                    '<div class="text-[0.78rem] text-slate-800 font-semibold">' + escapeHtml(patient) + '</div>' +
                    '<div class="text-[0.7rem] text-slate-500">' + escapeHtml(timeOnly + ' • ' + type) + '</div>' +
                '</button>'
            }).join('')

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
            var appt = todayAppointments[index]
            if (!appt) return
            apptModalSelectedAppt = appt
            // Update left panel selection highlight
            apptList.querySelectorAll('.appt-list-item').forEach(function (el, i) {
                if (i === index) {
                    el.classList.add('bg-green-100', 'border-green-300')
                    el.classList.remove('hover:bg-green-50', 'border-transparent')
                } else {
                    el.classList.remove('bg-green-100', 'border-green-300')
                    el.classList.add('hover:bg-green-50', 'border-transparent')
                }
            })
            // Enable select button
            if (apptModalSelect) apptModalSelect.disabled = false
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

            var servicesHtml = services.length
                ? services.map(function (s) {
                    return '<div class="flex items-center justify-between py-1 text-[0.78rem]"><span>' + escapeHtml(s.name) + '</span><span class="font-semibold">' + escapeHtml(money(s.price)) + '</span></div>'
                }).join('')
                : '<div class="text-[0.75rem] text-slate-400">No services</div>'

            apptDetail.innerHTML =
                '<div class="space-y-2 text-[0.78rem]">' +
                    '<div><span class="font-semibold text-slate-800">Patient:</span> <span class="text-slate-700">' + escapeHtml(patient) + '</span></div>' +
                    '<div><span class="font-semibold text-slate-800">Doctor:</span> <span class="text-slate-700">' + escapeHtml(doctor) + '</span></div>' +
                    '<div><span class="font-semibold text-slate-800">Date/Time:</span> <span class="text-slate-700">' + escapeHtml(when) + '</span></div>' +
                    '<div><span class="font-semibold text-slate-800">Type:</span> <span class="text-slate-700">' + escapeHtml(appointmentTypeLabel(appt)) + '</span></div>' +
                    '<div class="border-t border-slate-200 pt-2 mt-2">' +
                        '<div class="text-[0.72rem] font-semibold text-slate-600 mb-1">Services</div>' +
                        servicesHtml +
                        '<div class="border-t border-slate-200 mt-1 pt-1 flex items-center justify-between text-[0.82rem] font-bold">' +
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
                var price = s && s.price != null ? parseFloat(s.price) : 0
                if (isNaN(price)) price = 0
                if (!name) return ''
                return '<div style="display:flex;justify-content:space-between;font-size:0.72rem;padding:1px 0;"><span>' + escapeHtml(name) + '</span><span>' + escapeHtml(price.toFixed(2)) + '</span></div>'
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
            txAllRows = Array.isArray(rows) ? rows : []
            var list = txAllRows
            if (!list.length) {
                txTableBody.innerHTML = '<tr><td colspan="7" class="px-3 py-6 text-center text-[0.78rem] text-slate-500">No transactions found.</td></tr>'
                if (txPagination) txPagination.innerHTML = ''
                txCurrentPage = 1
                return
            }

            // Deduplicate by patient — keep the latest transaction per patient
            var patientMap = {}
            list.forEach(function (tx) {
                var appt = tx && tx.appointment ? tx.appointment : null
                var patient = appt && appt.patient ? appt.patient : null
                var pid = patient && patient.user_id != null ? String(patient.user_id) : ''
                if (!pid) return
                var existing = patientMap[pid]
                var txDate = tx.transaction_datetime || tx.created_at || ''
                if (!existing || (txDate > (existing.transaction_datetime || existing.created_at || ''))) {
                    patientMap[pid] = tx
                }
            })
            var deduped = Object.keys(patientMap).map(function (k) { return patientMap[k] })
            // Keep sort order from loadTransactions
            deduped.sort(function (a, b) {
                var da = (a.transaction_datetime || a.created_at || '')
                var db = (b.transaction_datetime || b.created_at || '')
                if (txSortOrder === 'oldest') return da < db ? -1 : (da > db ? 1 : 0)
                return da < db ? 1 : (da > db ? -1 : 0)
            })

            var totalPages = Math.ceil(deduped.length / txPerPage)
            if (txCurrentPage > totalPages) txCurrentPage = totalPages
            if (txCurrentPage < 1) txCurrentPage = 1
            var start = (txCurrentPage - 1) * txPerPage
            var end = Math.min(start + txPerPage, deduped.length)
            var pageSlice = deduped.slice(start, end)
            txTableBody.innerHTML = pageSlice.map(function (tx) {
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
            var total = txAllRows.length
            var totalPages = Math.max(1, Math.ceil(total / txPerPage))
            if (txCurrentPage > totalPages) txCurrentPage = totalPages
            if (txCurrentPage < 1) txCurrentPage = 1
            if (total === 0) { txPagination.innerHTML = ''; return }
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="' + (txCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (txCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var ws = txCurrentPage
            var we = Math.min(ws + txVisibleCount - 1, totalPages)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === txCurrentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>' }
            html += '<button type="button" class="' + (txCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (txCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            txPagination.innerHTML = html
            txPagination.querySelectorAll('button[data-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-page')
                    if (p === 'prev' && txCurrentPage > 1) { txCurrentPage--; renderTransactions(txAllRows) }
                    else if (p === 'next' && txCurrentPage < totalPages) { txCurrentPage++; renderTransactions(txAllRows) }
                    else if (p === 'next-window') { var ns = Math.min(we + 1, totalPages); txCurrentPage = ns; renderTransactions(txAllRows) }
                    else if (p !== 'prev' && p !== 'next') { txCurrentPage = parseInt(p, 10); renderTransactions(txAllRows) }
                })
            })
        }

        function loadTransactions() {
            if (typeof apiFetch !== 'function') return
            showTransactionsError('')
            txTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading transactions…</td></tr>'

            var url = "{{ url('/api/transactions') }}" + '?per_page=15'
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

                    rows.sort(function (a, b) {
                        function completeRank(tx) {
                            var appt = tx && tx.appointment ? tx.appointment : null
                            var status = String(appt && appt.status ? appt.status : '').toLowerCase()
                            return status === 'completed' ? 1 : 0
                        }
                        var ra = completeRank(a)
                        var rb = completeRank(b)
                        if (ra !== rb) return ra - rb
                        var da = String(a && a.transaction_datetime ? a.transaction_datetime : (a && a.created_at ? a.created_at : ''))
                        var db = String(b && b.transaction_datetime ? b.transaction_datetime : (b && b.created_at ? b.created_at : ''))
                        if (order === 'oldest') return da < db ? -1 : (da > db ? 1 : 0)
                        return da < db ? 1 : (da > db ? -1 : 0)
                    })

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
            var change = Math.max(0, paid - net)

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
            detailBody.innerHTML = '<div class="max-w-sm mx-auto">' + formatReceiptHtml(details, true) + '<div class="text-center mt-3"><button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300">\ud83d\udde8\ufe0f Print / PDF</button></div></div>'
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
        if (reviewPrintBtn) {
            reviewPrintBtn.addEventListener('click', function () { window.print() })
        }
        if (reviewOverlay) {
            reviewOverlay.addEventListener('click', function (e) {
                if (e.target === reviewOverlay) closeReview(false)
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
                loadTransactions()
            })
        }
        if (txRefresh) txRefresh.addEventListener('click', loadTransactions)
        if (txSort) txSort.addEventListener('change', loadTransactions)
        if (txSearch) {
            txSearch.addEventListener('input', function () {
                if (transactionsSearchTimer) clearTimeout(transactionsSearchTimer)
                transactionsSearchTimer = setTimeout(function () { loadTransactions() }, 250)
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

        loadTransactions()
    })
</script>
