<?php

namespace App\Livewire\Lab\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $date   = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['status' => $status]);
        if ($status === 'sample_collected') $order->update(['collected_at' => now()]);
        if ($status === 'completed') $order->update(['completed_at' => now()]);
    }

    public function render()
    {
        $orders = Order::with(['patient', 'items', 'invoice'])
            ->when($this->search, fn($q) => $q
                ->where('order_number', 'like', "%{$this->search}%")
                ->orWhereHas('patient', fn($p) => $p->where('name', 'like', "%{$this->search}%")))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->date, fn($q) => $q->whereDate('created_at', $this->date))
            ->latest()
            ->paginate(15);

        return view('livewire.lab.orders.index', compact('orders'))
            ->layout('layouts.lab', ['title' => 'Orders']);
    }
}
