<?php

namespace App\Livewire\Lab\Settings;

use Livewire\Component;

class LabSettings extends Component
{
    public string $name         = '';
    public string $phone        = '';
    public string $email        = '';
    public string $address      = '';
    public string $city         = '';
    public string $owner_name   = '';
    public string $license_number = '';
    public string $header_text  = '';
    public string $footer_text  = '';

    public function mount(): void
    {
        $lab = auth()->user()->lab;
        $this->name            = $lab->name;
        $this->phone           = $lab->phone ?? '';
        $this->email           = $lab->email ?? '';
        $this->address         = $lab->address ?? '';
        $this->city            = $lab->city ?? '';
        $this->owner_name      = $lab->owner_name ?? '';
        $this->license_number  = $lab->license_number ?? '';
        $this->header_text     = $lab->header_text ?? '';
        $this->footer_text     = $lab->footer_text ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        auth()->user()->lab->update([
            'name'           => $this->name,
            'phone'          => $this->phone,
            'email'          => $this->email,
            'address'        => $this->address,
            'city'           => $this->city,
            'owner_name'     => $this->owner_name,
            'license_number' => $this->license_number,
            'header_text'    => $this->header_text,
            'footer_text'    => $this->footer_text,
        ]);

        session()->flash('success', 'Lab settings updated.');
    }

    public function render()
    {
        return view('livewire.lab.settings.index')
            ->layout('layouts.lab', ['title' => 'Lab Settings']);
    }
}
