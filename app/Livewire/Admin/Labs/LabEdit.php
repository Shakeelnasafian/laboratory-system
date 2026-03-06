<?php

namespace App\Livewire\Admin\Labs;

use App\Models\Lab;
use Livewire\Component;

class LabEdit extends Component
{
    public Lab $lab;

    public string $name           = '';
    public string $email          = '';
    public string $phone          = '';
    public string $address        = '';
    public string $city           = '';
    public string $license_number = '';
    public string $owner_name     = '';
    public string $header_text    = '';
    public string $footer_text    = '';
    public bool   $is_active      = true;

    public function mount(Lab $lab): void
    {
        $this->lab            = $lab;
        $this->name           = $lab->name;
        $this->email          = $lab->email ?? '';
        $this->phone          = $lab->phone ?? '';
        $this->address        = $lab->address ?? '';
        $this->city           = $lab->city ?? '';
        $this->license_number = $lab->license_number ?? '';
        $this->owner_name     = $lab->owner_name ?? '';
        $this->header_text    = $lab->header_text ?? '';
        $this->footer_text    = $lab->footer_text ?? '';
        $this->is_active      = $lab->is_active;
    }

    public function updateLab(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $this->lab->update([
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'city'           => $this->city,
            'license_number' => $this->license_number,
            'owner_name'     => $this->owner_name,
            'header_text'    => $this->header_text,
            'footer_text'    => $this->footer_text,
            'is_active'      => $this->is_active,
        ]);

        session()->flash('success', 'Lab updated successfully.');
        $this->redirect(route('admin.labs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.labs.edit')
            ->layout('layouts.admin', ['title' => 'Edit Lab']);
    }
}
