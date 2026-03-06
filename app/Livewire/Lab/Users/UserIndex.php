<?php

namespace App\Livewire\Lab\Users;

use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public bool   $showForm  = false;
    public ?int   $editingId = null;
    public string $name      = '';
    public string $email     = '';
    public string $phone     = '';
    public string $password  = '';
    public string $role      = 'receptionist';

    public function openCreate(): void
    {
        $this->reset('name', 'email', 'phone', 'password', 'editingId');
        $this->role     = 'receptionist';
        $this->showForm = true;
    }

    public function edit(User $user): void
    {
        $this->editingId = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->phone     = $user->phone ?? '';
        $this->password  = '';
        $this->role      = $user->roles->first()?->name ?? 'receptionist';
        $this->showForm  = true;
    }

    public function save(): void
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->editingId ? ",{$this->editingId}" : ''),
            'role'  => 'required|in:lab_admin,lab_incharge,receptionist,technician',
        ];
        if (!$this->editingId) $rules['password'] = 'required|min:8';
        $this->validate($rules);

        if ($this->editingId) {
            $user = User::find($this->editingId);
            $user->update(array_filter([
                'name'     => $this->name,
                'email'    => $this->email,
                'phone'    => $this->phone,
                'password' => $this->password ? bcrypt($this->password) : null,
            ]));
            $user->syncRoles([$this->role]);
        } else {
            $user = User::create([
                'lab_id'   => auth()->user()->lab_id,
                'name'     => $this->name,
                'email'    => $this->email,
                'phone'    => $this->phone,
                'password' => bcrypt($this->password),
            ]);
            $user->assignRole($this->role);
        }

        $this->reset('showForm', 'editingId', 'name', 'email', 'phone', 'password');
        session()->flash('success', 'User saved.');
    }

    public function toggleActive(User $user): void
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        $users = User::with('roles')
            ->where('lab_id', auth()->user()->lab_id)
            ->where('id', '!=', auth()->id())
            ->get();

        return view('livewire.lab.users.index', compact('users'))
            ->layout('layouts.lab', ['title' => 'Staff Users']);
    }
}
