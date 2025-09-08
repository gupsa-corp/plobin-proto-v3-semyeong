<?php

namespace App\Livewire;

use Livewire\Component;

class PageMenuDropdown extends Component
{
    public $page;
    public $isOpen = false;

    public function mount($page)
    {
        $this->page = $page;
    }

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function closeDropdown()
    {
        $this->isOpen = false;
    }

    public function updatePageTitle($pageId, $newTitle)
    {
        $this->closeDropdown();
        $this->dispatch('updatePageTitle', pageId: $pageId, newTitle: $newTitle);
    }

    public function addChildPage($pageId)
    {
        $this->closeDropdown();
        $this->dispatch('addChildPage', pageId: $pageId);
    }

    public function deletePage($pageId)
    {
        $this->closeDropdown();
        $this->dispatch('deletePage', pageId: $pageId);
    }

    public function render()
    {
        return view('livewire.page-menu-dropdown');
    }
}
