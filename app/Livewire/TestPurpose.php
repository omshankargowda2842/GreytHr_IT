<?php

namespace App\Livewire;

use Livewire\Component;

class TestPurpose extends Component
{
    public function test(){
        return redirect()->route('requests');
    }
    public function render()
    {
        return view('livewire.test-purpose');
    }
}
