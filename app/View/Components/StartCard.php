<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StartCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
		public string $name,
		public string $imageFile,
		public string $url
	)
    {
		//
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.start-card', [
			"name" => $this->name,
			"imageFile" => $this->imageFile,
			"url" => $this->url
		]);
    }
}
