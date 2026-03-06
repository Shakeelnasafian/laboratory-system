<?php

namespace App\Livewire\Lab\Orders;

use App\Models\Order;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['patient', 'items.test', 'items.result', 'invoice', 'createdBy']);
    }

    public function updateStatus(string $status): void
    {
        $this->order->update(['status' => $status]);
        if ($status === 'sample_collected') $this->order->update(['collected_at' => now()]);
        if ($status === 'completed') $this->order->update(['completed_at' => now()]);
        $this->order->refresh();
        session()->flash('success', 'Order status updated.');
    }

    public function printReport()
    {
        return redirect()->route('lab.orders.report', $this->order);
    }

    public function render()
    {
        return view('livewire.lab.orders.show')
            ->layout('layouts.lab', ['title' => 'Order #' . $this->order->order_number]);
    }
}
