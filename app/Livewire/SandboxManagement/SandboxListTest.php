<?php

namespace App\Livewire\SandboxManagement;

use Livewire\Component;

class SandboxListTest extends Component
{
    public $testMessage = 'Livewire 컴포넌트가 정상 작동 중입니다!';
    
    public function render()
    {
        return view('livewire.sandbox-management.sandbox-list-test');
    }
}