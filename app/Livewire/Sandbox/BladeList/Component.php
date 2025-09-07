<?php

namespace App\Livewire\Sandbox\BladeList;

use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    public $blades = [];

    public function render()
    {
        return view('700-page-sandbox.706-livewire-blade-list');
    }

    public function loadBlades()
    {
        // 구현필요
    }

    public function deleteBlade($id)
    {
        // 구현필요
    }

    public function previewBlade($id)
    {
        // 구현필요
    }
}