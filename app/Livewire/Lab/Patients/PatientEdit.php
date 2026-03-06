<?php

namespace App\Livewire\Lab\Patients;

use App\Models\Patient;
use Livewire\Component;

class PatientEdit extends Component
{
    public Patient $patient;

    public string $name        = '';
    public string $cnic        = '';
    public string $phone       = '';
    public string $email       = '';
    public string $gender      = 'male';
    public string $age         = '';
    public string $age_unit    = 'years';
    public string $address     = '';
    public string $referred_by = '';

    public function mount(Patient $patient): void
    {
        $this->patient     = $patient;
        $this->name        = $patient->name;
        $this->cnic        = $patient->cnic ?? '';
        $this->phone       = $patient->phone ?? '';
        $this->email       = $patient->email ?? '';
        $this->gender      = $patient->gender;
        $this->age         = (string) ($patient->age ?? '');
        $this->age_unit    = $patient->age_unit;
        $this->address     = $patient->address ?? '';
        $this->referred_by = $patient->referred_by ?? '';
    }

    public function update(): void
    {
        $this->validate([
            'name'     => 'required|string|max:255',
            'gender'   => 'required|in:male,female,other',
            'age'      => 'nullable|integer|min:0',
            'age_unit' => 'required|in:years,months,days',
        ]);

        $this->patient->update([
            'name'        => $this->name,
            'cnic'        => $this->cnic,
            'phone'       => $this->phone,
            'email'       => $this->email,
            'gender'      => $this->gender,
            'age'         => $this->age ?: null,
            'age_unit'    => $this->age_unit,
            'address'     => $this->address,
            'referred_by' => $this->referred_by,
        ]);

        session()->flash('success', 'Patient updated successfully.');
        $this->redirect(route('lab.patients.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.lab.patients.edit')
            ->layout('layouts.lab', ['title' => 'Edit Patient']);
    }
}
