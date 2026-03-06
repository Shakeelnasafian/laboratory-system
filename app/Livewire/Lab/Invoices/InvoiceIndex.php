<?php

namespace App\Livewire\Lab\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceIndex extends Component
{
    use WithPagination;

    public string $search         = '';
    public string $payment_status = '';

    public function markPaid(Invoice $invoice): void
    {
        $invoice->update([
            'paid_amount'    => $invoice->total,
            'balance'        => 0,
            'payment_status' => 'paid',
        ]);
        session()->flash('success', 'Invoice marked as paid.');
    }

    public function render()
    {
        $invoices = Invoice::with(['order.patient'])
            ->when($this->search, fn($q) => $q
                ->where('invoice_number', 'like', "%{$this->search}%")
                ->orWhereHas('order.patient', fn($p) => $p->where('name', 'like', "%{$this->search}%")))
            ->when($this->payment_status, fn($q) => $q->where('payment_status', $this->payment_status))
            ->latest()
            ->paginate(15);

        $todayTotal    = Invoice::whereDate('created_at', today())->sum('total');
        $todayCollected = Invoice::whereDate('created_at', today())->sum('paid_amount');
        $outstanding   = Invoice::where('payment_status', '!=', 'paid')->sum('balance');

        return view('livewire.lab.invoices.index', compact('invoices', 'todayTotal', 'todayCollected', 'outstanding'))
            ->layout('layouts.lab', ['title' => 'Billing & Invoices']);
    }
}
