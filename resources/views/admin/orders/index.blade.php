@extends('layouts.admin')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">لوحة التحكم</a>
    </div>
    <div class="breadcrumb-item">
        <i class="fas fa-chevron-left"></i>
        الطلبات
    </div>
@endsection

@push('styles')
<style>
    :root {
        /* Modern Color Palette */
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-200: #bfdbfe;
        --primary-300: #93c5fd;
        --primary-400: #60a5fa;
        --primary-500: #3b82f6;
        --primary-600: #2563eb;
        --primary-700: #1d4ed8;
        --primary-800: #1e40af;
        --primary-900: #1e3a8a;
        
        --admin-secondary-25: #fafafa;
        --admin-secondary-50: #f8fafc;
        --admin-secondary-100: #f1f5f9;
        --admin-secondary-200: #e2e8f0;
        --admin-secondary-300: #cbd5e1;
        --admin-secondary-400: #94a3b8;
        --admin-secondary-500: #64748b;
        --admin-secondary-600: #475569;
        --admin-secondary-700: #334155;
        --admin-secondary-800: #1e293b;
        --admin-secondary-900: #0f172a;
        
        --admin-primary-500: var(--primary-500);
        --admin-primary-600: var(--primary-600);
        --admin-primary-700: var(--primary-700);
        
        --success-500: #10b981;
        --success-600: #059669;
        --warning-500: #f59e0b;
        --warning-600: #d97706;
        --error-500: #ef4444;
        --error-600: #dc2626;
        --error-300: #fca5a5;
        --purple-500: #8b5cf6;
        --purple-600: #7c3aed;
        
        /* Spacing System */
        --space-xs: 0.25rem;
        --space-sm: 0.5rem;
        --space-md: 0.75rem;
        --space-lg: 1rem;
        --space-xl: 1.5rem;
        --space-2xl: 2rem;
        --space-3xl: 3rem;
        
        /* Border Radius */
        --radius-sm: 0.375rem;
        --radius-md: 0.5rem;
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        
        /* Transitions */
        --transition-fast: all 0.15s ease;
        --transition-normal: all 0.3s ease;
        --transition-slow: all 0.5s ease;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    * {
        direction: rtl;
        text-align: right;
    }
    
    body {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }
    
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
        gap: var(--space-xl);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: var(--space-2xl);
        border-radius: var(--radius-2xl);
        color: white;
        box-shadow: var(--shadow-2xl);
        position: relative;
        overflow: hidden;
    }
    
    .orders-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        z-index: 0;
    }
    
    .orders-header > * {
        position: relative;
        z-index: 1;
    }
    
    .orders-title {
        font-size: clamp(1.5rem, 4vw, 2.25rem);
        font-weight: 800;
        color: white;
        display: flex;
        align-items: center;
        gap: var(--space-md);
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0;
    }
    
    .orders-title i {
        font-size: 1.2em;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .orders-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: var(--space-lg);
        width: 100%;
        max-width: 500px;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--space-xl);
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(20px);
        border-radius: var(--radius-xl);
        border: 1px solid rgba(255,255,255,0.3);
        min-width: 120px;
        transition: var(--transition-normal);
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .stat-item::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg);
        transition: var(--transition-slow);
        opacity: 0;
    }
    
    .stat-item:hover {
        transform: translateY(-8px) scale(1.02);
        background: rgba(255,255,255,0.25);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .stat-item:hover::before {
        opacity: 1;
        animation: shimmer 1s ease-in-out;
    }
    
    @keyframes shimmer {
        0% { top: -100%; left: -100%; }
        100% { top: 100%; left: 100%; }
    }
    
    .stat-number {
        font-size: clamp(1.5rem, 3vw, 2.5rem);
        font-weight: 900;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        display: block;
        margin-bottom: var(--space-sm);
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.9);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-xs);
        font-weight: 600;
    }
    
    .filters-section {
        background: white;
        border-radius: var(--radius-2xl);
        padding: var(--space-2xl);
        margin-bottom: var(--space-2xl);
        box-shadow: var(--shadow-xl);
        border: 1px solid var(--admin-secondary-200);
        position: relative;
        overflow: hidden;
    }
    
    .filters-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--purple-500), var(--success-500));
        border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
    }
    
    .filters-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: var(--space-xl);
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        position: relative;
        padding-bottom: var(--space-md);
    }
    
    .filters-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, var(--admin-primary-500), var(--purple-500));
        border-radius: var(--radius-lg);
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--space-xl);
        margin-bottom: var(--space-xl);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
        position: relative;
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--admin-secondary-700);
    }
    
    .filter-input {
        padding: var(--space-lg) var(--space-xl);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        transition: var(--transition-fast);
        background: #ffffff;
        position: relative;
        width: 100%;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: var(--admin-primary-500);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }
    
    .filter-input:hover {
        border-color: var(--admin-primary-300);
    }
    
    .filter-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: var(--space-lg) var(--space-2xl);
        border: none;
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-fast);
        display: inline-flex;
        align-items: center;
        gap: var(--space-sm);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
        min-width: 120px;
        justify-content: center;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: var(--transition-normal);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--admin-secondary-500), var(--admin-secondary-600));
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .orders-table-container {
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--admin-secondary-100);
        margin-bottom: var(--space-2xl);
    }
    
    .table-header {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-2xl);
        border-bottom: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-lg);
    }
    
    .table-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-secondary-900);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin: 0;
    }
    
    .export-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: var(--space-lg) var(--space-2xl);
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: var(--shadow-lg);
    }
    
    .export-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.4);
    }
    
    /* Responsive Table Wrapper */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        direction: rtl;
        min-width: 900px;
    }
    
    .orders-table th {
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        padding: var(--space-xl);
        text-align: right;
        font-weight: 700;
        color: var(--admin-secondary-700);
        font-size: 0.875rem;
        border-bottom: 2px solid var(--admin-secondary-200);
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .orders-table td {
        padding: var(--space-xl);
        border-bottom: 1px solid var(--admin-secondary-100);
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .orders-table tbody tr {
        transition: var(--transition-fast);
        cursor: pointer;
    }
    
    .orders-table tbody tr:hover {
        background: linear-gradient(135deg, var(--admin-secondary-25), #f8fafc);
        transform: scale(1.005);
        box-shadow: var(--shadow-md);
    }
    
    .order-id {
        font-weight: 700;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none;
        transition: var(--transition-fast);
        font-size: 1rem;
    }
    
    .order-id:hover {
        transform: scale(1.05);
        text-decoration: underline;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: var(--space-md);
    }
    
    .customer-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-lg);
    }
    
    .customer-details {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    
    .customer-name {
        font-weight: 700;
        color: var(--admin-secondary-900);
        line-height: 1.2;
        font-size: 0.95rem;
        margin: 0;
    }
    
    .customer-email {
        color: var(--admin-secondary-600);
        font-size: 0.8rem;
        line-height: 1.2;
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .order-amount {
        font-weight: 800;
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.2rem;
    }
    
    .discount-info {
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 0.75rem;
        margin-top: var(--space-xs);
        font-weight: 600;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-xl);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        gap: var(--space-xs);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        white-space: nowrap;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .status-processing {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .status-shipped {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .status-delivered {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--radius-xl);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .badge-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    .order-date {
        color: var(--admin-secondary-600);
        white-space: nowrap;
        font-weight: 500;
    }
    
    .order-actions {
        display: flex;
        gap: var(--space-sm);
        align-items: center;
    }
    
    .action-btn {
        width: 45px;
        height: 45px;
        border: none;
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-fast);
        font-size: 1rem;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        transition: var(--transition-fast);
        transform: translate(-50%, -50%);
    }
    
    .action-btn:hover::before {
        width: 100%;
        height: 100%;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.view:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.4);
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .action-btn.delete:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 20px 40px rgba(239, 68, 68, 0.4);
    }
    
    .pagination-wrapper {
        padding: var(--space-2xl);
        background: linear-gradient(135deg, var(--admin-secondary-50), #f1f5f9);
        border-top: 2px solid var(--admin-secondary-200);
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: var(--space-sm);
        align-items: center;
        background: white;
        padding: var(--space-xl);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .page-link {
        padding: var(--space-lg) var(--space-xl);
        border: 2px solid var(--admin-secondary-200);
        border-radius: var(--radius-xl);
        color: var(--admin-secondary-600);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition-fast);
        min-width: 45px;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .page-link:hover {
        background: var(--admin-primary-50);
        border-color: var(--admin-primary-300);
        color: var(--admin-primary-600);
        transform: translateY(-2px);
    }
    
    .page-link.active {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        border-color: var(--admin-primary-600);
        color: white;
        box-shadow: var(--shadow-lg);
    }
    
    .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--space-3xl);
        color: var(--admin-secondary-500);
        background: white;
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        margin: var(--space-2xl) 0;
    }
    
    .empty-icon {
        font-size: 5rem;
        margin-bottom: var(--space-xl);
        opacity: 0.6;
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .empty-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: var(--space-md);
        color: var(--admin-secondary-700);
    }
    
    .empty-text {
        margin-bottom: var(--space-2xl);
        font-size: 1.125rem;
        color: var(--admin-secondary-600);
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .bulk-actions {
        display: none;
        align-items: center;
        gap: var(--space-lg);
        padding: var(--space-xl);
        background: linear-gradient(135deg, var(--admin-primary-50), #e0f2fe);
        border-bottom: 2px solid var(--admin-primary-200);
        flex-wrap: wrap;
    }
    
    .bulk-actions.show {
        display: flex;
    }
    
    .bulk-select {
        font-weight: 600;
        color: var(--admin-primary-700);
        font-size: 1rem;
    }
    
    .bulk-btn {
        padding: var(--space-md) var(--space-xl);
        border: 2px solid var(--admin-primary-300);
        background: white;
        color: var(--admin-primary-600);
        border-radius: var(--radius-xl);
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition-fast);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    
    .bulk-btn:hover {
        background: var(--admin-primary-600);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .orders-stats {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            max-width: none;
        }
        
        .filters-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
    }
    
    @media (max-width: 992px) {
        .orders-header {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
            padding: var(--space-xl);
        }
        
        .orders-stats {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .table-header {
            flex-direction: column;
            gap: var(--space-lg);
            align-items: stretch;
        }
        
        .bulk-actions {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-md);
        }
        
        .bulk-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Mobile Cards for better responsiveness */
    .mobile-cards {
        display: none;
        padding: var(--space-lg);
    }
    
    .order-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: var(--space-xl);
        margin-bottom: var(--space-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--admin-secondary-100);
        transition: var(--transition-fast);
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-2xl);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-lg);
        border-bottom: 2px solid var(--admin-secondary-100);
    }
    
    .card-body {
        display: flex;
        flex-direction: column;
        gap: var(--space-lg);
    }
    
    .card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--admin-secondary-100);
    }
    
    .card-row:last-child {
        border-bottom: none;
    }
    
    .card-label {
        font-weight: 600;
        color: var(--admin-secondary-600);
        font-size: 0.875rem;
        min-width: 80px;
    }
    
    .card-value {
        font-weight: 500;
        color: var(--admin-secondary-900);
        text-align: left;
        flex: 1;
    }
    
    @media (max-width: 768px) {
        .orders-header {
            padding: var(--space-lg);
        }
        
        .orders-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-md);
        }
        
        .stat-item {
            padding: var(--space-lg);
        }
        
        .filters-section {
            padding: var(--space-xl);
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        
        .filter-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        /* Hide table and show mobile cards */
        .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .pagination {
            padding: var(--space-lg);
            margin: 0 var(--space-lg);
        }
        
        .page-link {
            padding: var(--space-md) var(--space-lg);
            font-size: 0.8rem;
            min-width: 40px;
        }
    }
    
    @media (max-width: 480px) {
        .orders-header {
            margin-bottom: var(--space-xl);
        }
        
        .orders-stats {
            grid-template-columns: 1fr;
            gap: var(--space-sm);
        }
        
        .orders-title {
            font-size: 1.5rem;
            flex-direction: column;
            gap: var(--space-sm);
        }
        
        .filters-section {
            padding: var(--space-lg);
            margin: 0 -var(--space-lg) var(--space-xl) -var(--space-lg);
            border-radius: var(--radius-xl);
        }
        
        .filter-group {
            gap: var(--space-xs);
        }
        
        .filter-input {
            padding: var(--space-md) var(--space-lg);
        }
        
        .order-card {
            padding: var(--space-lg);
            margin: 0 -var(--space-lg) var(--space-lg) -var(--space-lg);
            border-radius: var(--radius-xl);
        }
        
        .card-header {
            flex-direction: column;
            gap: var(--space-md);
            align-items: stretch;
            text-align: center;
        }
        
        .card-row {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-sm);
            text-align: center;
        }
        
        .card-label {
            font-size: 0.8rem;
            min-width: auto;
        }
        
        .customer-info {
            justify-content: center;
            gap: var(--space-sm);
        }
        
        .customer-avatar {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }
        
        .order-actions {
            justify-content: center;
            gap: var(--space-md);
        }
        
        .action-btn {
            width: 50px;
            height: 50px;
            font-size: 1.1rem;
        }
        
        .pagination {
            flex-direction: column;
            gap: var(--space-sm);
            padding: var(--space-md);
        }
        
        .pagination > * {
            display: flex;
            gap: var(--space-xs);
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .mobile-cards {
            padding: 0;
        }
        
        .orders-table-container {
            margin: 0 -var(--space-lg);
            border-radius: var(--radius-xl);
        }
    }

    .order-checkbox:checked {
        accent-color: var(--admin-primary-500);
    }
    
    #selectAll:indeterminate {
        opacity: 0.5;
    }

    .alert {
        padding: var(--space-xl);
        border-radius: var(--radius-xl);
        margin-bottom: var(--space-xl);
        display: flex;
        align-items: center;
        gap: var(--space-md);
        font-weight: 600;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .alert-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .alert-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    /* RTL Improvements */
    .fas {
        margin-left: var(--space-xs);
        margin-right: 0;
    }
    
    .breadcrumb-item .fas {
        margin: 0 var(--space-xs);
    }
    
    /* Animation Classes */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Loading Spinner */
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Enhanced Hover Effects */
    .stat-item,
    .filter-group,
    .btn,
    .action-btn,
    .page-link {
        position: relative;
        overflow: hidden;
    }
    
    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Focus Styles */
    .filter-input:focus,
    .btn:focus,
    .action-btn:focus,
    .page-link:focus {
        outline: 2px solid var(--admin-primary-500);
        outline-offset: 2px;
    }
    
    /* Selection Styles */
    ::selection {
        background: var(--admin-primary-500);
        color: white;
    }
    
    /* Scrollbar Styles */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: var(--admin-secondary-100);
        border-radius: var(--radius-lg);
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
        border-radius: var(--radius-lg);
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        :root {
            --admin-secondary-50: #1e293b;
            --admin-secondary-100: #334155;
            --admin-secondary-200: #475569;
            --admin-secondary-700: #cbd5e1;
            --admin-secondary-900: #f1f5f9;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .filters-section,
        .orders-table-container {
            background: #1e293b;
            border-color: #334155;
        }
        
        .filter-input {
            background: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }
        
        .orders-table th {
            background: linear-gradient(135deg, #334155, #475569);
        }
    }
    
    /* Print Styles */
    @media print {
        .filter-actions,
        .order-actions,
        .export-btn,
        .bulk-actions {
            display: none !important;
        }
        
        .orders-header {
            background: none !important;
            color: #000 !important;
            box-shadow: none !important;
        }
        
        .orders-table-container {
            box-shadow: none !important;
            border: 1px solid #000;
        }
        
        .orders-table {
            font-size: 12px;
        }
        
        .page-link {
            display: none;
        }
    }
    
    /* High contrast mode */
    @media (prefers-contrast: high) {
        .btn,
        .status-badge,
        .badge,
        .action-btn {
            border: 2px solid currentColor;
        }
        
        .orders-table tbody tr:hover {
            background: #fff !important;
            border: 2px solid var(--admin-primary-500) !important;
        }
    }
    
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        
        .fade-in {
            opacity: 1;
            transform: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Orders Header -->
<div class="orders-header">
    <div>
        <h1 class="orders-title">
            <i class="fas fa-shopping-cart"></i>
            إدارة الطلبات
        </h1>
    </div>
    
    <div class="orders-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $orders->total() }}</div>
            <div class="stat-label">إجمالي الطلبات</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="stat-label">في الانتظار</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'delivered')->count() }}</div>
            <div class="stat-label">تم التسليم</div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="filters-section fade-in">
    <h2 class="filters-title">
        <i class="fas fa-filter"></i>
        تصفية الطلبات
    </h2>
    
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">رقم الطلب</label>
                <input 
                    type="text" 
                    name="order_id" 
                    class="filter-input" 
                    placeholder="البحث برقم الطلب..."
                    value="{{ request('order_id') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">العميل</label>
                <input 
                    type="text" 
                    name="customer" 
                    class="filter-input" 
                    placeholder="البحث باسم العميل..."
                    value="{{ request('customer') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">الحالة</label>
                <select name="status" class="filter-input">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">التاريخ من</label>
                <input 
                    type="date" 
                    name="date_from" 
                    class="filter-input"
                    value="{{ request('date_from') }}"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label">التاريخ إلى</label>
                <input 
                    type="date" 
                    name="date_to" 
                    class="filter-input"
                    value="{{ request('date_to') }}"
                >
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                تصفية
            </button>
            
            @if(request()->hasAny(['order_id', 'customer', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    مسح الفلاتر
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="orders-table-container fade-in">
    <div class="table-header">
        <h3 class="table-title">
            <i class="fas fa-list"></i>
            قائمة الطلبات
        </h3>
        
        <button class="export-btn" onclick="exportOrders()">
            <i class="fas fa-download"></i>
            تصدير CSV
        </button>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <span class="bulk-select">
            <span id="selectedCount">0</span> محدد
        </span>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('processing')">
            تحويل لقيد المعالجة
        </button>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('shipped')">
            تحويل لتم الشحن
        </button>
        
        <button class="bulk-btn" onclick="bulkUpdateStatus('delivered')">
            تحويل لتم التسليم
        </button>
        
        <button class="bulk-btn" onclick="bulkDelete()" style="border-color: var(--error-300); color: var(--error-600);">
            حذف المحدد
        </button>
    </div>
    
    @if(($orders ?? collect())->count() > 0)
        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>الدفع</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="order-id">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($order->user->name ?? 'ع', 0, 1)) }}
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $order->user->name ?? 'عميل غير محدد' }}</div>
                                        <div class="customer-email">{{ $order->user->email ?? 'بريد غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="order-amount">${{ number_format($order->total_amount ?? 0, 2) }}</div>
                                @if(($order->discount_amount ?? 0) > 0)
                                    <div class="discount-info">
                                        خصم: -${{ number_format($order->discount_amount, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                                    <i class="fas fa-{{ ($order->status ?? 'pending') == 'pending' ? 'clock' : (($order->status ?? 'pending') == 'processing' ? 'cog' : (($order->status ?? 'pending') == 'shipped' ? 'shipping-fast' : (($order->status ?? 'pending') == 'delivered' ? 'check' : 'times'))) }}"></i>
                                    @switch($order->status ?? 'pending')
                                        @case('pending') في الانتظار @break
                                        @case('processing') قيد المعالجة @break
                                        @case('shipped') تم الشحن @break
                                        @case('delivered') تم التسليم @break
                                        @case('cancelled') ملغي @break
                                        @default في الانتظار
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    @switch($order->payment_method ?? 'cash')
                                        @case('credit_card') بطاقة ائتمان @break
                                        @case('paypal') باي بال @break
                                        @case('cash') نقداً @break
                                        @case('bank_transfer') تحويل بنكي @break
                                        @default نقداً
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <div class="order-date">
                                    {{ $order->created_at->format('d M, Y') }}
                                    <br>
                                    <small style="color: var(--admin-secondary-500);">
                                        {{ $order->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="order-actions">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="action-btn view" title="عرض الطلب">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button class="action-btn delete" onclick="deleteOrder('{{ $order->id }}')" title="حذف الطلب">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: var(--space-3xl); color: var(--admin-secondary-500);">
                                <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: var(--space-lg); opacity: 0.5;"></i>
                                <br>
                                لا توجد طلبات تطابق معايير البحث
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards for Responsive -->
        <div class="mobile-cards">
            @forelse($orders as $order)
                <div class="order-card">
                    <div class="card-header">
                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" onchange="updateBulkActions()">
                        <a href="{{ route('admin.orders.show', $order) }}" class="order-id">
                            #{{ $order->id }}
                        </a>
                        <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                            <i class="fas fa-{{ ($order->status ?? 'pending') == 'pending' ? 'clock' : (($order->status ?? 'pending') == 'processing' ? 'cog' : (($order->status ?? 'pending') == 'shipped' ? 'shipping-fast' : (($order->status ?? 'pending') == 'delivered' ? 'check' : 'times'))) }}"></i>
                            @switch($order->status ?? 'pending')
                                @case('pending') في الانتظار @break
                                @case('processing') قيد المعالجة @break
                                @case('shipped') تم الشحن @break
                                @case('delivered') تم التسليم @break
                                @case('cancelled') ملغي @break
                                @default في الانتظار
                            @endswitch
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <div class="card-row">
                            <span class="card-label">العميل:</span>
                            <div class="card-value">
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($order->user->name ?? 'ع', 0, 1)) }}
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $order->user->name ?? 'عميل غير محدد' }}</div>
                                        <div class="customer-email">{{ $order->user->email ?? 'بريد غير محدد' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">المبلغ:</span>
                            <span class="card-value">
                                <div class="order-amount">${{ number_format($order->total_amount ?? 0, 2) }}</div>
                                @if(($order->discount_amount ?? 0) > 0)
                                    <div class="discount-info">
                                        خصم: -${{ number_format($order->discount_amount, 2) }}
                                    </div>
                                @endif
                            </span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">طريقة الدفع:</span>
                            <span class="badge badge-info">
                                @switch($order->payment_method ?? 'cash')
                                    @case('credit_card') بطاقة ائتمان @break
                                    @case('paypal') باي بال @break
                                    @case('cash') نقداً @break
                                    @case('bank_transfer') تحويل بنكي @break
                                    @default نقداً
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">التاريخ:</span>
                            <span class="card-value">
                                <div class="order-date">
                                    {{ $order->created_at->format('d M, Y') }}
                                    <br>
                                    <small style="color: var(--admin-secondary-500);">
                                        {{ $order->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">الإجراءات:</span>
                            <div class="card-value">
                                <div class="order-actions">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="action-btn view" title="عرض الطلب">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <button class="action-btn delete" onclick="deleteOrder('{{ $order->id }}')" title="حذف الطلب">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="empty-title">لا توجد طلبات</h3>
                    <p class="empty-text">لا توجد طلبات تطابق معايير البحث الحالية. جرب تعديل معايير البحث.</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                        عرض جميع الطلبات
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    @if($orders->onFirstPage())
                        <span class="page-link disabled">السابق</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="page-link">السابق</a>
                    @endif
                    
                    @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if($page == $orders->currentPage())
                            <span class="page-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="page-link">التالي</a>
                    @else
                        <span class="page-link disabled">التالي</span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="empty-title">لا توجد طلبات</h3>
            <p class="empty-text">لا توجد طلبات تطابق معايير البحث الحالية. جرب تعديل معايير البحث.</p>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                عرض جميع الطلبات
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let selectedOrders = [];
    
    // Toggle select all
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAll.checked;
        });
        
        updateBulkActions();
    }
    
    // Update bulk actions
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        selectedOrders = Array.from(checkboxes).map(function(cb) {
            return cb.value;
        });
        selectedCount.textContent = selectedOrders.length;
        
        if (selectedOrders.length > 0) {
            bulkActions.classList.add('show');
        } else {
            bulkActions.classList.remove('show');
        }
        
        // Update "select all" checkbox
        const allCheckboxes = document.querySelectorAll('.order-checkbox');
        const selectAll = document.getElementById('selectAll');
        
        if (selectedOrders.length === allCheckboxes.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else if (selectedOrders.length > 0) {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
    }
    
    // Bulk update status
    function bulkUpdateStatus(status) {
        if (selectedOrders.length === 0) {
            showNotification('يرجى تحديد طلبات للتحديث', 'warning');
            return;
        }
        
        const statusText = {
            'processing': 'قيد المعالجة',
            'shipped': 'تم الشحن',
            'delivered': 'تم التسليم'
        };
        
        if (!confirm('هل أنت متأكد من تحديث ' + selectedOrders.length + ' طلب إلى ' + statusText[status] + '؟')) {
            return;
        }
        
        var promises = selectedOrders.map(function(orderId) {
            return fetch('/admin/orders/' + orderId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            });
        });
        
        Promise.all(promises).then(function() {
            showNotification('تم تحديث الطلبات بنجاح', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        }).catch(function(error) {
            showNotification('خطأ في تحديث الطلبات', 'error');
        });
    }
    
    // Bulk delete
    function bulkDelete() {
        if (selectedOrders.length === 0) {
            showNotification('يرجى تحديد طلبات للحذف', 'warning');
            return;
        }
        
        if (!confirm('هل أنت متأكد من حذف ' + selectedOrders.length + ' طلب؟ لا يمكن التراجع عن هذا الإجراء.')) {
            return;
        }
        
        var promises = selectedOrders.map(function(orderId) {
            return fetch('/admin/orders/' + orderId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });
        
        Promise.all(promises).then(function() {
            showNotification('تم حذف الطلبات بنجاح', 'success');
            setTimeout(function() {
                location.reload();
            }, 1000);
        }).catch(function(error) {
            showNotification('خطأ في حذف الطلبات', 'error');
        });
    }
    
    // Delete single order
    function deleteOrder(orderId) {
        if (!confirm('هل أنت متأكد من حذف هذا الطلب؟ لا يمكن التراجع عن هذا الإجراء.')) {
            return;
        }
        
        fetch('/admin/orders/' + orderId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(function(response) {
            if (response.ok) {
                showNotification('تم حذف الطلب بنجاح', 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showNotification('خطأ في حذف الطلب', 'error');
            }
        }).catch(function(error) {
            showNotification('خطأ في حذف الطلب', 'error');
        });
    }
    
    // Export orders
    function exportOrders() {
        var params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        
        window.location.href = '{{ route("admin.orders.index") }}?' + params.toString();
    }
    
    // Show notification
    function showNotification(message, type) {
        if (type === undefined) {
            type = 'info';
        }
        
        var notification = document.createElement('div');
        notification.className = 'alert alert-' + type;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.left = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '350px';
        notification.style.transform = 'translateX(-100%)';
        notification.style.transition = 'transform 0.3s ease';
        
        var iconClass = 'info-circle';
        if (type === 'success') {
            iconClass = 'check-circle';
        } else if (type === 'error') {
            iconClass = 'exclamation-circle';
        } else if (type === 'warning') {
            iconClass = 'exclamation-triangle';
        }
        
        notification.innerHTML = '<i class="fas fa-' + iconClass + '"></i> ' + message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(function() {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Animate out
        setTimeout(function() {
            notification.style.transform = 'translateX(-100%)';
            setTimeout(function() {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, index) {
                if (entry.isIntersecting) {
                    setTimeout(function() {
                        entry.target.classList.add('visible');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        var fadeElements = document.querySelectorAll('.fade-in');
        fadeElements.forEach(function(el) {
            observer.observe(el);
        });
        
        // Add hover effects to cards
        document.querySelectorAll('.orders-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.005)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // Enhanced search functionality
        const searchInputs = document.querySelectorAll('.filter-input');
        searchInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 8px 20px rgba(59, 130, 246, 0.2)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
            });
        });
        
        // Add loading state to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.type === 'submit' || this.tagName === 'A') {
                    // Add loading spinner
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
                    this.disabled = true;
                    
                    // Restore after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (selectedOrders.length === 0) {
            location.reload();
        }
    }, 30000);
    
    // Add smooth scrolling to pagination
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    
    // Real-time order status updates (if you have websockets)
    // You can implement this with Laravel Echo + Pusher
    /*
    Echo.channel('orders')
        .listen('OrderStatusUpdated', (e) => {
            showNotification('تم تحديث حالة الطلب #' + e.order.id, 'info');
            // Update the specific row or reload page
        });
    */
</script>
@endpush