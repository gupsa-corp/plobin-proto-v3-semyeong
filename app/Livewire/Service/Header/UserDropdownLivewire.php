<?php

namespace App\Livewire\Service\Header;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserDropdownLivewire extends Component
{
    public $isOpen = false;
    
    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }
    
    public function closeDropdown()
    {
        $this->isOpen = false;
    }
    
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect('/login');
    }
    
    public function render()
    {
        return view('300-page-service.300-common.300-livewire-dropdown-user');
    }
}