<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NoEntries extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
		public string $type
	)
    {
        if ($type == "payment") {
			$this->headerText = "Brak składek";
			$this->description = "W twojej klasie albo nie utworzono jeszcze żadnych składek, albo już je wszystkie opłaciłeś.";
		} else if ($type == "announcement") {
			$this->headerText = "Brak ogłoszeń";
			$this->description = "W twojej klasie jeszcze nic nie ogłoszono.";
		} else if ($type == "contest") {
			$this->headerText = "Brak konkursów";
			$this->description = "W twojej szkole obecnie nie ma żadnych aktywnych konkursów.";
		} else {
			$this->headerText = "Brak pytań";
			$this->description = "Nikt jeszcze nie zadał pytania samorządowi szkolnemu. Bądź pierwszy!";
		}
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.no-entries', [
			"headerText" => $this->headerText,
			"description" => $this->description
		]);
    }
}
