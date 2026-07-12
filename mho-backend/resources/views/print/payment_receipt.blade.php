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
        #receiptRoot[data-state="review"] .receipt-accent { color: rgb(180 83 9); }
        #receiptRoot[data-state="finalized"] .receipt-accent { color: rgb(4 120 87); }
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

        <x-receipt-card />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var txnId = {{ $transactionId }};
            var root = document.getElementById('receiptRoot');
            var txnIdEl = root ? root.querySelector('.receipt-txn-id') : null;
            if (txnIdEl) txnIdEl.textContent = txnId;

            if (typeof apiFetch === 'function') {
                apiFetch('/api/transactions?transaction_id=' + encodeURIComponent(txnId), { method: 'GET' })
                    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }) })
                    .then(function (result) {
                        if (!result.ok) return;
                        var tx = Array.isArray(result.data.data) ? result.data.data[0] : (result.data.transaction || null);
                        if (!tx) tx = result.data.data || result.data;
                        if (Array.isArray(tx)) tx = tx[0];
                        if (!tx) return;
                        populateReceiptCard(tx);
                    })
                    .catch(function () {});
            }

            var printBtn = document.getElementById('receiptPrintBtn');
            if (printBtn) printBtn.addEventListener('click', function () { window.print(); });
        });

        function populateReceiptCard(tx) {
            var root = document.getElementById('receiptRoot');
            if (!root || !tx) return;

            var appt = tx.appointment || {};
            var patient = appt.patient || {};
            var doctor = appt.doctor || {};

            root.querySelector('.receipt-patient').textContent =
                [patient.firstname, patient.middlename, patient.lastname].filter(Boolean).join(' ') || '-';
            root.querySelector('.receipt-doctor').textContent =
                [doctor.firstname, doctor.lastname].filter(Boolean).join(' ') || '-';

            var servicesContainer = root.querySelector('.receipt-services');
            if (servicesContainer) {
                var txServices = appt.services || (tx.services) || [];
                if (Array.isArray(txServices) && txServices.length) {
                    servicesContainer.innerHTML = txServices.map(function (s) {
                        var name = s.service_name || s.name || 'Service';
                        var amt = s.price || s.amount || 0;
                        return '<div class="flex justify-between text-[0.85rem] receipt-figures"><span class="text-slate-700">' + escapeHtml(name) + '</span><span class="text-slate-800 font-medium">PHP ' + Number(amt).toLocaleString('en-US', {minimumFractionDigits:2}) + '</span></div>';
                    }).join('');
                } else {
                    servicesContainer.innerHTML = '<div class="flex justify-between text-slate-400"><span>No services</span><span>-</span></div>';
                }
            }

            var gross = parseFloat(tx.amount != null ? tx.amount : 0) || 0;
            var disc = parseFloat(tx.discount_amount != null ? tx.discount_amount : 0) || 0;
            var net = Math.max(0, gross - disc);
            var paid = parseFloat(tx.money_paid != null ? tx.money_paid : gross) || gross;
            var change = parseFloat(tx.money_change != null ? tx.money_change : Math.max(0, paid - net)) || 0;
            var discType = tx.discount_type || 'none';
            var mode = (tx.payment_mode || 'cash').toUpperCase();
            var txnDate = tx.transaction_datetime ? String(tx.transaction_datetime).replace('T', ' ').slice(0, 16) : '-';
            var payStatus = (tx.payment_status || '').toLowerCase();

            root.querySelector('.receipt-gross').textContent = 'PHP ' + gross.toLocaleString('en-US', {minimumFractionDigits:2});
            root.querySelector('.receipt-discount-type').textContent = discType;
            root.querySelector('.receipt-discount-amount').textContent = 'PHP ' + disc.toLocaleString('en-US', {minimumFractionDigits:2});
            root.querySelector('.receipt-net').textContent = 'PHP ' + net.toLocaleString('en-US', {minimumFractionDigits:2});
            root.querySelector('.receipt-mode').textContent = mode;
            root.querySelector('.receipt-date').textContent = txnDate;

            if (payStatus === 'pending') {
                root.querySelector('.receipt-gross').textContent = '\u2014';
                root.querySelector('.receipt-discount-type').textContent = '\u2014';
                root.querySelector('.receipt-discount-amount').textContent = '\u2014';
                root.querySelector('.receipt-net').textContent = '\u2014';
                root.querySelector('.receipt-mode').textContent = '\u2014';
                root.querySelector('.receipt-paid').textContent = '\u2014';
                root.querySelector('.receipt-change').textContent = '\u2014';
            } else {
                root.querySelector('.receipt-paid').textContent = 'PHP ' + paid.toLocaleString('en-US', {minimumFractionDigits:2});
                root.querySelector('.receipt-change').textContent = 'PHP ' + change.toLocaleString('en-US', {minimumFractionDigits:2});
            }

            var meta = root.querySelector('#receiptMeta');
            if (meta && txnDate) meta.textContent = '\u00B7  ' + txnDate;
        }

        function escapeHtml(str) {
            if (typeof str !== 'string') return str;
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }
    </script>
</body>
</html>
