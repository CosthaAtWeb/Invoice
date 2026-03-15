<?php

include('header.php');
include('functions.php');

?>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
  :root {
    --bg: #f4f6fb;
    --card: #ffffff;
    --border: #e2e8f0;
    --accent: #2563eb;
    --accent-light: #eff6ff;
    --accent-dark: #1d4ed8;
    --success: #16a34a;
    --danger: #dc2626;
    --text: #0f172a;
    --muted: #64748b;
    --soft: #94a3b8;
    --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    --shadow-lg: 0 8px 32px rgba(37,99,235,0.10);
    --radius: 12px;
    --radius-sm: 8px;
  }

  body, .content-wrapper {
    background: var(--bg) !important;
    font-family: 'DM Sans', sans-serif !important;
  }

  /* ── Page Header ── */
  .inv-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
  }
  .inv-page-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    color: var(--text);
    margin: 0;
  }
  .inv-page-header h2 span {
    color: var(--accent);
  }
  .inv-badge-type {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--accent-light);
    color: var(--accent);
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  /* ── Alert ── */
  #response {
    border-radius: var(--radius);
    border: none;
    box-shadow: var(--shadow);
  }

  /* ── Cards ── */
  .inv-card {
    background: var(--card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    margin-bottom: 20px;
    overflow: hidden;
    animation: fadeUp 0.4s ease both;
  }
  .inv-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: linear-gradient(90deg, #f8faff 0%, #fff 100%);
    border-bottom: 1px solid var(--border);
  }
  .inv-card-header h4 {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--muted);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .inv-card-header h4 i {
    color: var(--accent);
    font-size: 14px;
  }
  .inv-card-header a {
    font-size: 12.5px;
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: opacity 0.2s;
  }
  .inv-card-header a:hover { opacity: 0.7; }
  .inv-card-body { padding: 20px; }

  /* ── Top Meta Row ── */
  .inv-meta-wrapper {
    background: var(--card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    padding: 14px 18px;
    margin-bottom: 20px;
    animation: fadeUp 0.3s ease both;
    max-width: 520px;  /* constrain the width */
  }
  .inv-meta-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    align-items: end;
  }
  .inv-meta-row + .inv-meta-row {
    margin-top: 10px;
  }
  .inv-meta-group label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--soft);
    display: block;
    margin-bottom: 4px;
  }
  .inv-meta-group select,
  .inv-meta-group input[type="date"],
  .inv-meta-group input[type="text"] {
    width: 100%;
    height: 34px;       /* reduced from 40px */
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0 10px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    background: #f8faff;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
  }
  .inv-meta-group select:focus,
  .inv-meta-group input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  .inv-invoice-num {
    display: flex;
    align-items: center;
    height: 34px;       /* reduced from 40px */
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: #f8faff;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .inv-invoice-num:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  .inv-invoice-num span {
    background: var(--accent-light);
    color: var(--accent);
    font-size: 12px;
    font-weight: 700;
    padding: 0 10px;
    height: 100%;
    display: flex;
    align-items: center;
    border-right: 1.5px solid var(--border);
    white-space: nowrap;
  }
  .inv-invoice-num input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    flex: 1;
    padding: 0 12px;
    font-size: 13.5px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    outline: none;
    height: 100%;
  }

  /* ── Form Controls ── */
  .inv-form-control {
    width: 100%;
    height: 38px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0 12px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    background: #f8faff;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    margin-bottom: 10px;
  }
  .inv-form-control:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  .inv-form-control::placeholder { color: var(--soft); }
  .inv-input-icon {
    position: relative;
    margin-bottom: 10px;
  }
  .inv-input-icon i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--soft);
    font-size: 13px;
    pointer-events: none;
  }
  .inv-input-icon input {
    padding-left: 34px;
    margin-bottom: 0;
  }

  /* ── Client columns ── */
  .inv-client-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
  }

  /* ── Items Table ── */
  .inv-table-wrap {
    background: var(--card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
    animation: fadeUp 0.5s ease both;
  }
  .inv-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: linear-gradient(90deg, #f8faff 0%, #fff 100%);
    border-bottom: 1px solid var(--border);
  }
  .inv-table-header h4 {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--muted);
    margin: 0;
  }
  .btn-add-row {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 7px 14px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s, transform 0.15s;
  }
  .btn-add-row:hover {
    background: var(--accent-dark);
    color: #fff;
    transform: translateY(-1px);
  }
  table#invoice_table {
    width: 100%;
    border-collapse: collapse;
  }
  table#invoice_table thead tr {
    background: #f8faff;
  }
  table#invoice_table thead th {
    padding: 11px 16px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    border-top: none;
    border-left: none;
    border-right: none;
  }
  table#invoice_table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
  }
  table#invoice_table tbody tr:hover { background: #fafbff; }
  table#invoice_table tbody td {
    padding: 10px 12px;
    border: none;
    vertical-align: middle;
  }
  table#invoice_table .form-control,
  table#invoice_table input,
  table#invoice_table select {
    height: 36px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0 10px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    background: #f8faff;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    width: 100%;
    box-shadow: none;
  }
  table#invoice_table input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  table#invoice_table input:disabled {
    background: #f1f5f9;
    color: var(--muted);
    cursor: not-allowed;
  }
  .inp-with-prefix {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: #f8faff;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .inp-with-prefix:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  .inp-with-prefix span {
    background: #f1f5f9;
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
    padding: 0 10px;
    height: 36px;
    display: flex;
    align-items: center;
    border-right: 1px solid var(--border);
    white-space: nowrap;
  }
  .inp-with-prefix input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    flex: 1;
  }
  .btn-del-row {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: #fef2f2;
    color: var(--danger);
    border: 1px solid #fecaca;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    font-size: 12px;
    flex-shrink: 0;
  }
  .btn-del-row:hover {
    background: var(--danger);
    color: #fff;
    border-color: var(--danger);
  }
  .item-select {
    font-size: 11px;
    color: var(--soft);
    margin: 4px 0 0 0;
  }
  .item-select a { color: var(--accent); text-decoration: none; font-weight: 500; }
  .item-select a:hover { text-decoration: underline; }
  .product-cell { display: flex; align-items: flex-start; gap: 8px; }
  .product-cell .product-input-wrap { flex: 1; }

  /* ── Totals + Notes ── */
  .inv-bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
  }
  .inv-notes textarea {
    width: 100%;
    height: 130px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    background: #f8faff;
    resize: vertical;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .inv-notes textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  .inv-totals-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }
  .inv-totals-card .tot-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 20px;
    border-bottom: 1px solid var(--border);
    font-size: 13.5px;
  }
  .inv-totals-card .tot-row:last-child { border-bottom: none; }
  .inv-totals-card .tot-row strong { color: var(--text); font-weight: 600; }
  .inv-totals-card .tot-row .tot-val { color: var(--muted); font-size: 14px; }
  .inv-totals-card .tot-total {
    background: linear-gradient(90deg, #1e3a8a 0%, #2563eb 100%);
    color: #fff !important;
  }
  .inv-totals-card .tot-total strong,
  .inv-totals-card .tot-total .tot-val { color: #fff !important; font-size: 16px; font-weight: 700; }
  .shipping-input-row {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: #f8faff;
    width: 140px;
    transition: border-color 0.2s;
  }
  .shipping-input-row:focus-within { border-color: var(--accent); }
  .shipping-input-row span {
    background: #f1f5f9;
    color: var(--muted);
    font-size: 12px;
    padding: 0 9px;
    height: 34px;
    display: flex;
    align-items: center;
    border-right: 1px solid var(--border);
  }
  .shipping-input-row input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    flex: 1;
    height: 34px;
    padding: 0 9px;
    font-size: 13px;
    outline: none;
    font-family: 'DM Sans', sans-serif;
    width: 80px;
  }
  .vat-remove-label {
    font-size: 11px;
    color: var(--soft);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
  }

  /* ── Actions Bar ── */
  .inv-actions-bar {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
  }
  .inv-actions-bar input[type="email"] {
    flex: 1;
    min-width: 240px;
    height: 42px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0 14px 0 36px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    background: #f8faff;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .inv-actions-bar input[type="email"]:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
  }
  .inv-email-wrap {
    position: relative;
    flex: 1;
  }
  .inv-email-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--soft);
    font-size: 13px;
    pointer-events: none;
  }
  .btn-create-invoice {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 0 28px;
    height: 42px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    transition: all 0.2s;
    white-space: nowrap;
  }
  .btn-create-invoice:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(37,99,235,0.4);
  }

  /* ── Animations ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .inv-card:nth-child(1) { animation-delay: 0.05s; }
  .inv-card:nth-child(2) { animation-delay: 0.10s; }

  /* ── Modal ── */
  .modal-content { border-radius: var(--radius); border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
  .modal-header { background: linear-gradient(90deg, #f8faff, #fff); border-bottom: 1px solid var(--border); border-radius: var(--radius) var(--radius) 0 0; }
  .modal-title { font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 16px; color: var(--text); }

  /* ── Responsive ── */
  @media (max-width: 900px) {
    .inv-meta-row { grid-template-columns: 1fr 1fr; } /* already 2-col, no change needed */
    .inv-client-grid { grid-template-columns: 1fr; }
    .inv-bottom-row { grid-template-columns: 1fr; }
  }
  @media (max-width: 600px) {
    .inv-meta-row { grid-template-columns: 1fr; }
  }
</style>

<!-- Page Alert -->
<div id="response" class="alert alert-success" style="display:none;">
  <a href="#" class="close" data-dismiss="alert">&times;</a>
  <div class="message"></div>
</div>

<form method="post" id="create_invoice">
  <input type="hidden" name="action" value="create_invoice">

  <!-- Page Header -->
  <div class="inv-page-header">
    <h2>New <span class="invoice_type">Invoice</span></h2>
    <div style="display:flex;align-items:center;gap:10px;">
      <div class="inv-badge-type">
        <i class="fa fa-file-text-o"></i>
        <span id="type_badge_label">Invoice</span>
      </div>
    </div>
  </div>

 <!-- Meta: Type/Status | Dates | Invoice # -->
<div class="inv-meta-wrapper">

  <!-- Row 1: Type & Status -->
  <div class="inv-meta-row">
    <div class="inv-meta-group">
      <label><i class="fa fa-tag" style="margin-right:4px;"></i>Type</label>
      <select name="invoice_type" id="invoice_type" class="form-control">
        <option value="invoice" selected>Invoice</option>
        <option value="quote">Quote</option>
        <option value="receipt">Receipt</option>
      </select>
    </div>
    <div class="inv-meta-group">
      <label><i class="fa fa-circle" style="margin-right:4px;font-size:9px;"></i>Status</label>
      <select name="invoice_status" id="invoice_status" class="form-control">
        <option value="open" selected>Open</option>
        <option value="paid">Paid</option>
        <option value="overdue">Overdue</option>
      </select>
    </div>
  </div>

  <!-- Row 2: Dates -->
  <div class="inv-meta-row">
    <div class="inv-meta-group">
      <label><i class="fa fa-calendar" style="margin-right:4px;"></i>Invoice Date</label>
      <input type="date" class="form-control required" name="invoice_date"
             data-date-format="<?php echo DATE_FORMAT ?>"
             value="<?php echo date('Y-m-d'); ?>" />
    </div>
    <div class="inv-meta-group">
      <label><i class="fa fa-calendar-check-o" style="margin-right:4px;"></i>Due Date</label>
      <input type="date" class="form-control required" name="invoice_due_date"
             data-date-format="<?php echo DATE_FORMAT ?>"
             value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" />
    </div>
  </div>

  <!-- Row 3: Invoice Number -->
  <div class="inv-meta-row">
    <div class="inv-meta-group">
      <label><i class="fa fa-hashtag" style="margin-right:4px;"></i>Invoice #</label>
      <div class="inv-invoice-num">
        <span>#<?php echo INVOICE_PREFIX ?></span>
        <input type="text" name="invoice_id" id="invoice_id" class="required" placeholder="Number" value="<?php getInvoiceId(); ?>">
      </div>
    </div>
  </div>

</div>

  <!-- Customer + Shipping -->
  <div class="inv-client-grid">

    <!-- Customer Info -->
    <div class="inv-card">
      <div class="inv-card-header">
        <h4><i class="fa fa-user-o"></i> Customer Information</h4>
        <a href="#" class="select-customer"><i class="fa fa-search"></i> Select Existing</a>
      </div>
      <div class="inv-card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 14px;">
          <input type="text" class="inv-form-control copy-input required" name="customer_name" id="customer_name" placeholder="Full Name" tabindex="1">
          <div class="inv-input-icon">
            <i class="fa fa-envelope"></i>
            <input type="email" class="inv-form-control copy-input required" name="customer_email" id="customer_email" placeholder="Email Address" tabindex="2">
          </div>
          <input type="text" class="inv-form-control copy-input required" name="customer_address_1" id="customer_address_1" placeholder="Address Line 1" tabindex="3">
          <input type="text" class="inv-form-control copy-input" name="customer_address_2" id="customer_address_2" placeholder="Address Line 2" tabindex="4">
          <input type="text" class="inv-form-control copy-input required" name="customer_town" id="customer_town" placeholder="Town / City" tabindex="5">
          <input type="text" class="inv-form-control copy-input required" name="customer_county" id="customer_county" placeholder="Country" tabindex="6">
          <input type="text" class="inv-form-control copy-input required" name="customer_postcode" id="customer_postcode" placeholder="Postcode" tabindex="7">
          <div class="inv-input-icon">
            <i class="fa fa-phone"></i>
            <input type="text" class="inv-form-control required" name="customer_phone" id="customer_phone" placeholder="Phone Number" tabindex="8">
          </div>
        </div>
      </div>
    </div>

    <!-- Shipping Info -->
    <div class="inv-card">
      <div class="inv-card-header">
        <h4><i class="fa fa-truck"></i> Shipping Information</h4>
      </div>
      <div class="inv-card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 14px;">
          <input type="text" class="inv-form-control required" name="customer_name_ship" id="customer_name_ship" placeholder="Full Name" tabindex="9">
          <input type="text" class="inv-form-control required" name="customer_address_1_ship" id="customer_address_1_ship" placeholder="Address Line 1" tabindex="10">
          <input type="text" class="inv-form-control" name="customer_address_2_ship" id="customer_address_2_ship" placeholder="Address Line 2" tabindex="11">
          <input type="text" class="inv-form-control required" name="customer_town_ship" id="customer_town_ship" placeholder="Town / City" tabindex="12">
          <input type="text" class="inv-form-control required" name="customer_county_ship" id="customer_county_ship" placeholder="Country" tabindex="13">
          <input type="text" class="inv-form-control required" name="customer_postcode_ship" id="customer_postcode_ship" placeholder="Postcode" tabindex="14">
        </div>
      </div>
    </div>

  </div>

  <!-- Items Table -->
  <div class="inv-table-wrap">
    <div class="inv-table-header">
      <h4><i class="fa fa-list" style="color:var(--accent);margin-right:6px;"></i> Line Items</h4>
      <a href="#" class="btn-add-row add-row"><i class="fa fa-plus"></i> Add Item</a>
    </div>
    <table class="table" id="invoice_table" style="margin:0;">
      <thead>
        <tr>
          <th width="38%">Product / Description</th>
          <th width="10%">Qty</th>
          <th width="18%">Unit Price</th>
          <th width="18%">Discount</th>
          <th width="16%">Sub Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="product-cell">
              <a href="#" class="btn-del-row delete-row" title="Remove"><i class="fa fa-times"></i></a>
              <div class="product-input-wrap">
                <input type="text" class="form-control item-input invoice_product" name="invoice_product[]" placeholder="Product name or description">
                <p class="item-select">or <a href="#">select a product</a></p>
              </div>
            </div>
          </td>
          <td>
            <input type="number" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="1" min="1">
          </td>
          <td>
            <div class="inp-with-prefix">
              <span><?php echo CURRENCY ?></span>
              <input type="number" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" placeholder="0.00" step="0.01">
            </div>
          </td>
          <td>
            <input type="text" class="form-control calculate" name="invoice_product_discount[]" placeholder="e.g. 10% or 5.00">
          </td>
          <td>
            <div class="inp-with-prefix">
              <span><?php echo CURRENCY ?></span>
              <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="0.00" disabled>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Notes + Totals -->
  <div class="inv-bottom-row">

    <!-- Notes -->
    <div class="inv-card" style="margin-bottom:0;">
      <div class="inv-card-header">
        <h4><i class="fa fa-sticky-note-o"></i> Additional Notes</h4>
      </div>
      <div class="inv-card-body inv-notes">
        <textarea name="invoice_notes" placeholder="Add any notes, payment terms, or special instructions..."></textarea>
      </div>
    </div>

    <!-- Totals -->
    <div class="inv-totals-card">
      <div class="tot-row">
        <strong>Sub Total</strong>
        <div class="tot-val">
          <?php echo CURRENCY ?><span class="invoice-sub-total">0.00</span>
          <input type="hidden" name="invoice_subtotal" id="invoice_subtotal">
        </div>
      </div>
      <div class="tot-row">
        <strong>Discount</strong>
        <div class="tot-val">
          <?php echo CURRENCY ?><span class="invoice-discount">0.00</span>
          <input type="hidden" name="invoice_discount" id="invoice_discount">
        </div>
      </div>
      <div class="tot-row">
        <strong class="shipping">Shipping</strong>
        <div class="tot-val">
          <div class="shipping-input-row">
            <span><?php echo CURRENCY ?></span>
            <input type="text" class="form-control calculate shipping" name="invoice_shipping" placeholder="0.00">
          </div>
        </div>
      </div>
      <?php if (ENABLE_VAT == true) { ?>
      <div class="tot-row">
        <div>
          <strong>TAX / VAT</strong>
          <div class="vat-remove-label">
            <input type="checkbox" class="remove_vat" id="remove_vat">
            <label for="remove_vat" style="cursor:pointer;margin:0;">Remove VAT</label>
          </div>
        </div>
        <div class="tot-val">
          <?php echo CURRENCY ?><span class="invoice-vat"
            data-enable-vat="<?php echo ENABLE_VAT ?>"
            data-vat-rate="<?php echo VAT_RATE ?>"
            data-vat-method="<?php echo VAT_INCLUDED ?>">0.00</span>
          <input type="hidden" name="invoice_vat" id="invoice_vat">
        </div>
      </div>
      <?php } ?>
      <div class="tot-row tot-total">
        <strong>Total</strong>
        <div class="tot-val">
          <?php echo CURRENCY ?><span class="invoice-total">0.00</span>
          <input type="hidden" name="invoice_total" id="invoice_total">
        </div>
      </div>
    </div>

  </div>

  <!-- Actions Bar -->
  <div class="inv-actions-bar">
    <div class="inv-email-wrap">
      <i class="fa fa-envelope"></i>
      <input type="email" name="custom_email" id="custom_email" class="custom_email_textarea"
             placeholder="Override email address (optional)">
    </div>
    <input type="submit" id="action_create_invoice" class="btn-create-invoice"
           value="Create Invoice" data-loading-text="Creating...">
  </div>

</form>

<!-- Product Modal -->
<div id="insert" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-cube" style="color:var(--accent);margin-right:6px;"></i>Select Product</h4>
      </div>
      <div class="modal-body"><?php popProductsList(); ?></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-primary" id="selected">Add Product</button>
        <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Customer Modal -->
<div id="insert_customer" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-users" style="color:var(--accent);margin-right:6px;"></i>Select Existing Customer</h4>
      </div>
      <div class="modal-body"><?php popCustomersList(); ?></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
// Update type badge label when select changes
document.getElementById('invoice_type').addEventListener('change', function() {
  var val = this.value;
  var label = val.charAt(0).toUpperCase() + val.slice(1);
  document.getElementById('type_badge_label').textContent = label;
  document.querySelector('.invoice_type').textContent = label;
});
</script>

<?php include('footer.php'); ?>