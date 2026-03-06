<?php

namespace App\Livewire\Lab\Patients;

use App\Models\Patient;
use Livewire\Component;

class PatientCreate extends Component
{
    public string $name        = '';
    public string $cnic        = '';
    public string $phone       = '';
    public string $email       = '';
    public string $gender      = 'male';
    public string $age         = '';
    public string $age_unit    = 'years';
    public string $address     = '';
    public string $referred_by = '';

    public function save(): void
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'cnic'        => 'nullable|string|max:15',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email',
            'gender'      => 'required|in:male,female,other',
            'age'         => 'nullable|integer|min:0|max:150',
            'age_unit'    => 'required|in:years,months,days',
            'address'     => 'nullable|string',
            'referred_by' => 'nullable|string|max:255',
        ]);

        $patient = Patient::create([
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

        session()->flash('success', "Patient {$patient->name} registered successfully.");
        $this->redirect(route('lab.patients.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.lab.patients.create')
            ->layout('layouts.lab', ['title' => 'Register Patient']);
    }
}
