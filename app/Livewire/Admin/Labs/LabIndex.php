<?php

namespace App\Livewire\Admin\Labs;

use App\Models\Lab;
use Livewire\Component;
use Livewire\WithPagination;

class LabIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleStatus(Lab $lab): void
    {
        $lab->update(['is_active' => !$lab->is_active]);
        session()->flash('success', "Lab {$lab->name} has been " . ($lab->is_active ? 'deactivated' : 'activated') . '.');
    }

    public function render()
    {
        $labs = Lab::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->status !== '', fn($q) => $q->where('is_active', $this->status === 'active'))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.labs.index', compact('labs'))
            ->layout('layouts.admin', ['title' => 'Manage Labs']);
    }
}
