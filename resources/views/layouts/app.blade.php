<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Buku') - Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --secondary: #64748b;
            --success: #16a34a;
            --success-bg: #f0fdf4;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --warning: #d97706;
            --warning-bg: #fffbeb;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-focus: #3b82f6;
            --radius-md: 10px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            padding: 36px 32px;
        }

        .dashboard-container {
            width: 100%;
            max-width: 760px;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            padding: 36px 32px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-badge {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 12px;
        }

        .auth-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .auth-header p {
            font-size: 14px;
            color: var(--text-muted);
        }

        .alert {
            padding: 14px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.5;
        }

        .alert-danger {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: var(--success);
            border: 1px solid #bbf7d0;
        }

        .alert-warning {
            background-color: var(--warning-bg);
            color: var(--warning);
            border: 1px solid #fde68a;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            background-color: #ffffff;
            color: var(--text-main);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background-color: #fffbfa;
        }

        .invalid-feedback {
            font-size: 12px;
            color: var(--danger);
            margin-top: 5px;
            font-weight: 500;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 600px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .auth-container, .dashboard-container {
                padding: 24px 20px;
            }
        }

        /* Role Selector Cards */
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 6px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 14px 12px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            background-color: #ffffff;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }

        .role-card .role-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .role-card .role-desc {
            font-size: 12px;
            color: var(--text-muted);
        }

        .role-option input[type="radio"]:checked + .role-card {
            border-color: var(--primary);
            background-color: var(--primary-light);
            box-shadow: 0 0 0 1px var(--primary);
        }

        .btn-submit {
            width: 100%;
            padding: 12px 18px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            color: #ffffff;
            background-color: var(--primary);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-logout {
            padding: 9px 18px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            color: #dc2626;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Dashboard specific styling */
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dash-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
        }

        .dash-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .user-card {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 20px;
        }

        .user-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .user-info-row:last-child {
            border-bottom: none;
        }

        .user-info-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .user-info-value {
            color: var(--text-main);
            font-weight: 600;
        }

        .role-badge-peminjam {
            background-color: #eff6ff;
            color: #2563eb;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #bfdbfe;
        }

        .role-badge-petugas {
            background-color: #f5f3ff;
            color: #7c3aed;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #ddd6fe;
        }
    </style>
</head>
<body>
    @hasSection('container')
        @yield('container')
    @else
        <main class="auth-container">
            @yield('content')
        </main>
    @endif
</body>
</html>
