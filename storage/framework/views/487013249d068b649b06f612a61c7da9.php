<?php
// resources/views/admin/calculations/show.blade.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <title>Calculation Details • Neo Faraid Admin</title>
  <meta name="description" content="View and manage inheritance calculation details in Neo Faraid Admin Panel">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
      --primary-color: #1a5fb4;
      --primary-dark: #0d2d5c;
      --secondary-color: #2d7ad6;
      --accent-color: #ffd700;
      --accent-light: #ffed4e;
      --danger-color: #dc3545;
      --danger-dark: #c82333;
      --success-color: #25D366;
      --success-dark: #128C7E;
      --warning-color: #ffc107;
      --info-color: #138496;
      --light-bg: #f8f9fa;
      --light-border: #e9ecef;
      --text-primary: #495057;
      --text-light: #6c757d;
      --white: #ffffff;
      --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
      --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
      --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
      --shadow-accent: 0 10px 25px rgba(255, 215, 0, 0.3);
      --border-radius-sm: 12px;
      --border-radius-md: 15px;
      --border-radius-lg: 20px;
      --border-radius-xl: 50px;
      --transition: all 0.3s ease;
      --font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    * {
      font-family: var(--font-family);
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: var(--light-bg);
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    /* Top Navigation */
    .admin-top-nav {
      background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
      color: var(--white);
      padding: 0 2rem;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow-md);
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      transition: var(--transition);
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .admin-logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      color: var(--white);
      font-weight: 700;
      font-size: 1.4rem;
      transition: var(--transition);
    }

    .admin-logo:hover {
      transform: translateY(-1px);
      opacity: 0.9;
    }

    .admin-logo-icon {
      width: 32px;
      height: 32px;
      color: var(--accent-color);
      fill: currentColor;
      filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3));
      animation: logoFloat 4s ease-in-out infinite;
    }

    @keyframes logoFloat {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-2px); }
    }

    .admin-logo-text {
      background: linear-gradient(135deg, #ffffff 0%, var(--accent-color) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
    }

    .nav-right {
      display: flex;
      align-items: center;
    }

    .user-profile-container {
      position: relative;
      height: 100%;
      display: flex;
      align-items: center;
    }

    .user-profile-btn {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.5rem 1rem;
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 100px;
      color: var(--white);
      cursor: pointer;
      transition: var(--transition);
      font-family: inherit;
      text-decoration: none;
    }

    .user-profile-btn:hover {
      background: rgba(255,255,255,0.25);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(255,255,255,0.15);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      flex-shrink: 0;
      box-shadow: var(--shadow-accent);
    }

    .avatar-initials {
      font-weight: 700;
      font-size: 1rem;
      color: var(--primary-dark);
    }

    .admin-badge {
      position: absolute;
      bottom: -2px;
      right: -2px;
      width: 16px;
      height: 16px;
      background: var(--danger-color);
      color: white;
      border-radius: 50%;
      font-size: 0.6rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid var(--primary-color);
    }

    .user-profile-info {
      flex: 1;
      min-width: 0;
      overflow: hidden;
    }

    .user-name {
      display: block;
      font-weight: 600;
      font-size: 0.9rem;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .user-role {
      display: block;
      font-size: 0.75rem;
      opacity: 0.9;
      font-weight: 500;
    }

    .chevron-icon {
      width: 16px;
      height: 16px;
      transition: transform 0.3s ease;
      flex-shrink: 0;
    }

    /* Dropdown Menu */
    .user-profile-dropdown {
      display: none;
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      background: var(--white);
      border-radius: var(--border-radius-lg);
      box-shadow: var(--shadow-lg);
      min-width: 320px;
      max-width: 400px;
      z-index: 1001;
      overflow: hidden;
      opacity: 0;
      transform: translateY(-10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
      border: 1px solid var(--light-border);
    }

    .user-profile-dropdown.active {
      opacity: 1;
      transform: translateY(0);
    }

    .profile-header {
      padding: 1.5rem;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: white;
    }

    .profile-avatar-large {
      width: 64px;
      height: 64px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      margin-bottom: 1rem;
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .avatar-initials-large {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--primary-color);
    }

    .admin-badge-large {
      position: absolute;
      bottom: -5px;
      right: -5px;
      width: 28px;
      height: 28px;
      background: var(--danger-color);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 3px solid white;
    }

    .admin-badge-large svg {
      width: 14px;
      height: 14px;
    }

    .profile-info {
      text-align: left;
    }

    .profile-name {
      font-size: 1.25rem;
      font-weight: 700;
      margin: 0 0 0.25rem 0;
      letter-spacing: -0.01em;
    }

    .profile-email {
      font-size: 0.85rem;
      opacity: 0.9;
      margin: 0 0 0.75rem 0;
      font-weight: 400;
    }

    .profile-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.8rem;
    }

    .last-login {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      opacity: 0.9;
    }

    .last-login svg {
      width: 14px;
      height: 14px;
    }

    .user-status {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
      background: rgba(255,255,255,0.2);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .user-status.active {
      color: var(--accent-color);
    }

    .user-status.active::before {
      content: '●';
      margin-right: 0.25rem;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }

    .dropdown-section {
      padding: 1rem 0;
      border-bottom: 1px solid var(--light-border);
    }

    .dropdown-section:last-of-type {
      border-bottom: none;
    }

    .section-title {
      font-size: 0.75rem;
      color: #94a3b8;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0 1.5rem 0.5rem 1.5rem;
      margin: 0;
    }

    .dropdown-nav {
      padding: 0 1.5rem;
    }

    .dropdown-nav-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 0.875rem 0;
      color: var(--text-primary);
      text-decoration: none;
      transition: var(--transition);
      border-bottom: 1px solid var(--light-border);
      position: relative;
    }

    .dropdown-nav-item:last-child {
      border-bottom: none;
    }

    .dropdown-nav-item:hover {
      color: var(--primary-color);
      transform: translateX(4px);
    }

    .dropdown-nav-item.active {
      color: var(--primary-color);
      font-weight: 600;
    }

    .dropdown-nav-item.active::before {
      content: '';
      position: absolute;
      left: -1.5rem;
      top: 0;
      bottom: 0;
      width: 3px;
      background: var(--primary-color);
    }

    .dropdown-nav-item svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
    }

    .nav-badge {
      margin-left: auto;
      background: var(--light-bg);
      color: var(--text-light);
      font-size: 0.75rem;
      font-weight: 600;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      min-width: 24px;
      text-align: center;
    }

    .account-links {
      padding: 0 1.5rem;
    }

    .account-link {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 0.875rem 0;
      color: var(--text-primary);
      text-decoration: none;
      transition: var(--transition);
    }

    .account-link:hover {
      color: var(--primary-color);
      transform: translateX(4px);
    }

    .account-link.active {
      color: var(--primary-color);
      font-weight: 600;
    }

    .account-link svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
    }

    .dropdown-footer {
      padding: 1.25rem 1.5rem;
      background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
      border-top: 1px solid var(--light-border);
    }

    .logout-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      width: 100%;
      padding: 0.875rem;
      background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
      border: none;
      color: var(--white);
      font-weight: 600;
      border-radius: var(--border-radius-md);
      cursor: pointer;
      transition: var(--transition);
      font-family: inherit;
    }

    .logout-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.25);
    }

    /* Main Content */
    .admin-main {
      padding: 2.5rem;
      background: var(--light-bg);
      overflow-y: auto;
      max-width: 1400px;
      margin: 0 auto;
      animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: var(--primary-color);
      margin-bottom: 2rem;
      padding-bottom: 1.5rem;
      border-bottom: 2px solid var(--light-border);
    }

    .page-title h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      color: var(--text-light);
      font-weight: 400;
      font-size: 0.95rem;
    }

    /* Stats Cards */
    .stats-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stats-card {
      background: var(--white);
      padding: 1.5rem;
      border-radius: var(--border-radius-md);
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
      border-left: 4px solid var(--primary-color);
    }

    .stats-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .stats-card-title {
      font-size: 0.9rem;
      color: var(--text-light);
      margin-bottom: 0.5rem;
      font-weight: 500;
    }

    .stats-card-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }

    .stats-card-change {
      font-size: 0.85rem;
      color: var(--text-light);
    }

    /* Buttons */
    .btn {
      padding: 0.75rem 1.5rem;
      border-radius: var(--border-radius-md);
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: var(--transition);
      border: none;
      font-size: 0.95rem;
      box-shadow: var(--shadow-sm);
      font-family: inherit;
    }

    .btn-outline {
      background: transparent;
      border: 2px solid var(--light-border);
      color: var(--text-primary);
    }

    .btn-outline:hover {
      background: var(--light-bg);
      border-color: var(--text-light);
    }

    .btn-danger {
      background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
      color: var(--white);
    }

    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
    }

    /* Content Blocks */
    .content-block {
      margin-bottom: 2rem;
    }

    .block-title {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }

    .block-title svg {
      width: 22px;
      height: 22px;
      color: var(--secondary-color);
    }

    /* Tables */
    .table-container {
      overflow-x: auto;
      border-radius: var(--border-radius-md);
      background: var(--white);
      box-shadow: var(--shadow-sm);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    }

    th {
      padding: 1.25rem;
      text-align: left;
      font-weight: 600;
      color: var(--white);
      font-size: 0.95rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      white-space: nowrap;
    }

    th:first-child {
      border-top-left-radius: var(--border-radius-md);
    }

    th:last-child {
      border-top-right-radius: var(--border-radius-md);
    }

    td {
      padding: 1.25rem;
      border-bottom: 1px solid var(--light-border);
      vertical-align: middle;
      color: var(--text-primary);
      font-weight: 400;
    }

    tbody tr {
      transition: var(--transition);
    }

    tbody tr:hover {
      background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(45, 122, 214, 0.05) 100%);
      transform: translateX(5px);
    }

    /* Total Row */
    .total-row {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
    }

    .total-row td {
      color: white !important;
      font-weight: 700 !important;
      font-size: 1rem;
    }

    /* Badges */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      padding: 0.375rem 0.875rem;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .badge-success {
      background: #d4edda;
      color: #155724;
    }

    .badge-warning {
      background: #fff3cd;
      color: #856404;
    }

    .badge-info {
      background: #d1ecf1;
      color: #0c5460;
    }

    .badge-primary {
      background: #e8f1fd;
      color: #1a5fb4;
    }

    /* ---------- MODERN ALERT / PROMPT MESSAGES ---------- */
    .alert-message {
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
      padding: 1rem 1.5rem;
      border-radius: var(--border-radius-sm);
      margin-bottom: 1.5rem;
      font-weight: 500;
      background: white;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
      animation: slideInAlert 0.35s ease;
      border: 1px solid transparent;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .alert-message:hover {
      transform: translateY(-1px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }

    @keyframes slideInAlert {
      from { opacity: 0; transform: translateY(-12px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
      background: #f0faf3;
      border-left: 5px solid #28a745;
      color: #145c2d;
    }

    .alert-error {
      background: #fef2f3;
      border-left: 5px solid #dc3545;
      color: #6d1a21;
    }

    .alert-warning {
      background: #fff9ed;
      border-left: 5px solid #ffc107;
      color: #6a5100;
    }

    .alert-message svg {
      flex-shrink: 0;
      margin-top: 0.15rem;
    }

    .alert-message strong {
      font-weight: 600;
    }

    /* ---------- MODERN DELETE CONFIRMATION MODAL ---------- */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.65);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      padding: 1rem;
      animation: fadeInModal 0.25s ease;
    }

    @keyframes fadeInModal {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal-content {
      background: white;
      border-radius: 24px;
      box-shadow: 0 30px 60px rgba(0,0,0,0.15), 0 8px 24px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 480px;
      overflow: hidden;
      animation: slideUpModal 0.35s ease;
    }

    @keyframes slideUpModal {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 2rem;
      background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
      border-bottom: 1px solid #edf2f7;
      color: var(--text-primary);
    }

    .modal-title {
      font-size: 1.3rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: #2d3748;
    }

    .modal-title svg {
      color: #e53e3e;
    }

    .modal-close {
      background: rgba(0,0,0,0.06);
      border: none;
      color: #718096;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }

    .modal-close:hover {
      background: rgba(0,0,0,0.1);
      color: #2d3748;
      transform: rotate(90deg);
    }

    .modal-body {
      padding: 1.75rem 2rem;
      font-size: 1rem;
      color: #4a5568;
      line-height: 1.6;
    }

    .modal-body .highlight-box {
      background: #f7fafc;
      border-radius: 14px;
      padding: 1rem 1.25rem;
      margin-top: 1.25rem;
      border-left: 4px solid #1a5fb4;
      font-size: 0.95rem;
      color: #2d3748;
    }

    .highlight-box strong {
      display: block;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      color: #1a5fb4;
      margin-bottom: 0.35rem;
      font-weight: 600;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      padding: 1.25rem 2rem;
      background: #f8fafc;
      border-top: 1px solid #edf2f7;
    }

    .btn-cancel {
      background: white;
      border: 1px solid #e2e8f0;
      color: #4a5568;
      padding: 0.7rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      transition: 0.2s;
      cursor: pointer;
    }

    .btn-cancel:hover {
      background: #edf2f7;
      border-color: #cbd5e0;
    }

    .btn-confirm-danger {
      background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
      color: white;
      border: none;
      padding: 0.7rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      box-shadow: 0 6px 18px rgba(229, 62, 62, 0.25);
      transition: all 0.2s;
      cursor: pointer;
    }

    .btn-confirm-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 24px rgba(229, 62, 62, 0.35);
    }

    /* Summary Box */
    .summary-box {
      margin-top: 1.5rem;
      padding: 1.5rem;
      background: var(--white);
      border-radius: var(--border-radius-md);
      border-left: 4px solid var(--primary-color);
      box-shadow: var(--shadow-sm);
    }

    .summary-box h3 {
      color: var(--primary-color);
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
    }

    .summary-box ul {
      list-style: none;
      padding: 0;
    }

    .summary-box ul li {
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--light-border);
      color: var(--text-primary);
      font-weight: 400;
    }

    .summary-box ul li:last-child {
      border-bottom: none;
    }

    /* Info Grid */
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
      gap: 1rem;
    }

    .info-item {
      background: var(--white);
      padding: 1.25rem;
      border-radius: var(--border-radius-md);
      border-left: 4px solid var(--primary-color);
      transition: var(--transition);
      box-shadow: var(--shadow-sm);
    }

    .info-item:hover {
      transform: translateX(4px);
      box-shadow: var(--shadow-md);
    }

    .info-label {
      font-size: 0.875rem;
      color: var(--text-light);
      font-weight: 500;
      margin-bottom: 0.375rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-value {
      font-size: 0.96rem;
      color: var(--text-primary);
      font-weight: 550;
    }

    /* No Data State */
    .no-data {
      text-align: center;
      padding: 3rem 2rem;
      color: var(--text-light);
      background: var(--white);
      border-radius: var(--border-radius-md);
      box-shadow: var(--shadow-sm);
    }

    .no-data svg {
      width: 64px;
      height: 64px;
      margin-bottom: 1rem;
      color: var(--light-border);
    }

    .no-data h3 {
      margin-bottom: 0.5rem;
      color: var(--text-primary);
      font-weight: 600;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: var(--light-bg);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--text-light);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-color);
    }

    /* Accessibility */
    .visually-hidden {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    button:focus,
    a:focus,
    input:focus,
    select:focus,
    textarea:focus {
      outline: 2px solid var(--accent-color);
      outline-offset: 2px;
    }

    /* Responsive Design */
    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
      .admin-logo-icon,
      .user-avatar,
      .loading-spinner {
        animation: none;
      }
      .user-status.active::before {
        animation: none;
      }
    }

    @media (max-width: 1200px) {
      .admin-main { padding: 2rem; }
    }

    @media (max-width: 992px) {
      .admin-top-nav { padding: 0 1rem; }
      .admin-logo-text { font-size: 1.2rem; }
      .user-profile-btn { min-width: auto; padding: 0.5rem; }
      .user-profile-info { display: none; }
      .stats-cards { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
      .admin-top-nav { height: 60px; }
      .nav-left { gap: 1rem; }
      .admin-logo-text { display: none; }
      .admin-main { padding: 1.5rem; }
      .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
      .stats-cards { grid-template-columns: 1fr; }
      .table-container { border-radius: var(--border-radius-md); overflow-x: auto; }
      table { min-width: 700px; }
      .info-grid { grid-template-columns: 1fr; }
      .modal-footer { flex-direction: column; }
      .modal-footer button { width: 100%; }
      .user-profile-dropdown {
        position: fixed; top: 60px; left: 0; right: 0;
        width: 100%; max-width: 100%; border-radius: 0;
        border-left: none; border-right: none;
      }
    }

    @media (max-width: 576px) {
      .admin-main { padding: 1rem; }
      .btn { padding: 0.6rem 1rem; font-size: 0.875rem; }
      th, td { padding: 0.75rem; }
      .stats-card-value { font-size: 1.5rem; }
      .modal-content { margin: 1rem; }
    }

    @media (max-width: 480px) {
      .admin-logo-icon { width: 28px; height: 28px; }
      .user-avatar { width: 32px; height: 32px; }
      .avatar-initials { font-size: 0.9rem; }
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="admin-top-nav" role="navigation" aria-label="Admin navigation">
    <div class="nav-left">
      <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-logo" aria-label="Neo Faraid Admin Home">
        <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
        <span class="admin-logo-text">Neo Faraid Admin</span>
      </a>
    </div>
    <div class="nav-right">
      <?php
        $user = auth()->user();
        $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
        $fullInitials = strtoupper(implode('', array_map(function($name) { return substr($name, 0, 1); }, explode(' ', $user->name, 2))));
      ?>
      <div class="user-profile-container" id="user-profile-container">
        <button class="user-profile-btn" id="user-profile-btn" aria-label="User profile menu" aria-expanded="false" aria-haspopup="true" data-user-role="<?php echo e($user->role); ?>" data-user-initials="<?php echo e($fullInitials); ?>">
          <div class="user-avatar" aria-hidden="true">
            <span class="avatar-initials"><?php echo e($fullInitials); ?></span>
            <?php if($user->role === 'admin'): ?>
              <span class="admin-badge" aria-label="Administrator">A</span>
            <?php endif; ?>
          </div>
          <div class="user-profile-info">
            <span class="user-name"><?php echo e($user->name); ?></span>
            <span class="user-role"><?php echo e(ucfirst($user->role)); ?></span>
          </div>
          <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="user-profile-dropdown" id="user-profile-dropdown" role="menu" aria-label="User profile menu" aria-hidden="true">
          <div class="profile-header">
            <div class="profile-avatar-large" aria-hidden="true">
              <span class="avatar-initials-large"><?php echo e($fullInitials); ?></span>
              <?php if($user->role === 'admin'): ?>
                <div class="admin-badge-large" aria-label="Administrator">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                </div>
              <?php endif; ?>
            </div>
            <div class="profile-info">
              <h3 class="profile-name"><?php echo e($user->name); ?></h3>
              <p class="profile-email"><?php echo e($user->email); ?></p>
              <div class="profile-meta">
                <span class="last-login">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Last login: <?php echo e($lastLogin); ?>

                </span>
                <span class="user-status active">● Online</span>
              </div>
            </div>
          </div>
          <div class="dropdown-section">
            <h4 class="section-title">Navigation</h4>
            <nav class="dropdown-nav" role="navigation" aria-label="Admin navigation">
              <a href="<?php echo e(route('admin.users.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/users*') ? 'active' : ''); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                <span>Manage Users</span>
              </a>
              <a href="<?php echo e(route('admin.feedback.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/feedback*') ? 'active' : ''); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <span>Manage Feedback</span>
              </a>
              <a href="<?php echo e(route('admin.faq.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/faq*') ? 'active' : ''); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Manage FAQ</span>
              </a>
              <a href="<?php echo e(route('admin.calculations.index')); ?>" class="dropdown-nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Manage Calculations</span>
              </a>
              <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/instant-estate*') ? 'active' : ''); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Manage Instant Estate</span>
              </a>
              <a href="<?php echo e(route('admin.estate-setup.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/estate-setup*') ? 'active' : ''); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Manage Estate Planning</span>
              </a>
            </nav>
          </div>
          <div class="dropdown-section">
            <h4 class="section-title">Account</h4>
            <div class="account-links">
              <a href="<?php echo e(route('profile.edit')); ?>" class="account-link <?php echo e(request()->is('profile*') ? 'active' : ''); ?>" role="menuitem">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Profile Settings</span>
              </a>
            </div>
          </div>
          <div class="dropdown-footer">
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
              <?php echo csrf_field(); ?>
              <button type="submit" class="logout-btn" role="menuitem" aria-label="Log out from admin panel">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="admin-main">
    <header class="page-header">
      <div class="page-title">
        <h1>Calculation Details</h1>
        <p class="page-subtitle">Deceased: <?php echo e($calculation->deceased_name); ?> | ID: <?php echo e($calculation->id); ?></p>
      </div>
      <a href="<?php echo e(route('admin.calculations.index')); ?>" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
        </svg>
        Back to Calculations
      </a>
    </header>

    <?php if(session('success')): ?>
      <div class="alert-message alert-success" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <?php echo e(session('success')); ?>

      </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
      <div class="alert-message alert-error" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <?php echo e(session('error')); ?>

      </div>
    <?php endif; ?>

    <?php if(!$readiness['ready']): ?>
      <div class="alert-message alert-warning" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div>
          <strong>Calculation Incomplete:</strong>
          <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
            <?php $__currentLoopData = $readiness['issues']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($issue); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="stats-cards">
      <div class="stats-card">
        <div class="stats-card-title">Total Assets</div>
        <div class="stats-card-value">RM <?php echo e(number_format($calculation->total_assets, 2)); ?></div>
        <div class="stats-card-change">Registered assets value</div>
      </div>
      <div class="stats-card">
        <div class="stats-card-title">Total Heirs</div>
        <div class="stats-card-value"><?php echo e($calculation->total_heirs ?? 0); ?></div>
        <div class="stats-card-change">Configured heirs</div>
      </div>
      <div class="stats-card">
        <div class="stats-card-title">Eligible Recipients</div>
        <div class="stats-card-value"><?php echo e(count($eligibleHeirs)); ?></div>
        <div class="stats-card-change">Inheritance recipients</div>
      </div>
      <div class="stats-card">
        <div class="stats-card-title">Net Estate</div>
        <div class="stats-card-value">RM <?php echo e(number_format($netEstate, 2)); ?></div>
        <div class="stats-card-change">After deductions</div>
      </div>
    </div>

    <!-- Estate Information Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Estate Information
      </div>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">Deceased Name</div>
          <div class="info-value"><?php echo e($calculation->deceased_name); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Gender</div>
          <div class="info-value"><?php echo e(ucfirst($calculation->deceased_gender)); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">NRIC</div>
          <div class="info-value"><?php echo e($calculation->deceased_nric ?? 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Date of Death</div>
          <div class="info-value"><?php echo e($calculation->date_of_death ? date('d M Y', strtotime($calculation->date_of_death)) : 'N/A'); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Marital Status</div>
          <div class="info-value"><?php echo e(ucfirst($calculation->marital_status)); ?></div>
        </div>
        <div class="info-item">
          <div class="info-label">Scenario</div>
          <div class="info-value">
            <span class="badge <?php echo e(($calculation->scenario_number ?? 0) > 0 ? 'badge-success' : 'badge-warning'); ?>">
              Scenario <?php echo e($calculation->scenario_number ?? 'N/A'); ?>

            </span>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Created By</div>
          <div class="info-value"><?php echo e($calculation->user->name ?? 'Unknown'); ?> (<?php echo e($calculation->user->email ?? 'N/A'); ?>)</div>
        </div>
        <div class="info-item">
          <div class="info-label">Created At</div>
          <div class="info-value"><?php echo e($calculation->created_at?->format('d M Y, h:i A')); ?></div>
        </div>
      </div>
    </div>

    <!-- Heirs Configuration Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
        </svg>
        Heirs Configuration
      </div>

      <?php if(!empty($heirsData) && count($heirsData) > 0): ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Heir Category</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              <?php if(isset($heirsData['husband_count']) && $heirsData['husband_count'] > 0): ?>
                <tr><td>Husband</td><td>Count: <?php echo e($heirsData['husband_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['wife_count']) && $heirsData['wife_count'] > 0): ?>
                <tr><td>Wife</td><td>Count: <?php echo e($heirsData['wife_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['father_status']) && $heirsData['father_status'] === 'alive'): ?>
                <tr><td>Father</td><td>Status: Alive</td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['mother_status']) && $heirsData['mother_status'] === 'alive'): ?>
                <tr><td>Mother</td><td>Status: Alive</td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['son_count']) && $heirsData['son_count'] > 0): ?>
                <tr><td>Sons</td><td>Count: <?php echo e($heirsData['son_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['daughter_count']) && $heirsData['daughter_count'] > 0): ?>
                <tr><td>Daughters</td><td>Count: <?php echo e($heirsData['daughter_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['full_brother_count']) && $heirsData['full_brother_count'] > 0): ?>
                <tr><td>Full Brothers</td><td>Count: <?php echo e($heirsData['full_brother_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['full_sister_count']) && $heirsData['full_sister_count'] > 0): ?>
                <tr><td>Full Sisters</td><td>Count: <?php echo e($heirsData['full_sister_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['paternal_half_brother_count']) && $heirsData['paternal_half_brother_count'] > 0): ?>
                <tr><td>Paternal Half-Brothers</td><td>Count: <?php echo e($heirsData['paternal_half_brother_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['paternal_half_sister_count']) && $heirsData['paternal_half_sister_count'] > 0): ?>
                <tr><td>Paternal Half-Sisters</td><td>Count: <?php echo e($heirsData['paternal_half_sister_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['maternal_half_brother_count']) && $heirsData['maternal_half_brother_count'] > 0): ?>
                <tr><td>Maternal Half-Brothers</td><td>Count: <?php echo e($heirsData['maternal_half_brother_count']); ?></td></tr>
              <?php endif; ?>
              <?php if(isset($heirsData['maternal_half_sister_count']) && $heirsData['maternal_half_sister_count'] > 0): ?>
                <tr><td>Maternal Half-Sisters</td><td>Count: <?php echo e($heirsData['maternal_half_sister_count']); ?></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="no-data">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
          </svg>
          <h3>No Heirs Data</h3>
          <p>Heirs configuration data is not available for this calculation.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Assets Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Assets
      </div>

      <?php if(!empty($assetsData) && isset($assetsData['properties']) && count($assetsData['properties']) > 0): ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Asset Type</th>
                <th>Description</th>
                <th>Value</th>
                <th>Ownership %</th>
                <th>Your Share</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $assetsData['properties']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $share = ($asset['value'] ?? 0) * (($asset['ownership'] ?? 100) / 100);
                ?>
                <tr>
                  <td><strong><?php echo e($asset['type'] ?? 'N/A'); ?></strong></td>
                  <td><?php echo e($asset['description'] ?? '-'); ?></td>
                  <td>RM <?php echo e(number_format($asset['value'] ?? 0, 2)); ?></td>
                  <td><?php echo e($asset['ownership'] ?? 100); ?>%</td>
                  <td>RM <?php echo e(number_format($share, 2)); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="no-data">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          <h3>No Assets Found</h3>
          <p>No assets have been registered for this calculation.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Inheritance Distribution Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
        </svg>
        Inheritance Distribution
      </div>

      <?php if(count($eligibleHeirs) > 0): ?>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Heir</th>
                <th>Relationship</th>
                <th>Share</th>
                <th>Amount</th>
                <th>Percentage</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $eligibleHeirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $heirArray = is_array($heir) ? $heir : (array)$heir;
                  $amount = $heirArray['amount'] ?? 0;
                  $percentage = ($netEstate > 0) ? ($amount / $netEstate) * 100 : 0;
                  $shareDisplay = $heirArray['share'] ?? $heirArray['fractionDisplay'] ?? 'N/A';
                  $status = $heirArray['status'] ?? 'Eligible';

                  $badgeClass = 'badge-primary';
                  if (str_contains($status, 'Fixed')) $badgeClass = 'badge-success';
                  elseif (str_contains($status, 'Asabah')) $badgeClass = 'badge-warning';
                  elseif (str_contains($status, 'Surplus')) $badgeClass = 'badge-info';
                ?>
                <tr>
                  <td><strong><?php echo e($heirArray['heir'] ?? $heirArray['name'] ?? 'Unknown'); ?></strong></td>
                  <td><?php echo e(ucfirst($heirArray['relationship'] ?? 'Unknown')); ?></td>
                  <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e($shareDisplay); ?></span></td>
                  <td><strong>RM <?php echo e(number_format($amount, 2)); ?></strong></td>
                  <td><?php echo e(number_format($percentage, 2)); ?>%</td>
                  <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e($status); ?></span></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
              <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Distributed:</td>
                <td colspan="3">RM <?php echo e(number_format($totalDistributed, 2)); ?> (100%)</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="summary-box">
          <h3>Distribution Summary</h3>
          <ul>
            <li><strong>Total Distributed:</strong> RM <?php echo e(number_format($totalDistributed, 2)); ?></li>
            <li><strong>Net Estate:</strong> RM <?php echo e(number_format($netEstate, 2)); ?></li>
            <li><strong>Eligible Heirs:</strong> <?php echo e(count($eligibleHeirs)); ?></li>
            <li><strong>Scenario Applied:</strong> Scenario <?php echo e($calculation->scenario_number ?? 'N/A'); ?></li>
          </ul>
        </div>
      <?php else: ?>
        <div class="no-data">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
          <h3>No Distribution Data</h3>
          <p>No inheritance distribution has been calculated for this case yet.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Actions -->
    <div class="modal-footer" style="margin-top: 2rem; justify-content: flex-start; gap: 1rem; background: none; border: none; padding: 0;">
      <button type="button" class="btn btn-danger" onclick="openDeleteModal('<?php echo e($calculation->id); ?>', '<?php echo e(addslashes($calculation->deceased_name)); ?>')">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Delete Calculation
      </button>
    </div>
  </main>

  <!-- Delete Confirmation Modal (Modern) -->
  <div id="deleteModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="deleteModalTitle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="26" height="26">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
          Confirm Deletion
        </h3>
        <button class="modal-close" onclick="closeDeleteModal()" aria-label="Close modal">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to permanently delete this calculation? This action <strong>cannot be undone</strong> and all associated data will be removed.</p>
        <div class="highlight-box">
          <strong>Calculation Details</strong>
          <span id="deleteCalcName"></span>
        </div>
      </div>
      <div class="modal-footer" style="background: none; border: none; padding: 1.25rem 2rem;">
        <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
        <form id="deleteForm" method="POST" style="display: none;"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>
        <button type="button" class="btn-confirm-danger" id="confirmDeleteBtn" onclick="confirmDelete()">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
          Yes, Delete Calculation
        </button>
      </div>
    </div>
  </div>

  <script>
    // =====================================================================
    // NAVIGATION MANAGER CLASS
    // =====================================================================
    class NavigationManager {
      constructor() {
        this.selectors = {
          userProfileBtn: '#user-profile-btn',
          userProfileDropdown: '#user-profile-dropdown',
          logoutForm: '.logout-form'
        };
        this.state = {
          isDropdownOpen: false,
          isTouchDevice: 'ontouchstart' in window || navigator.maxTouchPoints > 0
        };
        this.elements = {};
        this.init();
      }

      init() {
        this.cacheElements();
        this.bindEvents();
        this.setupAccessibility();
        this.setupKeyboardNavigation();
        this.setupTouchGestures();
      }

      cacheElements() {
        Object.keys(this.selectors).forEach(key => {
          this.elements[key] = document.querySelector(this.selectors[key]);
        });
      }

      bindEvents() {
        if (this.elements.userProfileBtn) {
          this.elements.userProfileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleUserDropdown();
          });
          this.elements.userProfileBtn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              this.toggleUserDropdown();
            }
            if (e.key === 'ArrowDown' && !this.state.isDropdownOpen) {
              e.preventDefault();
              this.openUserDropdown();
            }
          });
        }
        document.addEventListener('click', (e) => this.handleOutsideClick(e));
        document.addEventListener('keydown', (e) => this.handleKeydown(e));
      }

      setupAccessibility() {
        if (this.elements.userProfileBtn) {
          this.elements.userProfileBtn.setAttribute('role', 'button');
          this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
        }
        if (this.elements.userProfileDropdown) {
          this.elements.userProfileDropdown.setAttribute('role', 'menu');
          this.elements.userProfileDropdown.setAttribute('aria-label', 'User profile menu');
        }
        document.querySelectorAll('.dropdown-nav-item, .quick-action-item, .account-link').forEach(item => {
          item.setAttribute('role', 'menuitem');
        });
      }

      setupKeyboardNavigation() {
        const dropdownNav = this.elements.userProfileDropdown?.querySelector('.dropdown-nav');
        if (dropdownNav) {
          const items = dropdownNav.querySelectorAll('.dropdown-nav-item');
          items.forEach((item, index) => {
            item.addEventListener('keydown', (e) => {
              if (e.key === 'ArrowDown') {
                e.preventDefault();
                const next = items[index + 1] || items[0];
                next.focus();
              } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prev = items[index - 1] || items[items.length - 1];
                prev.focus();
              }
            });
          });
        }
      }

      setupTouchGestures() {
        if (!this.state.isTouchDevice) return;
        let startY = 0;
        if (this.elements.userProfileDropdown) {
          this.elements.userProfileDropdown.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
          });
          this.elements.userProfileDropdown.addEventListener('touchmove', (e) => {
            if (!this.state.isDropdownOpen) return;
            if (startY - e.touches[0].clientY > 50) {
              this.closeUserDropdown();
            }
          });
        }
      }

      handleOutsideClick(e) {
        if (this.state.isDropdownOpen &&
            this.elements.userProfileDropdown &&
            !this.elements.userProfileDropdown.contains(e.target) &&
            !this.elements.userProfileBtn.contains(e.target)) {
          this.closeUserDropdown();
        }
      }

      handleKeydown(e) {
        if (e.key === 'Escape' && this.state.isDropdownOpen) {
          this.closeUserDropdown();
          this.elements.userProfileBtn?.focus();
        }
        if (e.key === 'Tab' && this.state.isDropdownOpen && this.elements.userProfileDropdown) {
          this.trapFocus(this.elements.userProfileDropdown, e);
        }
      }

      trapFocus(element, e) {
        const focusable = element.querySelectorAll(
          'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        if (focusable.length === 0) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }

      toggleUserDropdown() {
        this.state.isDropdownOpen ? this.closeUserDropdown() : this.openUserDropdown();
      }

      openUserDropdown() {
        this.state.isDropdownOpen = true;
        if (this.elements.userProfileDropdown) {
          this.elements.userProfileDropdown.style.display = 'block';
          this.elements.userProfileDropdown.setAttribute('aria-hidden', 'false');
          setTimeout(() => {
            this.elements.userProfileDropdown.classList.add('active');
          }, 10);
          setTimeout(() => {
            const first = this.elements.userProfileDropdown.querySelector('a, button');
            if (first) first.focus();
          }, 100);
        }
        if (this.elements.userProfileBtn) {
          this.elements.userProfileBtn.setAttribute('aria-expanded', 'true');
          const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
          if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
      }

      closeUserDropdown() {
        this.state.isDropdownOpen = false;
        if (this.elements.userProfileDropdown) {
          this.elements.userProfileDropdown.classList.remove('active');
          this.elements.userProfileDropdown.setAttribute('aria-hidden', 'true');
          setTimeout(() => {
            if (!this.state.isDropdownOpen) {
              this.elements.userProfileDropdown.style.display = 'none';
            }
          }, 300);
        }
        if (this.elements.userProfileBtn) {
          this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
          const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
          if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
      }
    }

    // =====================================================================
    // DELETE MODAL FUNCTIONS
    // =====================================================================
    function openDeleteModal(id, name) {
      document.getElementById('deleteCalcName').textContent = `Deceased: ${name} (ID: #${id})`;
      document.getElementById('deleteForm').action = `/admin/calculations/${id}`;
      document.getElementById('deleteModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
      setTimeout(() => {
        document.getElementById('confirmDeleteBtn')?.focus();
      }, 100);
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').style.display = 'none';
      document.body.style.overflow = '';
    }

    function confirmDelete() {
      document.getElementById('deleteForm').submit();
    }

    // =====================================================================
    // INITIALIZATION & EVENT LISTENERS
    // =====================================================================
    document.addEventListener('DOMContentLoaded', function() {
      window.navigationManager = new NavigationManager();

      // Delete modal close on outside click
      const deleteModal = document.getElementById('deleteModal');
      if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
          if (e.target === deleteModal) closeDeleteModal();
        });
      }

      // Escape key for delete modal
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (deleteModal && deleteModal.style.display === 'flex') {
            closeDeleteModal();
          }
        }
      });

      // Auto-hide session alerts after 5 seconds
      setTimeout(() => {
        document.querySelectorAll('.alert-message').forEach(alert => {
          if (alert.parentNode) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            setTimeout(() => {
              if (alert.parentNode) alert.remove();
            }, 300);
          }
        });
      }, 5000);

      // Prevent FOUC
      document.body.style.opacity = '0';
      document.body.style.transition = 'opacity 0.3s ease';
      setTimeout(() => {
        document.body.style.opacity = '1';
      }, 50);
    });
  </script>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/admin/calculations/show.blade.php ENDPATH**/ ?>