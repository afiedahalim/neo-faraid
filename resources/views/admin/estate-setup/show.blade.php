<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <title>Estate Details • Neo Faraid Admin</title>
  <meta name="description" content="View and manage estate details, debts, and approval status in Neo Faraid Admin Panel">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: var(--white);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
      background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
    }

    .btn-success {
      background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
      color: var(--white);
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
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

    .btn-settle {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.5rem 1rem;
      background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
      color: #2c2c2c;
      border: none;
      border-radius: var(--border-radius-sm);
      cursor: pointer;
      font-size: 0.875rem;
      font-weight: 500;
      transition: var(--transition);
      font-family: inherit;
      box-shadow: var(--shadow-sm);
    }

    .btn-settle:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .btn-settle.settled {
      background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
      color: white;
      cursor: default;
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

    .badge-danger {
      background: #f8d7da;
      color: #721c24;
    }

    /* Alert Messages */
    .alert-message {
      padding: 1rem 1.5rem;
      border-radius: var(--border-radius-md);
      margin-bottom: 1.5rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      animation: slideIn 0.3s ease;
      background: var(--white);
      box-shadow: var(--shadow-sm);
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
      color: #155724;
      border-left: 4px solid #28a745;
    }

    .alert-error {
      background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
      color: #721c24;
      border-left: 4px solid var(--danger-color);
    }

    .alert-warning {
      background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
      color: #856404;
      border-left: 4px solid var(--warning-color);
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

    .summary-box .alert-message {
      margin-top: 0.75rem;
      margin-bottom: 0;
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

    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      animation: fadeIn 0.3s ease;
      backdrop-filter: blur(5px);
      padding: 1rem;
    }

    .modal-content {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      box-shadow: var(--shadow-lg);
      width: 100%;
      max-width: 500px;
      animation: slideUp 0.4s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal-header {
      padding: 1.5rem;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: var(--white);
      border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-title {
      font-size: 1.3rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .modal-close {
      background: rgba(255,255,255,0.2);
      border: none;
      color: var(--white);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
    }

    .modal-close:hover {
      background: rgba(255,255,255,0.3);
      transform: rotate(90deg);
    }

    .modal-body {
      padding: 1.5rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text-primary);
      font-size: 0.9rem;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid var(--light-border);
      border-radius: var(--border-radius-sm);
      font-family: var(--font-family);
      font-size: 0.95rem;
      transition: var(--transition);
      color: var(--text-primary);
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }

    /* Loading Spinner */
    .loading-spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
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
      .btn-settle { padding: 0.4rem 0.75rem; font-size: 0.75rem; }
      th, td { padding: 0.75rem; }
      .stats-card-value { font-size: 1.5rem; }
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
      <a href="{{ route('admin.dashboard') }}" class="admin-logo" aria-label="Neo Faraid Admin Home">
        <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
        <span class="admin-logo-text">Neo Faraid Admin</span>
      </a>
    </div>
    <div class="nav-right">
      @php
        $user = auth()->user();
        $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
        $fullInitials = strtoupper(implode('', array_map(function($name) { return substr($name, 0, 1); }, explode(' ', $user->name, 2))));
      @endphp
      <div class="user-profile-container" id="user-profile-container">
        <button class="user-profile-btn" id="user-profile-btn" aria-label="User profile menu" aria-expanded="false" aria-haspopup="true" data-user-role="{{ $user->role }}" data-user-initials="{{ $fullInitials }}">
          <div class="user-avatar" aria-hidden="true">
            <span class="avatar-initials">{{ $fullInitials }}</span>
            @if($user->role === 'admin')
              <span class="admin-badge" aria-label="Administrator">A</span>
            @endif
          </div>
          <div class="user-profile-info">
            <span class="user-name">{{ $user->name }}</span>
            <span class="user-role">{{ ucfirst($user->role) }}</span>
          </div>
          <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="user-profile-dropdown" id="user-profile-dropdown" role="menu" aria-label="User profile menu" aria-hidden="true">
          <div class="profile-header">
            <div class="profile-avatar-large" aria-hidden="true">
              <span class="avatar-initials-large">{{ $fullInitials }}</span>
              @if($user->role === 'admin')
                <div class="admin-badge-large" aria-label="Administrator">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                </div>
              @endif
            </div>
            <div class="profile-info">
              <h3 class="profile-name">{{ $user->name }}</h3>
              <p class="profile-email">{{ $user->email }}</p>
              <div class="profile-meta">
                <span class="last-login">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Last login: {{ $lastLogin }}
                </span>
                <span class="user-status active">● Online</span>
              </div>
            </div>
          </div>
          <div class="dropdown-section">
            <h4 class="section-title">Navigation</h4>
            <nav class="dropdown-nav" role="navigation" aria-label="Admin navigation">
              <a href="{{ route('admin.users.index') }}" class="dropdown-nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                <span>Manage Users</span>
              </a>
              <a href="{{ route('admin.feedback.index') }}" class="dropdown-nav-item {{ request()->is('admin/feedback*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <span>Manage Feedback</span>
              </a>
              <a href="{{ route('admin.faq.index') }}" class="dropdown-nav-item {{ request()->is('admin/faq*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Manage FAQ</span>
              </a>
              <a href="{{ route('admin.calculations.index') }}" class="dropdown-nav-item {{ request()->is('admin/calculations*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Manage Calculations</span>
              </a>
              <a href="{{ route('admin.instant-estate.index') }}" class="dropdown-nav-item {{ request()->is('admin/instant-estate*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Manage Instant Estate</span>
              </a>
              <a href="{{ route('admin.estate-setup.index') }}" class="dropdown-nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Manage Estate Planning</span>
              </a>
            </nav>
          </div>
          <div class="dropdown-section">
            <h4 class="section-title">Account</h4>
            <div class="account-links">
              <a href="{{ route('profile.edit') }}" class="account-link {{ request()->is('profile*') ? 'active' : '' }}" role="menuitem">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Profile Settings</span>
              </a>
            </div>
          </div>
          <div class="dropdown-footer">
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
              @csrf
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
        <h1>Estate Details</h1>
        <p class="page-subtitle">Deceased: {{ $estate->deceased_name }} | ID: {{ $estate->unique_id }}</p>
      </div>
      <a href="{{ route('admin.estate-setup.index') }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
        </svg>
        Back to Estates
      </a>
    </header>

    @if(session('success'))
      <div class="alert-message alert-success" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert-message alert-error" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-cards">
      <div class="stats-card">
        <div class="stats-card-title">Total Assets</div>
        <div class="stats-card-value">RM {{ number_format($estate->total_assets, 2) }}</div>
        <div class="stats-card-change">Registered assets</div>
      </div>
      <div class="stats-card">
        <div class="stats-card-title">Total Debts</div>
        <div class="stats-card-value">RM {{ number_format($estate->total_debts, 2) }}</div>
        <div class="stats-card-change">Registered debts</div>
      </div>
      <div class="stats-card">
        <div class="stats-card-title">Remaining Debts</div>
        <div class="stats-card-value">RM {{ number_format($debtSettlementStatus['remaining'] ?? 0, 2) }}</div>
        <div class="stats-card-change">Yet to be settled</div>
      </div>
      <div class="stats-card">
        <div class="stats-card-title">Settlement Progress</div>
        <div class="stats-card-value">{{ $debtSettlementStatus['settlement_percentage'] ?? 0 }}%</div>
        <div class="stats-card-change">Completed settlements</div>
      </div>
    </div>

    <!-- Outstanding Debts Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        Outstanding Debts
      </div>

      @if($estate->debts->count() > 0)
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Creditor</th>
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Remaining</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($estate->debts as $debt)
                @php
                  $remaining = $debt->amount - $debt->amount_paid;
                  $isSettled = $remaining <= 0 || $debt->status === 'settled';
                @endphp
                <tr id="debt-row-{{ $debt->id }}">
                  <td><strong>{{ $debt->creditor_name }}</strong></td>
                  <td>RM {{ number_format($debt->amount, 2) }}</td>
                  <td id="debt-paid-{{ $debt->id }}">RM {{ number_format($debt->amount_paid, 2) }}</td>
                  <td id="debt-remaining-{{ $debt->id }}" style="color: {{ $isSettled ? 'var(--success-color)' : 'var(--warning-color)' }}; font-weight: 600;">
                    RM {{ number_format($remaining, 2) }}
                  </td>
                  <td id="debt-status-{{ $debt->id }}">
                    <span class="badge badge-{{ $isSettled ? 'success' : ($debt->amount_paid > 0 ? 'info' : 'warning') }}">
                      {{ $isSettled ? 'Settled' : ($debt->amount_paid > 0 ? 'Partial' : 'Pending') }}
                    </span>
                  </td>
                  <td id="debt-action-{{ $debt->id }}">
                    @if(!$isSettled)
                      <button type="button" class="btn-settle" onclick="openSettleModal({{ $debt->id }}, '{{ addslashes($debt->creditor_name) }}', {{ $remaining }})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Settle
                      </button>
                    @else
                      <button class="btn-settle settled" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Settled
                      </button>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="summary-box">
          <h3>Debt Settlement Summary</h3>
          <ul id="summary-list">
            <li><strong>Settled:</strong> <span id="summary-settled">{{ $estate->debts->count() - ($debtSettlementStatus['unsettled_count'] ?? 0) }}</span> debts</li>
            <li><strong>Pending:</strong> <span id="summary-pending">{{ $debtSettlementStatus['unsettled_count'] ?? 0 }}</span> debts</li>
            <li><strong>Total Paid:</strong> <span id="summary-paid">{{ $debtSettlementStatus['formatted_paid'] ?? 'RM 0.00' }}</span></li>
            <li><strong>Remaining:</strong> <span id="summary-remaining">{{ $debtSettlementStatus['formatted_remaining'] ?? 'RM 0.00' }}</span></li>
          </ul>

          <div id="settlement-alert-area">
            @if(!($debtSettlementStatus['all_settled'] ?? false))
              <div class="alert-message alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <strong>Action Required:</strong> Please mark ALL debts as settled before approving this estate.
              </div>
            @else
              <div class="alert-message alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <strong>All debts settled!</strong> You can now approve this estate.
              </div>
            @endif
          </div>
        </div>
      @else
        <div class="alert-message alert-success">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          No debts registered for this estate.
        </div>
      @endif
    </div>

    <!-- Estate Approval Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Estate Approval
      </div>

      <div id="approval-section">
        @if(!($readiness['ready'] ?? false))
          <div class="alert-message alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div>
              <strong>Estate not ready for approval:</strong>
              <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                @foreach($readiness['issues'] ?? [] as $issue)
                  <li>{{ $issue }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @elseif(!($debtSettlementStatus['all_settled'] ?? false))
          <div class="alert-message alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <strong>Cannot Approve:</strong> Please settle all outstanding debts before approving.
          </div>
        @else
          <div class="alert-message alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <strong>Estate is ready for approval!</strong> All debts are settled and all requirements are met.
          </div>
          <form id="approvalForm" action="{{ route('admin.estate-setup.approve', $estate->unique_id) }}" method="POST">
            @csrf
            <button type="button" class="btn btn-success" onclick="openApprovalModal()">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Approve Estate &amp; Send Notifications
            </button>
          </form>
        @endif
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
          <div class="info-value">{{ $estate->deceased_name }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">NRIC</div>
          <div class="info-value">{{ $estate->deceased_nric ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Trustee Name</div>
          <div class="info-value">{{ $estate->trustee_name ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Trustee Email</div>
          <div class="info-value">{{ $estate->trustee_email ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Status</div>
          <div class="info-value">
            <span class="badge badge-{{ $estate->status === 'active' ? 'success' : ($estate->status === 'pending' ? 'warning' : 'info') }}">
              {{ $estate->status_label }}
            </span>
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Admin Approved</div>
          <div class="info-value">
            @if($estate->admin_approved)
              <span class="badge badge-success">Yes</span>
            @else
              <span class="badge badge-danger">No</span>
            @endif
          </div>
        </div>
        <div class="info-item">
          <div class="info-label">Net Estate</div>
          <div class="info-value">RM {{ number_format($estate->net_estate, 2) }}</div>
        </div>
        <div class="info-item">
          <div class="info-label">Created</div>
          <div class="info-value">{{ $estate->created_at?->format('d M Y, h:i A') }}</div>
        </div>
      </div>
    </div>

    <!-- Heirs Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
        </svg>
        Heirs ({{ $estate->heirs->count() }})
      </div>

      @if($estate->heirs->count() > 0)
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>NRIC</th>
                <th>Relationship</th>
                <th>Email</th>
                <th>Share %</th>
              </tr>
            </thead>
            <tbody>
              @foreach($estate->heirs as $heir)
                <tr>
                  <td><strong>{{ $heir->name }}</strong></td>
                  <td>{{ $heir->nric ?? 'N/A' }}</td>
                  <td>{{ ucfirst(str_replace('_', ' ', $heir->relationship)) }}</td>
                  <td>{{ $heir->email ?? 'N/A' }}</td>
                  <td>{{ number_format($heir->share_percentage ?? 0, 2) }}%</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="no-data">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
          </svg>
          <h3>No Heirs Found</h3>
          <p>No heirs have been registered for this estate.</p>
        </div>
      @endif
    </div>

    <!-- Assets Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Assets ({{ $estate->assets->count() }})
      </div>

      @if($estate->assets->count() > 0)
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Asset Name</th>
                <th>Value</th>
                <th>Ownership %</th>
                <th>Owned Value</th>
              </tr>
            </thead>
            <tbody>
              @foreach($estate->assets as $asset)
                <tr>
                  <td><strong>{{ $asset->name }}</strong></td>
                  <td>RM {{ number_format($asset->value, 2) }}</td>
                  <td>{{ $asset->ownership_percentage ?? 100 }}%</td>
                  <td>RM {{ number_format(($asset->value * ($asset->ownership_percentage ?? 100)) / 100, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="no-data">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          <h3>No Assets Found</h3>
          <p>No assets have been registered for this estate.</p>
        </div>
      @endif
    </div>

    <!-- Wasiyyah Section -->
    <div class="content-block">
      <div class="block-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Wasiyyah Beneficiaries ({{ $estate->wasiyyah->count() }})
      </div>

      @if($estate->wasiyyah->count() > 0)
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Relationship</th>
                <th>Email</th>
                <th>Requested %</th>
              </tr>
            </thead>
            <tbody>
              @foreach($estate->wasiyyah as $wasiyyah)
                <tr>
                  <td><strong>{{ $wasiyyah->beneficiary_name }}</strong></td>
                  <td>{{ $wasiyyah->relationship }}</td>
                  <td>{{ $wasiyyah->beneficiary_email ?? 'N/A' }}</td>
                  <td>{{ number_format($wasiyyah->requested_percentage, 2) }}%</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="no-data">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <h3>No Wasiyyah Beneficiaries</h3>
          <p>No wasiyyah beneficiaries have been registered.</p>
        </div>
      @endif
    </div>
  </main>

  <!-- Settlement Modal -->
  <div id="settlementModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modalTitle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Mark Debt as Settled
        </h3>
        <button class="modal-close" onclick="closeModal()" aria-label="Close modal">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <form id="settlementForm" method="POST" onsubmit="return false;">
          @csrf
          <input type="hidden" name="debt_id" id="debtId">
          <div class="form-group">
            <label for="creditorName">Creditor Name</label>
            <input type="text" id="creditorName" readonly>
          </div>
          <div class="form-group">
            <label for="remainingAmount">Remaining Amount</label>
            <input type="text" id="remainingAmount" readonly>
          </div>
          <div class="form-group">
            <label for="settlementReference">Settlement Reference (Optional)</label>
            <input type="text" name="settlement_reference" id="settlementReference" placeholder="Receipt number, payment ref, etc.">
          </div>
          <div class="form-group">
            <label for="settlementNotes">Notes (Optional)</label>
            <textarea name="notes" id="settlementNotes" rows="3" placeholder="Additional notes about this settlement..."></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
            <button type="button" class="btn btn-success" id="confirmSettlementBtn" onclick="submitSettlement()">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              Confirm Settlement
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Approval Confirmation Modal -->
  <div id="approvalModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="approvalModalTitle">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="approvalModalTitle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Confirm Estate Approval
        </h3>
        <button class="modal-close" onclick="closeApprovalModal()" aria-label="Close modal">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <p style="font-size: 1rem; color: var(--text-primary); line-height: 1.6;">
          You are about to <strong>approve</strong> this estate. Once approved, the system will automatically:
        </p>
        <ul style="margin-top: 1rem; padding-left: 1.5rem; color: var(--text-primary); line-height: 1.8;">
          <li>Generate unique access links for all beneficiaries</li>
          <li>Send notification emails to heirs, trustees, and wasiyyah beneficiaries via SMTP</li>
        </ul>
        <div style="background: var(--success-light); border-radius: var(--border-radius-sm); padding: 0.75rem 1rem; margin-top: 1rem; color: var(--success-dark); font-size: 0.9rem;">
          <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
          <strong>Emails will be dispatched immediately upon approval.</strong>
        </div>
        <div class="modal-footer" style="margin-top: 1.5rem;">
          <button type="button" class="btn btn-outline" onclick="closeApprovalModal()">Cancel</button>
          <button type="button" class="btn btn-success" id="confirmApprovalBtn" onclick="submitApproval()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Approve & Send Notifications
          </button>
        </div>
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
    // GLOBAL VARIABLES
    // =====================================================================
    let currentDebtId = null;
    const estateUniqueId = '{{ $estate->unique_id }}';
    const baseUrl = '{{ url("admin/estate-setup") }}';

    // =====================================================================
    // DEBT SETTLEMENT MODAL FUNCTIONS
    // =====================================================================
    function openSettleModal(debtId, creditorName, remainingAmount) {
      currentDebtId = debtId;
      const form = document.getElementById('settlementForm');
      form.action = `${baseUrl}/${estateUniqueId}/debts/${debtId}/settle`;
      document.getElementById('debtId').value = debtId;
      document.getElementById('creditorName').value = creditorName;
      document.getElementById('remainingAmount').value = 'RM ' + remainingAmount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      document.getElementById('settlementReference').value = '';
      document.getElementById('settlementNotes').value = '';
      const confirmBtn = document.getElementById('confirmSettlementBtn');
      confirmBtn.disabled = false;
      confirmBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Confirm Settlement`;
      document.getElementById('settlementModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
      setTimeout(() => {
        document.getElementById('settlementReference')?.focus();
      }, 100);
    }

    function closeModal() {
      document.getElementById('settlementModal').style.display = 'none';
      document.body.style.overflow = '';
      currentDebtId = null;
    }

    function submitSettlement() {
      const form = document.getElementById('settlementForm');
      const confirmBtn = document.getElementById('confirmSettlementBtn');
      const originalBtnHTML = confirmBtn.innerHTML;
      if (!currentDebtId) {
        showAlert('error', 'No debt selected for settlement.');
        return;
      }
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';
      const formData = new FormData(form);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json().then(data => ({ status: response.status, data })))
      .then(result => {
        if (result.data.success) {
          updateDebtRowUI(currentDebtId);
          showAlert('success', result.data.message || 'Debt marked as settled successfully.');
          closeModal();
          updateSummaryUI(result.data);
          if (result.data.all_debts_settled) {
            updateApprovalSection(true);
          }
        } else {
          showAlert('error', result.data.error || result.data.message || 'Failed to settle debt.');
          confirmBtn.disabled = false;
          confirmBtn.innerHTML = originalBtnHTML;
        }
      })
      .catch(error => {
        showAlert('error', 'An error occurred: ' + (error.message || 'Please try again.'));
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = originalBtnHTML;
      });
    }

    // =====================================================================
    // APPROVAL MODAL FUNCTIONS
    // =====================================================================
    function openApprovalModal() {
      document.getElementById('approvalModal').style.display = 'flex';
      document.body.style.overflow = 'hidden';
      setTimeout(() => {
        document.getElementById('confirmApprovalBtn')?.focus();
      }, 100);
    }

    function closeApprovalModal() {
      document.getElementById('approvalModal').style.display = 'none';
      document.body.style.overflow = '';
    }

    function submitApproval() {
      document.getElementById('approvalForm').submit();
    }

    // =====================================================================
    // UI UPDATE FUNCTIONS
    // =====================================================================
    function updateDebtRowUI(debtId) {
      const paidCell = document.getElementById('debt-paid-' + debtId);
      const remainingCell = document.getElementById('debt-remaining-' + debtId);
      const statusCell = document.getElementById('debt-status-' + debtId);
      const actionCell = document.getElementById('debt-action-' + debtId);
      if (paidCell) {
        const row = document.getElementById('debt-row-' + debtId);
        const totalAmountText = row.querySelector('td:nth-child(2)').textContent;
        const totalAmount = totalAmountText.replace('RM ', '').replace(/,/g, '');
        paidCell.textContent = 'RM ' + parseFloat(totalAmount).toLocaleString('en-US', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }
      if (remainingCell) {
        remainingCell.textContent = 'RM 0.00';
        remainingCell.style.color = 'var(--success-color)';
      }
      if (statusCell) {
        statusCell.innerHTML = '<span class="badge badge-success">Settled</span>';
      }
      if (actionCell) {
        actionCell.innerHTML = `<button class="btn-settle settled" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Settled
        </button>`;
      }
    }

    function updateSummaryUI(data) {
      const remainingDebtsCard = document.querySelector('.stats-card:nth-child(3) .stats-card-value');
      const settlementProgressCard = document.querySelector('.stats-card:nth-child(4) .stats-card-value');
      if (remainingDebtsCard && data.settlement_summary) {
        remainingDebtsCard.textContent = data.settlement_summary.remaining || 'RM 0.00';
      }
      if (settlementProgressCard && data.settlement_percentage !== undefined) {
        settlementProgressCard.textContent = data.settlement_percentage + '%';
      }
      const summarySettled = document.getElementById('summary-settled');
      const summaryPending = document.getElementById('summary-pending');
      const summaryPaid = document.getElementById('summary-paid');
      const summaryRemaining = document.getElementById('summary-remaining');
      if (summaryPending && data.remaining_debts !== undefined) {
        const totalDebts = {{ $estate->debts->count() }};
        const settledCount = totalDebts - data.remaining_debts;
        if (summarySettled) summarySettled.textContent = settledCount;
        if (summaryPending) summaryPending.textContent = data.remaining_debts;
      }
      if (summaryPaid && data.settlement_summary) {
        summaryPaid.textContent = data.settlement_summary.total_paid || 'RM 0.00';
      }
      if (summaryRemaining && data.settlement_summary) {
        summaryRemaining.textContent = data.settlement_summary.remaining || 'RM 0.00';
      }
    }

    function updateApprovalSection(allSettled) {
      const approvalSection = document.getElementById('approval-section');
      const settlementAlertArea = document.getElementById('settlement-alert-area');
      if (settlementAlertArea) {
        if (allSettled) {
          settlementAlertArea.innerHTML = `<div class="alert-message alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <strong>All debts settled!</strong> You can now approve this estate.
          </div>`;
        }
      }
      if (approvalSection && allSettled) {
        @php
          $readyForApproval = ($readiness['ready'] ?? false) && ($debtSettlementStatus['all_settled'] ?? false);
        @endphp
        const isReady = {{ json_encode($readyForApproval) }};
        if (isReady) {
          approvalSection.innerHTML = `<div class="alert-message alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <strong>Estate is ready for approval!</strong> All debts are settled and all requirements are met.
          </div>
          <form id="approvalForm" action="{{ route('admin.estate-setup.approve', $estate->unique_id) }}" method="POST">
            @csrf
            <button type="button" class="btn btn-success" onclick="openApprovalModal()">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Approve Estate &amp; Send Notifications
            </button>
          </form>`;
        }
      }
    }

    // =====================================================================
    // ALERT FUNCTION
    // =====================================================================
    function showAlert(type, message) {
      const existingAlerts = document.querySelectorAll('.alert-message.dynamic-alert');
      existingAlerts.forEach(alert => alert.remove());
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert-message alert-${type} dynamic-alert`;
      const successIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
      const errorIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
      alertDiv.innerHTML = `${type === 'success' ? successIcon : errorIcon} ${message}`;
      const main = document.querySelector('.admin-main');
      if (main) {
        main.insertBefore(alertDiv, main.firstChild);
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateY(-10px)';
        alertDiv.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        setTimeout(() => {
          if (alertDiv.parentNode) alertDiv.remove();
        }, 300);
      }, 6000);
    }

    // =====================================================================
    // INITIALIZATION & EVENT LISTENERS
    // =====================================================================
    document.addEventListener('DOMContentLoaded', function() {
      window.navigationManager = new NavigationManager();

      // Settlement modal close on outside click
      const settlementModal = document.getElementById('settlementModal');
      if (settlementModal) {
        settlementModal.addEventListener('click', function(e) {
          if (e.target === settlementModal) closeModal();
        });
      }

      // Approval modal close on outside click
      const approvalModal = document.getElementById('approvalModal');
      if (approvalModal) {
        approvalModal.addEventListener('click', function(e) {
          if (e.target === approvalModal) closeApprovalModal();
        });
      }

      // Escape key for both modals
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (settlementModal && settlementModal.style.display === 'flex') {
            closeModal();
          }
          if (approvalModal && approvalModal.style.display === 'flex') {
            closeApprovalModal();
          }
        }
      });

      // Auto-hide session alerts after 5 seconds
      setTimeout(() => {
        document.querySelectorAll('.alert-message:not(.dynamic-alert)').forEach(alert => {
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
</html>