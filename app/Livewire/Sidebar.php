<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $showSubmenu =[];
    public function toggleSubmenu($rowId)
    {
        if (isset($this->showSubmenu[$rowId])) {
            $this->showSubmenu[$rowId] = !$this->showSubmenu[$rowId];
        } else {
            $this->showSubmenu[$rowId] = true;
        }
    }
    public function render()
    {
        return view('livewire.sidebar');
    }
}
