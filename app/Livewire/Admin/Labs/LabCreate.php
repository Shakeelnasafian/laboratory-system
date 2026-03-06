<?php

namespace App\Livewire\Admin\Labs;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class LabCreate extends Component
{
    // Lab fields
    public string $name            = '';
    public string $email           = '';
    public string $phone           = '';
    public string $address         = '';
    public string $city            = '';
    public string $license_number  = '';
    public string $owner_name      = '';
    public string $header_text     = '';
    public string $footer_text     = '';

    // Admin user fields
    public string $admin_name     = '';
    public string $admin_email    = '';
    public string $admin_password = '';

    public function createLab(): void
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'owner_name'     => 'nullable|string|max:255',
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8',
        ]);

        $lab = Lab::create([
            'name'           => $this->name,
            'slug'           => Str::slug($this->name) . '-' . Str::random(4),
            'email'          => $this->email,
            'phone'          => $this->phone,
            'address'        => $this->address,
            'city'           => $this->city,
            'license_number' => $this->license_number,
            'owner_name'     => $this->owner_name,
            'header_text'    => $this->header_text,
            'footer_text'    => $this->footer_text,
            'is_active'      => true,
        ]);

        $admin = User::create([
            'lab_id'   => $lab->id,
            'name'     => $this->admin_name,
            'email'    => $this->admin_email,
            'password' => bcrypt($this->admin_password),
        ]);

        $admin->assignRole('lab_admin');

        session()->flash('success', "Lab '{$lab->name}' created successfully.");
        $this->redirect(route('admin.labs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.labs.create')
            ->layout('layouts.admin', ['title' => 'Create New Lab']);
    }
}
