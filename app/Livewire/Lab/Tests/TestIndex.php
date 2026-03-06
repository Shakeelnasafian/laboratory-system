<?php

namespace App\Livewire\Lab\Tests;

use App\Models\Test;
use App\Models\TestCategory;
use Livewire\Component;
use Livewire\WithPagination;

class TestIndex extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $category_id = '';
    public bool   $showForm    = false;
    public ?int   $editingId   = null;

    // Form fields
    public string $name                = '';
    public string $short_name          = '';
    public string $code                = '';
    public string $price               = '';
    public string $unit                = '';
    public string $normal_range        = '';
    public string $normal_range_male   = '';
    public string $normal_range_female = '';
    public string $sample_type         = '';
    public string $turnaround_hours    = '24';
    public string $form_category_id    = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset('name', 'short_name', 'code', 'price', 'unit', 'normal_range',
            'normal_range_male', 'normal_range_female', 'sample_type', 'turnaround_hours',
            'form_category_id', 'editingId');
        $this->showForm = true;
    }

    public function edit(Test $test): void
    {
        $this->editingId           = $test->id;
        $this->name                = $test->name;
        $this->short_name          = $test->short_name ?? '';
        $this->code                = $test->code ?? '';
        $this->price               = (string) $test->price;
        $this->unit                = $test->unit ?? '';
        $this->normal_range        = $test->normal_range ?? '';
        $this->normal_range_male   = $test->normal_range_male ?? '';
        $this->normal_range_female = $test->normal_range_female ?? '';
        $this->sample_type         = $test->sample_type ?? '';
        $this->turnaround_hours    = (string) $test->turnaround_hours;
        $this->form_category_id    = (string) ($test->category_id ?? '');
        $this->showForm            = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'turnaround_hours' => 'required|integer|min:1',
        ]);

        $data = [
            'name'                => $this->name,
            'short_name'          => $this->short_name,
            'code'                => $this->code,
            'price'               => $this->price,
            'unit'                => $this->unit,
            'normal_range'        => $this->normal_range,
            'normal_range_male'   => $this->normal_range_male,
            'normal_range_female' => $this->normal_range_female,
            'sample_type'         => $this->sample_type,
            'turnaround_hours'    => $this->turnaround_hours,
            'category_id'         => $this->form_category_id ?: null,
        ];

        $this->editingId ? Test::find($this->editingId)->update($data) : Test::create($data);

        $this->reset('showForm', 'editingId');
        session()->flash('success', 'Test saved successfully.');
    }

    public function toggleActive(Test $test): void
    {
        $test->update(['is_active' => !$test->is_active]);
    }

    public function render()
    {
        $tests = Test::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->category_id, fn($q) => $q->where('category_id', $this->category_id))
            ->paginate(15);

        return view('livewire.lab.tests.index', [
            'tests'      => $tests,
            'categories' => TestCategory::all(),
        ])->layout('layouts.lab', ['title' => 'Test Catalog']);
    }
}
