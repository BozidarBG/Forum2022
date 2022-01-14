<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Topic extends Component
{
    public $topic;
    public $type;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($topic, $type=null)
    {
        $this->topic=$topic;
        $this->type=$type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.topic');
    }
}
