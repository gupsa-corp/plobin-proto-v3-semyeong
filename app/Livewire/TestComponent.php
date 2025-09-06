<?php

namespace App\Livewire;

use Livewire\Component;

class TestComponent extends Component
{
    public $showModal = false;
    
    public function openModal()
    {
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return '<div>
            <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded">
                테스트 버튼
            </button>
            
            @if($showModal)
                <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white p-6 rounded">
                        <h3>테스트 모달</h3>
                        <button wire:click="closeModal" class="mt-4 px-4 py-2 bg-red-600 text-white rounded">
                            닫기
                        </button>
                    </div>
                </div>
            @endif
        </div>';
    }
}