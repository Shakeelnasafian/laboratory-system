<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $label;
    public string $tone;

    public function __construct(
        public string $type,
        public string $status,
        ?string $label = null,
    ) {
        $config = config("ui.badges.{$this->type}.{$this->status}", []);

        if (empty($config) && app()->isLocal()) {
            report(new \InvalidArgumentException(
                "StatusBadge: no config found for type=\"{$this->type}\" status=\"{$this->status}\". Add it to config/ui.php or pass a label override."
            ));
        }

        $this->label = $label ?? $config['label'] ?? Str::headline(str_replace('_', ' ', $this->status));
        $this->tone = $config['tone'] ?? 'neutral';
    }

    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
