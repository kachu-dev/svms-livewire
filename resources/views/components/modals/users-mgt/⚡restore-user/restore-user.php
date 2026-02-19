<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?User $user = null;

    public $name;

    #[On('restore-user')]
    public function setFields($id): void
    {
        $this->user = User::onlyTrashed()->find($id);

        $this->name = $this->user->name;

        $this->modal('restore-user')->show();
    }

    public function restore(): void
    {
        $this->user->restore();

        Toaster::success('User reactivated successfully!');

        $this->redirectRoute('staff.users-mgt.deleted');
    }
};
