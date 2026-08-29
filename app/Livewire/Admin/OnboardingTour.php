<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class OnboardingTour extends Component
{
    public bool $show = false;
    public int $step = 0;

    public function mount(): void
    {
        $this->show = admin() && admin()->onboarding_completed_at === null;
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(0, $this->step - 1);
    }

    public function dismiss(): void
    {
        admin()->update(['onboarding_completed_at' => now()]);
        $this->show = false;
    }

    public function dismissForever(): void
    {
        $this->dismiss();
    }

    public function goToSettings()
    {
        $this->dismiss();

        return redirect()->route('admin.settings');
    }

    public function render()
    {
        return view('livewire.admin.onboarding-tour');
    }
}
