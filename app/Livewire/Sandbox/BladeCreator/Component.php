<?php

namespace App\Livewire\Sandbox\BladeCreator;

use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    public $bladeCode = '';
    public $bladeName = '';
    public $bladeDescription = '';

    public function render()
    {
        return view('700-page-sandbox.705-livewire-blade-creator');
    }

    public function saveBlade()
    {
        // 구현필요
    }

    public function previewBlade()
    {
        // 구현필요
    }
}