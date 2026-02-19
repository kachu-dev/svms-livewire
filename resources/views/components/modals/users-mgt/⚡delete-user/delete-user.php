<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?User $user = null;

    public $name;

    #[On('confirm-delete-user')]
    public function setFields($id): void
    {
        $this->user = User::find($id);

        $this->name = $this->user->name;

        $this->modal('delete-user')->show();
    }

    public function delete(): void
    {
        $this->user->delete();

        Toaster::success('User deactivated successfully!');

        $this->redirectRoute('staff.users-mgt.index');
    }
};
