@livewire('auth.reset-password', [
    'token' => request('token'),
    'email' => request('email')
])