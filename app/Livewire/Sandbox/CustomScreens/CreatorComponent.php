<?php

namespace App\Livewire\Sandbox\CustomScreens;

use Livewire\Component;

class CreatorComponent extends Component
{
    public $edit = null;
    
    public function mount($edit = null)
    {
        $this->edit = $edit;
    }

    public function render()
    {
        return view('sandbox.custom-screens.creator-component', [
            'edit' => $this->edit
        ]);
    }
}
