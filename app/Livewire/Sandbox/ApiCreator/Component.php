<?php

namespace App\Livewire\Sandbox\ApiCreator;

use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    public $apiCode = '';
    public $apiName = '';
    public $apiDescription = '';

    public function render()
    {
        return view('700-page-sandbox.700-livewire-api-creator');
    }

    public function saveApi()
    {
        // 구현필요
    }

    public function testApi()
    {
        // 구현필요
    }
}