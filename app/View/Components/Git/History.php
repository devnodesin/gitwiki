<?php

namespace App\View\Components\Git;

use Illuminate\View\Component;

class History extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  array<int, array<string, mixed>>  $history
     * @return void
     */
    public function __construct(
        public array $history,
        public ?string $gitRemote,
        public ?string $changes
    ) {}

    public function render()
    {
        return view('components.git.history');
    }
}
