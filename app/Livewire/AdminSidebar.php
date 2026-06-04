<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

final class AdminSidebar extends Component
{
    public string $theme = 'auto';

    public bool $mobileMenuOpen = false;

    public int $userCount = 0;

    public function mount(): void
    {
        $this->theme = Auth::check() ? Auth::user()->theme?->value ?? 'auto' : 'auto';
        $this->mobileMenuOpen = false;
        $this->userCount = \App\Models\User::count();
    }

    public function toggleTheme(?string $newTheme = null): void
    {
        if ($newTheme) {
            $this->setTheme($newTheme);
        } else {
            // Cycle through: auto -> light -> dark -> auto
            $newTheme = match ($this->theme) {
                'auto' => 'light',
                'light' => 'dark',
                'dark' => 'auto',
                default => 'auto',
            };
            $this->setTheme($newTheme);
        }
    }

    // public function updatedTheme(string $value): void
    // {
    //     $this->setTheme($value);
    // }

    private function setTheme(string $theme): void
    {
        $this->theme = $theme;

        if (Auth::check()) {
            Auth::user()->update([
                'theme' => $this->theme,
            ]);
        }

        $this->dispatch('theme-updated', theme: $this->theme);

        $this->js(<<<JS
            const theme = '{$this->theme}';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'auto' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            localStorage.setItem('theme', theme);
            localStorage.setItem('flux.appearance', theme === 'auto' ? 'system' : theme);
        JS);
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

    public function render(): View
    {
        return view('livewire.admin-sidebar');
    }
}
