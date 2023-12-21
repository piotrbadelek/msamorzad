<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Message extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
		public int $id,
		public string $question,
		public $response = null,
		public $username = null
	)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.message');
    }
}
