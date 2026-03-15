<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ChangelogPage extends Component
{
    public function render(): View
    {
        $rawEntries = config('changelog.entries', []);

        $entries = collect($rawEntries)->map(function (array $entry) {
            if ($entry['type'] === 'workspace') {
                $entry['date'] = now()->format('F d, Y');
            }
            return $entry;
        })->all();

        $commitCount = collect($entries)->where('type', 'commit')->count();
        $workspaceChanges = collect($entries)->where('type', 'workspace')->count();

        $layout = auth()->user()?->hasRole('superadmin') ? 'layouts.admin' : 'layouts.lab';

        return view('livewire.changelog-page', [
            'entries' => $entries,
            'commitCount' => $commitCount,
            'workspaceChanges' => $workspaceChanges,
            'lastUpdated' => now()->format('F d, Y'),
        ])->layout($layout, ['title' => 'Change Log']);
    }
}
