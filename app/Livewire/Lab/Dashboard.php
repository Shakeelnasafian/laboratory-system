<?php

namespace App\Livewire\Lab;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Patient;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $todayOrders    = Order::whereDate('created_at', today())->count();
        $todayPatients  = Patient::whereDate('created_at', today())->count();
        $pendingOrders  = Order::whereIn('status', ['pending', 'sample_collected', 'processing'])->count();
        $todayRevenue   = Invoice::whereDate('created_at', today())->sum('paid_amount');
        $totalPatients  = Patient::count();
        $completedToday = Order::where('status', 'completed')->whereDate('updated_at', today())->count();
        $recentOrders   = Order::with(['patient', 'items.test'])
            ->latest()->take(8)->get();

        return view('livewire.lab.dashboard', compact(
            'todayOrders', 'todayPatients', 'pendingOrders',
            'todayRevenue', 'totalPatients', 'completedToday', 'recentOrders'
        ))->layout('layouts.lab', ['title' => 'Dashboard']);
    }
}
