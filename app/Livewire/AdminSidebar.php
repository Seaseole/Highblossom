<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class AdminSidebar extends Component
{
    public string $theme = 'auto';

    public bool $mobileMenuOpen = false;

    public function mount(): void
    {
        $this->theme = Auth::check() ? Auth::user()->theme_preference ?? 'auto' : 'auto';
        $this->mobileMenuOpen = false;
    }

    public function toggleTheme(): void
    {
        // Cycle through: auto -> light -> dark -> auto
        $this->theme = match ($this->theme) {
            'auto' => 'light',
            'light' => 'dark',
            'dark' => 'auto',
            default => 'auto',
        };

        if (Auth::check()) {
            Auth::user()->update([
                'theme_preference' => $this->theme,
            ]);
        }

        $this->dispatch('theme-changed', theme: $this->theme);
    }

    public function isEffectiveDark(): bool
    {
        return match ($this->theme) {
            'dark' => true,
            'light' => false,
            default => request()->hasHeader('Sec-CH-UA-Platform') 
                ? false // Server-side fallback
                : true, // Will be resolved client-side
        };
    }

    public function closeMobileMenu(): void
    {
        $this->mobileMenuOpen = false;
    }

    public function openMobileMenu(): void
    {
        $this->mobileMenuOpen = true;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin-sidebar');
    }
}
