<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public bool $open = false;
    public int $perPage = 10;

    #[Computed]
    public function getNotificationsProperty()
    {
        return auth()->user()->notifications()->latest()->take($this->perPage)->get();
    }

    public function getUnreadCountProperty(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function toggle(): void
    {
        $this->open = !$this->open;
    }

    public function markAsRead(string $id): void
    {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
    }

    public function navigateNotif(string $id, $href)
    {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return $this->redirect($href);
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification(string $id): void
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
    }

    #[On('notification-sent')]
    public function refresh(): void {}
};
?>

<flux:dropdown
    align="end"
    gap="8"
    position="bottom"
    wire:poll.30s
>

    <flux:button
        aria-label="Notifications"
        icon="bell"
        inset="top bottom"
        variant="ghost"
    >
        @if ($this->unreadCount > 0)
            <flux:badge
                color="red"
                inset="top right"
                size="sm"
            >
                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
            </flux:badge>
        @endif
    </flux:button>

    <flux:menu class="w-96 overflow-hidden p-0">

        <div class="flex items-center justify-between border-b border-zinc-100 px-4 py-3 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <flux:heading class="font-semibold" size="sm">Notifications</flux:heading>
                @if ($this->unreadCount > 0)
                    <span
                        class="inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-blue-100 px-1.5 text-xs font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-300"
                    >
                        {{ $this->unreadCount }}
                    </span>
                @endif
            </div>

            @if ($this->unreadCount > 0)
                <flux:button
                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400"
                    size="sm"
                    variant="ghost"
                    wire:click="markAllAsRead"
                >
                    Mark all as read
                </flux:button>
            @endif
        </div>

        <div class="max-h-[26rem] divide-y divide-zinc-100 overflow-y-auto dark:divide-zinc-800">

            @forelse ($this->notifications as $notif)
                @php
                    $data = $notif->data;
                    $isUnread = is_null($notif->read_at);
                    $icon = match ($data['type'] ?? 'info') {
                        'success' => 'check-circle',
                        'warning' => 'exclamation-triangle',
                        'danger' => 'x-circle',
                        default => 'information-circle',
                    };
                    $iconBg = match ($data['type'] ?? 'info') {
                        'success' => 'bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400',
                        'warning' => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400',
                        'danger' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400',
                        default => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400',
                    };
                @endphp

                <div class="{{ $isUnread ? 'bg-blue-50/60 dark:bg-blue-950/20 hover:bg-blue-50 dark:hover:bg-blue-950/30' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/50' }} group relative flex items-start gap-3 px-4 py-3.5 transition-colors"
                    wire:key="notif-{{ $notif->id }}"
                >
                    <div class="{{ $iconBg }} mt-0.5 shrink-0 rounded-full p-1.5">
                        <flux:icon
                            :icon="$icon"
                            class="size-4"
                            variant="mini"
                        />
                    </div>

                    <div class="min-w-0 flex-1 space-y-0.5">
                        @if (!empty($data['title']))
                            <p class="truncate text-sm font-semibold leading-snug text-zinc-900 dark:text-zinc-100">
                                {{ $data['title'] }}
                            </p>
                        @endif

                        <p class="line-clamp-2 text-sm leading-snug text-zinc-600 dark:text-zinc-400">
                            {{ $data['message'] }}
                        </p>

                        <div class="flex items-center gap-3 pt-0.5">
                            <span class="text-xs text-zinc-400">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                            @if ($isUnread)
                                <span class="inline-block size-2 rounded-full bg-blue-500"></span>
                            @endif
                        </div>
                    </div>

                    <div
                        class="absolute right-3 top-3 flex items-center gap-0.5 opacity-0 transition-opacity group-hover:opacity-100">
                        @if (!empty($data['action_url']))
                            <flux:button
                                class="text-zinc-400 hover:text-blue-600"
                                icon="eye"
                                inset="top bottom"
                                size="sm"
                                title="{{ $data['action_text'] ?? 'View' }}"
                                variant="ghost"
                                wire:click="navigateNotif('{{ $notif->id }}', '{{ $data['action_url'] }}')"
                            />
                        @endif

                        @if ($isUnread)
                            <flux:button
                                class="text-zinc-400 hover:text-blue-600"
                                icon="check"
                                inset="top bottom"
                                size="sm"
                                title="Mark as read"
                                variant="ghost"
                                wire:click="markAsRead('{{ $notif->id }}')"
                            />
                        @endif
                    </div>
                </div>

            @empty
                <div class="flex flex-col items-center justify-center gap-3 py-16 text-zinc-400">
                    <div class="rounded-full bg-zinc-100 p-4 dark:bg-zinc-800">
                        <flux:icon
                            class="size-7 text-zinc-400"
                            icon="bell-slash"
                            variant="outline"
                        />
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">All caught up</p>
                        <p class="mt-0.5 text-xs text-zinc-400 dark:text-zinc-500">No notifications yet</p>
                    </div>
                </div>
            @endforelse

        </div>

        @if ($this->notifications->count() >= $perPage)
            <div class="border-t border-zinc-100 px-4 py-2.5 dark:border-zinc-800">
                <flux:button
                    class="w-full justify-center text-xs text-zinc-500 hover:text-zinc-700"
                    size="sm"
                    variant="ghost"
                    wire:click="$set('perPage', {{ $perPage + 10 }})"
                >
                    Load more notifications
                </flux:button>
            </div>
        @endif
    </flux:menu>
</flux:dropdown>
