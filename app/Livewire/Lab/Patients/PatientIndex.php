<?php

namespace App\Livewire\Lab\Patients;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class PatientIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $gender = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $patients = Patient::query()
            ->when($this->search, fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('cnic', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%")
                ->orWhere('patient_id', 'like', "%{$this->search}%"))
            ->when($this->gender, fn($q) => $q->where('gender', $this->gender))
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('livewire.lab.patients.index', compact('patients'))
            ->layout('layouts.lab', ['title' => 'Patients']);
    }
}
