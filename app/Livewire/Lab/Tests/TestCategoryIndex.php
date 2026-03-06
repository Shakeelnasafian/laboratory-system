<?php

namespace App\Livewire\Lab\Tests;

use App\Models\TestCategory;
use Livewire\Component;

class TestCategoryIndex extends Component
{
    public string $name        = '';
    public string $description = '';
    public bool   $showForm    = false;
    public ?int   $editingId   = null;

    public function openCreate(): void
    {
        $this->reset('name', 'description', 'editingId');
        $this->showForm = true;
    }

    public function edit(TestCategory $testCategory): void
    {
        $this->editingId   = $testCategory->id;
        $this->name        = $testCategory->name;
        $this->description = $testCategory->description ?? '';
        $this->showForm    = true;
    }

    public function save(): void
    {
        $this->validate(['name' => 'required|string|max:255']);

        if ($this->editingId) {
            TestCategory::find($this->editingId)->update([
                'name'        => $this->name,
                'description' => $this->description,
            ]);
        } else {
            TestCategory::create([
                'name'        => $this->name,
                'description' => $this->description,
            ]);
        }

        $this->reset('name', 'description', 'editingId', 'showForm');
        session()->flash('success', 'Category saved.');
    }

    public function delete(TestCategory $testCategory): void
    {
        $testCategory->delete();
        session()->flash('success', 'Category deleted.');
    }

    public function render()
    {
        return view('livewire.lab.tests.categories', [
            'categories' => TestCategory::withCount('tests')->latest()->get(),
        ])->layout('layouts.lab', ['title' => 'Test Categories']);
    }
}
