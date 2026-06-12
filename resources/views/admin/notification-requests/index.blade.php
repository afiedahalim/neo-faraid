@extends('layouts.admin')

@section('title', 'Notification Requests • Neo Faraid Admin')

@section('content')
<!-- Add Poppins font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #1a5fb4;
        --primary-dark: #0d2d5c;
        --primary-light: #e8f1fd;
        --secondary-color: #2d7ad6;
        --accent-color: #ffd700;
        --success-color: #25D366;
        --success-dark: #128C7E;
        --success-light: #d4edda;
        --danger-color: #dc3545;
        --danger-dark: #c82333;
        --danger-light: #f8d7da;
        --warning-color: #ffc107;
        --warning-dark: #e0a800;
        --warning-light: #fff3cd;
        --info-color: #17a2b8;
        --info-dark: #138496;
        --info-light: #d1ecf1;
        --dark: #1a1a2e;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
        --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
        --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
        --border-radius-sm: 12px;
        --border-radius-md: 15px;
        --border-radius-lg: 20px;
        --border-radius-xl: 50px;
        --transition: all 0.3s ease;
    }

    * {
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        color: var(--white);
        padding: 2rem 2.5rem;
        border-radius: var(--border-radius-lg);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
    }

    .page-header .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    /* ===== STAT CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        border-left: 4px solid;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card.total { border-left-color: var(--primary-color); }
    .stat-card.pending { border-left-color: var(--warning-color); }
    .stat-card.approved { border-left-color: var(--success-color); }
    .stat-card.rejected { border-left-color: var(--danger-color); }
    .stat-card.sent { border-left-color: var(--info-color); }

    .stat-card .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .stat-card.total .stat-icon { background: var(--primary-light); color: var(--primary-color); }
    .stat-card.pending .stat-icon { background: var(--warning-light); color: var(--warning-dark); }
    .stat-card.approved .stat-icon { background: var(--success-light); color: var(--success-dark); }
    .stat-card.rejected .stat-icon { background: var(--danger-light); color: var(--danger-dark); }
    .stat-card.sent .stat-icon { background: var(--info-light); color: var(--info-dark); }

    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-card.total .stat-value { color: var(--primary-color); }
    .stat-card.pending .stat-value { color: var(--warning-dark); }
    .stat-card.approved .stat-value { color: var(--success-color); }
    .stat-card.rejected .stat-value { color: var(--danger-color); }
    .stat-card.sent .stat-value { color: var(--info-color); }

    .stat-card .stat-label {
        font-size: 0.8125rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* ===== ACTION BAR ===== */
    .action-bar {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
        background: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        font-size: 0.9375rem;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
    }

    .filter-select {
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        font-size: 0.9375rem;
        background: var(--white);
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25rem;
    }

    /* ===== BUTTONS ===== */
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
    }

    .btn-sm {
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
        border-radius: var(--border-radius-sm);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: var(--white);
    }

    .btn-success {
        background: var(--success-color);
        color: var(--white);
    }

    .btn-success:hover {
        background: var(--success-dark);
        color: var(--white);
    }

    .btn-danger {
        background: var(--danger-color);
        color: var(--white);
    }

    .btn-danger:hover {
        background: var(--danger-dark);
        color: var(--white);
    }

    .btn-warning {
        background: var(--warning-color);
        color: var(--dark);
    }

    .btn-warning:hover {
        background: var(--warning-dark);
        color: var(--dark);
    }

    .btn-info {
        background: var(--info-color);
        color: var(--white);
    }

    .btn-info:hover {
        background: var(--info-dark);
        color: var(--white);
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--gray-300);
        color: var(--gray-700);
    }

    .btn-outline:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        color: var(--gray-800);
    }

    /* ===== TABLE ===== */
    .table-card {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: var(--gray-50);
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--gray-200);
        white-space: nowrap;
    }

    .data-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: var(--gray-50);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr.row-pending {
        background: var(--warning-light);
        border-left: 4px solid var(--warning-color);
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .status-pending { background: var(--warning-light); color: var(--warning-dark); }
    .status-approved { background: var(--success-light); color: var(--success-dark); }
    .status-rejected { background: var(--danger-light); color: var(--danger-dark); }
    .status-sent { background: var(--info-light); color: var(--info-dark); }

    /* ===== ACTION BUTTONS ===== */
    .action-btns {
        display: flex;
        gap: 0.375rem;
        flex-wrap: wrap;
    }

    .action-btns .btn-sm {
        padding: 0.375rem 0.625rem;
        font-size: 0.75rem;
    }

    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(5px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .modal-dialog {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        width: 90%;
        max-width: 550px;
        box-shadow: var(--shadow-xl);
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active .modal-dialog {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modal-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .modal-icon.success { background: var(--success-light); color: var(--success-dark); }
    .modal-icon.danger { background: var(--danger-light); color: var(--danger-dark); }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--gray-900);
    }

    .modal-body {
        padding: 1.5rem;
        color: var(--gray-700);
        line-height: 1.6;
    }

    .modal-body .form-group {
        margin-bottom: 1rem;
    }

    .modal-body label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--gray-700);
        margin-bottom: 0.375rem;
    }

    .modal-body textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem;
        resize: vertical;
        min-height: 80px;
    }

    .modal-body textarea:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* ===== MODERN ALERT ===== */
    .modern-alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 420px;
    }

    .modern-alert {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 320px;
        border-left: 4px solid;
    }

    .modern-alert.show {
        transform: translateX(0);
        opacity: 1;
    }

    .modern-alert.success { border-left-color: var(--success-color); }
    .modern-alert.error { border-left-color: var(--danger-color); }
    .modern-alert.warning { border-left-color: var(--warning-color); }
    .modern-alert.info { border-left-color: var(--info-color); }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray-300);
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state h3 {
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-500);
    }

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        padding: 1.25rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid var(--gray-200);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-bar {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }

        .action-btns {
            flex-direction: column;
        }

        .action-btns .btn-sm {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Modern Alert Container -->
<div class="modern-alert-container" id="alertContainer"></div>

<!-- Approve/Reject Modal -->
<div class="modal-overlay" id="actionModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="modal-icon success" id="actionModalIcon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="modal-title" id="actionModalTitle">Process Request</h3>
        </div>
        <form id="actionForm" method="POST">
            @csrf
            <input type="hidden" name="send_emails" id="sendEmailsInput" value="1">
            <div class="modal-body">
                <div id="actionModalMessage">
                    Are you sure you want to proceed with this action?
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="admin_notes">Admin Notes</label>
                    <textarea name="admin_notes" id="admin_notes" 
                              placeholder="Enter any notes or reason for this action..."
                              rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeActionModal()">Cancel</button>
                <button type="submit" class="btn btn-sm" id="actionModalConfirmBtn">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1><i class="fas fa-bell" style="margin-right: 0.75rem;"></i>Notification Requests</h1>
        <p>Manage and process inheritance notification requests from estate owners</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.estate-setup.index') }}" class="btn btn-outline" style="background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3); color: var(--white);">
            <i class="fas fa-folder"></i> View Estates
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-icon"><i class="fas fa-bell"></i></div>
        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
        <div class="stat-label">Total Requests</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card approved">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value">{{ $stats['approved'] ?? 0 }}</div>
        <div class="stat-label">Approved</div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        <div class="stat-value">{{ $stats['rejected'] ?? 0 }}</div>
        <div class="stat-label">Rejected</div>
    </div>
    <div class="stat-card sent">
        <div class="stat-icon"><i class="fas fa-paper-plane"></i></div>
        <div class="stat-value">{{ $stats['sent'] ?? 0 }}</div>
        <div class="stat-label">Sent</div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="modern-alert success show" style="position: relative; top: 0; right: 0; max-width: 100%; margin-bottom: 1rem;">
        <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.25rem;"></i>
        <div style="flex: 1;">
            <div style="font-weight: 700; color: var(--gray-900);">Success</div>
            <div style="font-size: 0.875rem; color: var(--gray-600);">{{ session('success') }}</div>
        </div>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: var(--gray-500);">✕</button>
    </div>
@endif

@if(session('error'))
    <div class="modern-alert error show" style="position: relative; top: 0; right: 0; max-width: 100%; margin-bottom: 1rem;">
        <i class="fas fa-times-circle" style="color: var(--danger-color); font-size: 1.25rem;"></i>
        <div style="flex: 1;">
            <div style="font-weight: 700; color: var(--gray-900);">Error</div>
            <div style="font-size: 0.875rem; color: var(--gray-600);">{{ session('error') }}</div>
        </div>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: var(--gray-500);">✕</button>
    </div>
@endif

@if(session('warning'))
    <div class="modern-alert warning show" style="position: relative; top: 0; right: 0; max-width: 100%; margin-bottom: 1rem;">
        <i class="fas fa-exclamation-triangle" style="color: var(--warning-color); font-size: 1.25rem;"></i>
        <div style="flex: 1;">
            <div style="font-weight: 700; color: var(--gray-900);">Warning</div>
            <div style="font-size: 0.875rem; color: var(--gray-600);">{{ session('warning') }}</div>
        </div>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: var(--gray-500);">✕</button>
    </div>
@endif

<!-- Action Bar -->
<div class="action-bar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Search by estate name, requester, or email..." 
               value="{{ request('search', '') }}">
    </div>
    <select class="filter-select" id="statusFilter" onchange="filterByStatus(this.value)">
        <option value="">All Statuses</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
    </select>
    <button class="btn btn-outline" onclick="clearFilters()">
        <i class="fas fa-sync"></i> Clear Filters
    </button>
</div>

<!-- Notification Requests Table -->
<div class="table-card">
    @if(isset($requests) && $requests->count() > 0)
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estate</th>
                        <th>Requested By</th>
                        <th>Recipient</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Processed At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        @php
                            $estate = $request->estatePreRegistration;
                            $requester = $request->requestedBy;
                            $rowClass = $request->status === 'pending' ? 'row-pending' : '';
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>
                                <strong>#{{ $request->id }}</strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--gray-800);">
                                    {{ $estate->deceased_name ?? 'N/A' }}
                                </div>
                                @if($estate)
                                    <small style="color: var(--gray-500);">
                                        <a href="{{ route('admin.estate-setup.show', $estate->unique_id) }}" 
                                           style="color: var(--primary-color); text-decoration: none;">
                                            View Estate <i class="fas fa-external-link-alt" style="font-size: 0.625rem;"></i>
                                        </a>
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 500;">{{ $requester->name ?? 'N/A' }}</div>
                                <small style="color: var(--gray-500);">{{ $requester->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div>{{ $request->recipient_name ?? 'N/A' }}</div>
                                <small style="color: var(--gray-500);">{{ $request->recipient_email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $request->status }}">
                                    @switch($request->status)
                                        @case('pending')
                                            <i class="fas fa-clock"></i> Pending
                                            @break
                                        @case('approved')
                                            <i class="fas fa-check-circle"></i> Approved
                                            @break
                                        @case('rejected')
                                            <i class="fas fa-times-circle"></i> Rejected
                                            @break
                                        @case('sent')
                                            <i class="fas fa-paper-plane"></i> Sent
                                            @break
                                        @default
                                            {{ ucfirst($request->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.8125rem;">
                                    {{ $request->created_at ? $request->created_at->format('d M Y') : 'N/A' }}
                                </span>
                                <br>
                                <small style="color: var(--gray-500);">
                                    {{ $request->created_at ? $request->created_at->format('H:i') : '' }}
                                </small>
                            </td>
                            <td>
                                @if($request->approved_at)
                                    <span style="font-size: 0.8125rem;">
                                        {{ $request->approved_at->format('d M Y') }}
                                    </span>
                                    <br>
                                    <small style="color: var(--gray-500);">
                                        {{ $request->approved_at->format('H:i') }}
                                    </small>
                                @elseif($request->rejected_at)
                                    <span style="font-size: 0.8125rem; color: var(--danger-color);">
                                        Rejected
                                    </span>
                                @else
                                    <span style="color: var(--gray-400);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    @if($request->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="openApproveModal('{{ $request->id }}', '{{ addslashes($estate->deceased_name ?? 'N/A') }}')"
                                                title="Approve & Send Emails">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="openApproveModalNoEmail('{{ $request->id }}', '{{ addslashes($estate->deceased_name ?? 'N/A') }}')"
                                                title="Approve Without Sending Emails">
                                            <i class="fas fa-check-double"></i> Approve (No Email)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="openRejectModal('{{ $request->id }}', '{{ addslashes($estate->deceased_name ?? 'N/A') }}')"
                                                title="Reject Request">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @elseif($request->status === 'approved')
                                        <form action="{{ route('admin.estate-setup.notifications.resend', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info" title="Resend Notification Emails">
                                                <i class="fas fa-paper-plane"></i> Resend
                                            </button>
                                        </form>
                                    @else
                                        <span style="color: var(--gray-400); font-size: 0.75rem;">No actions</span>
                                    @endif
                                    
                                    <a href="{{ route('admin.estate-setup.show', $estate->unique_id ?? '') }}" 
                                       class="btn btn-sm btn-outline" title="View Estate Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
            <div class="pagination-wrapper">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <h3>No Notification Requests</h3>
            <p>
                @if(request('search') || request('status'))
                    No notification requests match your current filters. Try clearing the filters.
                @else
                    No inheritance notification requests have been submitted yet.
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Info Panel -->
@if(isset($requests) && $requests->where('status', 'pending')->count() > 0)
    <div style="margin-top: 1.5rem; padding: 1rem 1.25rem; background: var(--warning-light); border-radius: var(--border-radius-md); border-left: 4px solid var(--warning-color);">
        <strong style="color: var(--warning-dark);">
            <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
            Pending Requests: {{ $requests->where('status', 'pending')->count() }}
        </strong>
        <p style="color: #856404; font-size: 0.8125rem; margin-top: 0.25rem;">
            There are pending notification requests that require your attention. 
            Approving a request will send inheritance distribution emails to all beneficiaries with email addresses.
        </p>
    </div>
@endif

<script>
    (function() {
        'use strict';

        // ===== SEARCH FUNCTIONALITY =====
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (this.value.trim()) {
                        url.searchParams.set('search', this.value.trim());
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }, 500);
            });

            if (searchInput.value) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }
        }

        // ===== FILTER BY STATUS =====
        window.filterByStatus = function(status) {
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        // ===== CLEAR FILTERS =====
        window.clearFilters = function() {
            window.location.href = "{{ route('admin.estate-setup.notifications.index') }}";
        };

        // ===== APPROVE MODAL (WITH EMAIL) =====
        window.openApproveModal = function(requestId, estateName) {
            const modal = document.getElementById('actionModal');
            const icon = document.getElementById('actionModalIcon');
            const title = document.getElementById('actionModalTitle');
            const message = document.getElementById('actionModalMessage');
            const form = document.getElementById('actionForm');
            const confirmBtn = document.getElementById('actionModalConfirmBtn');
            const sendEmailsInput = document.getElementById('sendEmailsInput');

            icon.className = 'modal-icon success';
            icon.innerHTML = '<i class="fas fa-check-circle"></i>';
            title.textContent = 'Approve & Send Emails';
            message.innerHTML = `
                <p>You are about to <strong>approve</strong> the notification request for <strong>"${estateName}"</strong>.</p>
                <p style="color: var(--success-color); margin-top: 0.5rem;">
                    <i class="fas fa-envelope"></i> 
                    Inheritance distribution emails <strong>will be sent</strong> to all beneficiaries with email addresses.
                </p>
            `;
            confirmBtn.className = 'btn btn-sm btn-success';
            confirmBtn.innerHTML = '<i class="fas fa-check"></i> Approve & Send';
            sendEmailsInput.value = '1';

            form.action = "{{ route('admin.estate-setup.notifications.approve', '') }}/" + requestId;
            
            document.getElementById('admin_notes').value = '';
            modal.classList.add('active');
        };

        // ===== APPROVE MODAL (WITHOUT EMAIL) =====
        window.openApproveModalNoEmail = function(requestId, estateName) {
            const modal = document.getElementById('actionModal');
            const icon = document.getElementById('actionModalIcon');
            const title = document.getElementById('actionModalTitle');
            const message = document.getElementById('actionModalMessage');
            const form = document.getElementById('actionForm');
            const confirmBtn = document.getElementById('actionModalConfirmBtn');
            const sendEmailsInput = document.getElementById('sendEmailsInput');

            icon.className = 'modal-icon success';
            icon.innerHTML = '<i class="fas fa-check-circle"></i>';
            title.textContent = 'Approve Without Sending Emails';
            message.innerHTML = `
                <p>You are about to <strong>approve</strong> the notification request for <strong>"${estateName}"</strong>.</p>
                <p style="color: var(--warning-dark); margin-top: 0.5rem;">
                    <i class="fas fa-envelope"></i> 
                    Inheritance distribution emails will <strong>NOT be sent</strong> at this time. 
                    You can send them later by clicking "Resend".
                </p>
            `;
            confirmBtn.className = 'btn btn-sm btn-warning';
            confirmBtn.innerHTML = '<i class="fas fa-check"></i> Approve Only';
            sendEmailsInput.value = '0';

            form.action = "{{ route('admin.estate-setup.notifications.approve', '') }}/" + requestId;
            
            document.getElementById('admin_notes').value = '';
            modal.classList.add('active');
        };

        // ===== REJECT MODAL =====
        window.openRejectModal = function(requestId, estateName) {
            const modal = document.getElementById('actionModal');
            const icon = document.getElementById('actionModalIcon');
            const title = document.getElementById('actionModalTitle');
            const message = document.getElementById('actionModalMessage');
            const form = document.getElementById('actionForm');
            const confirmBtn = document.getElementById('actionModalConfirmBtn');
            const sendEmailsInput = document.getElementById('sendEmailsInput');

            icon.className = 'modal-icon danger';
            icon.innerHTML = '<i class="fas fa-times-circle"></i>';
            title.textContent = 'Reject Request';
            message.innerHTML = `
                <p>You are about to <strong>reject</strong> the notification request for <strong>"${estateName}"</strong>.</p>
                <p style="color: var(--danger-color); margin-top: 0.5rem;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    This will prevent inheritance distribution emails from being sent. 
                    Please provide a reason for rejection.
                </p>
            `;
            confirmBtn.className = 'btn btn-sm btn-danger';
            confirmBtn.innerHTML = '<i class="fas fa-times"></i> Reject';
            sendEmailsInput.value = '0';

            form.action = "{{ route('admin.estate-setup.notifications.reject', '') }}/" + requestId;
            
            document.getElementById('admin_notes').value = '';
            modal.classList.add('active');
        };

        // ===== CLOSE MODAL =====
        window.closeActionModal = function() {
            document.getElementById('actionModal').classList.remove('active');
        };

        // Close modal on outside click
        document.getElementById('actionModal').addEventListener('click', function(e) {
            if (e.target === this) closeActionModal();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeActionModal();
            }
        });

        // ===== MODERN ALERT SYSTEM =====
        function showAlert(type, title, message) {
            const container = document.getElementById('alertContainer');
            if (!container) return;

            const alertId = 'alert-' + Date.now();
            const icons = {
                success: '<i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.25rem;"></i>',
                error: '<i class="fas fa-times-circle" style="color: var(--danger-color); font-size: 1.25rem;"></i>',
                warning: '<i class="fas fa-exclamation-triangle" style="color: var(--warning-color); font-size: 1.25rem;"></i>',
                info: '<i class="fas fa-info-circle" style="color: var(--info-color); font-size: 1.25rem;"></i>'
            };

            const alertEl = document.createElement('div');
            alertEl.className = `modern-alert ${type}`;
            alertEl.id = alertId;
            alertEl.innerHTML = `
                ${icons[type] || icons.info}
                <div style="flex: 1;">
                    <div style="font-weight: 700; color: var(--gray-900);">${title}</div>
                    <div style="font-size: 0.875rem; color: var(--gray-600);">${message}</div>
                </div>
                <button onclick="document.getElementById('${alertId}').classList.remove('show'); setTimeout(() => document.getElementById('${alertId}')?.remove(), 500);" 
                        style="background: none; border: none; cursor: pointer; color: var(--gray-500);">✕</button>
            `;

            container.appendChild(alertEl);
            setTimeout(() => alertEl.classList.add('show'), 10);
            setTimeout(() => {
                alertEl.classList.remove('show');
                setTimeout(() => alertEl.remove(), 500);
            }, 5000);
        }

        // ===== AUTO-DISMISS STATIC ALERTS =====
        setTimeout(() => {
            document.querySelectorAll('.modern-alert.show').forEach(alert => {
                if (alert.parentElement !== document.getElementById('alertContainer')) {
                    setTimeout(() => {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 500);
                    }, 6000);
                }
            });
        }, 500);

        // ===== KEYBOARD SHORTCUT =====
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (searchInput) searchInput.focus();
            }
        });

    })();
</script>
@endsection