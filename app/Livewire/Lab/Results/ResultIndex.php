<?php

namespace App\Livewire\Lab\Results;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Result;
use Livewire\Component;
use Livewire\WithPagination;

class ResultIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    // Result entry modal
    public bool   $showModal    = false;
    public ?int   $orderItemId  = null;
    public string $value        = '';
    public string $unit         = '';
    public string $normal_range = '';
    public string $flag         = 'normal';
    public string $remarks      = '';

    public function openResultEntry(OrderItem $item): void
    {
        $this->orderItemId  = $item->id;
        $this->unit         = $item->test->unit ?? '';
        $this->normal_range = $item->test->normal_range ?? '';
        $existing           = $item->result;
        $this->value        = $existing?->value ?? '';
        $this->flag         = $existing?->flag ?? 'normal';
        $this->remarks      = $existing?->remarks ?? '';
        $this->showModal    = true;
    }

    public function saveResult(): void
    {
        $this->validate(['value' => 'required|string|max:255']);

        $item = OrderItem::find($this->orderItemId);

        Result::updateOrCreate(
            ['order_item_id' => $this->orderItemId],
            [
                'entered_by'   => auth()->id(),
                'value'        => $this->value,
                'unit'         => $this->unit,
                'normal_range' => $this->normal_range,
                'flag'         => $this->flag,
                'is_abnormal'  => in_array($this->flag, ['high', 'low', 'critical']),
                'remarks'      => $this->remarks,
            ]
        );

        $item->update(['status' => 'completed']);

        // Update order status if all items complete
        $order = $item->order;
        $allDone = $order->items()->where('status', '!=', 'completed')->doesntExist();
        if ($allDone) {
            $order->update(['status' => 'completed', 'completed_at' => now()]);
        }

        $this->reset('showModal', 'orderItemId', 'value', 'unit', 'normal_range', 'flag', 'remarks');
        session()->flash('success', 'Result saved.');
    }

    public function verify(OrderItem $item): void
    {
        $item->result?->update([
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);
        session()->flash('success', 'Result verified.');
    }

    public function render()
    {
        $labId = auth()->user()->lab_id;

        $items = OrderItem::with(['order.patient', 'test', 'result'])
            ->whereHas('order', fn($q) => $q
                ->where('lab_id', $labId)
                ->when($this->search, fn($q) => $q
                    ->where(function($q) {
                        $q->where('order_number', 'like', "%{$this->search}%")
                          ->orWhereHas('patient', fn($p) => $p->where('name', 'like', "%{$this->search}%"));
                    })))
            ->when($this->status === 'pending', fn($q) => $q->where('status', 'pending'))
            ->when($this->status === 'completed', fn($q) => $q->where('status', 'completed'))
            ->latest()
            ->paginate(15);

        return view('livewire.lab.results.index', compact('items'))
            ->layout('layouts.lab', ['title' => 'Test Results']);
    }
}
