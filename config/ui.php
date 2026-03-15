<?php

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Result;
use App\Models\User;

return [
    'badges' => [
        'order' => [
            Order::STATUS_PENDING => ['label' => Order::STATUSES[Order::STATUS_PENDING], 'tone' => 'warning'],
            Order::STATUS_SAMPLE_COLLECTED => ['label' => Order::STATUSES[Order::STATUS_SAMPLE_COLLECTED], 'tone' => 'info'],
            Order::STATUS_PROCESSING => ['label' => Order::STATUSES[Order::STATUS_PROCESSING], 'tone' => 'primary'],
            Order::STATUS_COMPLETED => ['label' => Order::STATUSES[Order::STATUS_COMPLETED], 'tone' => 'success'],
            Order::STATUS_CANCELLED => ['label' => Order::STATUSES[Order::STATUS_CANCELLED], 'tone' => 'danger'],
        ],
        'order_item' => [
            OrderItem::STATUS_PENDING => ['label' => OrderItem::STATUSES[OrderItem::STATUS_PENDING], 'tone' => 'warning'],
            OrderItem::STATUS_SAMPLE_COLLECTED => ['label' => OrderItem::STATUSES[OrderItem::STATUS_SAMPLE_COLLECTED], 'tone' => 'info'],
            OrderItem::STATUS_PROCESSING => ['label' => OrderItem::STATUSES[OrderItem::STATUS_PROCESSING], 'tone' => 'primary'],
            OrderItem::STATUS_COMPLETED => ['label' => OrderItem::STATUSES[OrderItem::STATUS_COMPLETED], 'tone' => 'success'],
        ],
        'payment' => [
            Invoice::STATUS_PAID => ['label' => Invoice::STATUSES[Invoice::STATUS_PAID], 'tone' => 'success'],
            Invoice::STATUS_PARTIAL => ['label' => Invoice::STATUSES[Invoice::STATUS_PARTIAL], 'tone' => 'warning'],
            Invoice::STATUS_UNPAID => ['label' => Invoice::STATUSES[Invoice::STATUS_UNPAID], 'tone' => 'danger'],
        ],
        'result' => [
            Result::STATUS_DRAFT => ['label' => Result::STATUSES[Result::STATUS_DRAFT], 'tone' => 'warning'],
            Result::STATUS_VERIFIED => ['label' => Result::STATUSES[Result::STATUS_VERIFIED], 'tone' => 'info'],
            Result::STATUS_RELEASED => ['label' => Result::STATUSES[Result::STATUS_RELEASED], 'tone' => 'success'],
        ],
        'queue' => [
            'released' => ['label' => 'Released', 'tone' => 'success'],
            'ready' => ['label' => 'Ready', 'tone' => 'info'],
            'in_progress' => ['label' => 'In Progress', 'tone' => 'neutral'],
            'pending_verification' => ['label' => 'Pending Verification', 'tone' => 'warning'],
            'awaiting_collection' => ['label' => 'Awaiting Collection', 'tone' => 'warning'],
            'rejected_for_recollect' => ['label' => 'Rejected for Recollect', 'tone' => 'danger'],
            'waiting_for_draft' => ['label' => 'Waiting for Draft', 'tone' => 'neutral'],
        ],
        'signal' => [
            'critical' => ['label' => 'Critical', 'tone' => 'danger'],
            'urgent' => ['label' => 'Urgent', 'tone' => 'danger'],
            'active' => ['label' => 'Active', 'tone' => 'success'],
            'inactive' => ['label' => 'Inactive', 'tone' => 'danger'],
        ],
        'role' => [
            User::ROLE_LAB_ADMIN => ['label' => 'Lab Admin', 'tone' => 'primary'],
            User::ROLE_LAB_INCHARGE => ['label' => 'Lab Incharge', 'tone' => 'info'],
            User::ROLE_RECEPTIONIST => ['label' => 'Receptionist', 'tone' => 'neutral'],
            User::ROLE_TECHNICIAN => ['label' => 'Technician', 'tone' => 'primary'],
            User::ROLE_SUPERADMIN => ['label' => 'Super Admin', 'tone' => 'primary'],
        ],
    ],
    'dashboard_metrics' => [
        'today_orders' => ['accent' => '#2563eb', 'soft' => 'rgba(37, 99, 235, 0.14)'],
        'today_patients' => ['accent' => '#1d4ed8', 'soft' => 'rgba(59, 130, 246, 0.14)'],
        'pending_collection' => ['accent' => '#d97706', 'soft' => 'rgba(245, 158, 11, 0.16)'],
        'processing_items' => ['accent' => '#4338ca', 'soft' => 'rgba(99, 102, 241, 0.14)'],
        'overdue_items' => ['accent' => '#dc2626', 'soft' => 'rgba(239, 68, 68, 0.15)'],
        'completed_items' => ['accent' => '#059669', 'soft' => 'rgba(16, 185, 129, 0.15)'],
        'today_revenue' => ['accent' => '#0f766e', 'soft' => 'rgba(20, 184, 166, 0.14)'],
        'total_patients' => ['accent' => '#0f172a', 'soft' => 'rgba(148, 163, 184, 0.16)'],
    ],
    'theme' => [
        'action' => [
            'primary' => '#2563eb',
            'primary_hover' => '#1d4ed8',
            'primary_text' => '#ffffff',
            'success' => '#059669',
            'success_hover' => '#047857',
            'success_text' => '#ffffff',
        ],
        'link' => [
            'primary' => '#2563eb',
            'hover' => '#1d4ed8',
        ],
        'badge' => [
            'primary' => ['bg' => '#eef2ff', 'fg' => '#4338ca'],
            'info' => ['bg' => '#dbeafe', 'fg' => '#1d4ed8'],
            'success' => ['bg' => '#dcfce7', 'fg' => '#15803d'],
            'warning' => ['bg' => '#fef3c7', 'fg' => '#b45309'],
            'danger' => ['bg' => '#fee2e2', 'fg' => '#b91c1c'],
            'neutral' => ['bg' => '#f1f5f9', 'fg' => '#475569'],
        ],
        'dashboard' => [
            'fallback' => [
                'accent' => '#475569',
                'soft' => 'rgba(148, 163, 184, 0.16)',
            ],
            'card_border' => 'rgba(226, 232, 240, 0.9)',
            'card_glow' => 'transparent',
            'card_top' => 'rgba(255, 255, 255, 0.98)',
            'card_bottom' => 'rgba(248, 250, 252, 0.94)',
            'card_shadow' => '0 18px 38px rgba(15, 23, 42, 0.06)',
            'card_shadow_hover' => '0 24px 44px rgba(15, 23, 42, 0.1)',
            'divider' => 'rgba(148, 163, 184, 0.22)',
            'label' => 'rgb(51 65 85)',
            'value' => 'rgb(2 6 23)',
            'subtitle' => 'rgb(100 116 139)',
            'chart_top' => 'rgba(248, 250, 252, 0.95)',
            'chart_bottom' => 'rgba(255, 255, 255, 0.72)',
            'grid_strong' => 'rgba(148, 163, 184, 0.18)',
            'grid_soft' => 'rgba(148, 163, 184, 0.10)',
            'surface_shadow' => '0 18px 40px rgba(15, 23, 42, 0.06)',
        ],
        'report' => [
            'body_text' => '#333333',
            'primary' => '#1e40af',
            'muted' => '#666666',
            'label' => '#555555',
            'border' => '#dddddd',
            'row_alt' => '#f8f9fa',
            'danger' => '#dc2626',
            'info' => '#2563eb',
            'footer' => '#888888',
            'meta_surface' => '#f0f4ff',
        ],
    ],
];
