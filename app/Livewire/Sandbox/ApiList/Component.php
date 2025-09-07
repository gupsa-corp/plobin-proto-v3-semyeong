<?php

namespace App\Livewire\Sandbox\ApiList;

use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    public $apis = [];

    public function render()
    {
        return view('700-page-sandbox.704-livewire-list');
    }

    public function loadApis()
    {
        // 구현필요
    }

    public function deleteApi($id)
    {
        // 구현필요
    }

    public function testApi($id)
    {
        // 구현필요
    }
}