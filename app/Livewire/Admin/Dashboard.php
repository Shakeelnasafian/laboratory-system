<?php

namespace App\Livewire\Admin;

use App\Models\Lab;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalLabs'    => Lab::count(),
            'activeLabs'   => Lab::where('is_active', true)->count(),
            'inactiveLabs' => Lab::where('is_active', false)->count(),
            'totalUsers'   => User::whereNotNull('lab_id')->count(),
            'recentLabs'   => Lab::latest()->take(5)->get(),
        ])->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
