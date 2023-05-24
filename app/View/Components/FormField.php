<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormField extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $title,
        public string $name,
        public string $placeholder='',
        public string $class='',
        public string $type='text',
        public bool $required=true
    )
    {
        if(empty($this->placeholder)) {
            $this->placeholder = $this->title;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-field');
    }
}
