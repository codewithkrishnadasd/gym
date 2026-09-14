<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Enums\NotificationActionType;
use App\Enums\NotificationStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\WhatsappActionNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * The outbox of messages waiting to be sent on WhatsApp (MEP.md 5.14).
 *
 * The platform never sends anything itself: it composes the message and an
 * operator opens WhatsApp to send it. Without this screen, a message missed at
 * the moment it was generated — the admin navigated away, closed the tab,
 * confirmed a payment on a phone with no WhatsApp — is simply never sent, and
 * nobody can tell. Everything still marked "Ready to send" is that backlog.
 *
 * "Opened" means an operator launched the deep link, never that WhatsApp
 * delivered anything. The column is labelled accordingly.
 */
class Index extends Component
{
    use ResolvesMembership, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $action = '';

    public function mount(): void
    {
        $this->authorize('sendNotifications', $this->organisation());
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    /**
     * Re-renders when a panel in the list edits, sends or skips a message, so
     * the counts at the top and the set of rows stay in step with it.
     */
    #[On('notification-updated')]
    public function refreshList(): void
    {
        // Livewire re-renders on any handled event; the work is the re-query.
    }

    /**
     * @return LengthAwarePaginator<int, WhatsappActionNotification>
     */
    protected function messages(): LengthAwarePaginator
    {
        return WhatsappActionNotification::query()
            ->with(['createdBy.user:id,name', 'openedBy.user:id,name'])
            ->when($this->search !== '', fn (Builder $query) => $query->where(
                fn (Builder $inner) => $inner->where('recipient_name', 'ilike', "%{$this->search}%")
                    ->orWhere('recipient_phone', 'ilike', "%{$this->search}%")
            ))
            // The default view is the work still to do: anything sent or
            // deliberately skipped is a decision already taken. Both stay
            // reachable by picking that status explicitly.
            ->when(
                $this->status !== '',
                fn (Builder $query) => $query->where('status', $this->status),
                fn (Builder $query) => $query->whereNotIn('status', [
                    NotificationStatus::Skipped,
                    NotificationStatus::Opened,
                ]),
            )
            ->when($this->action !== '', fn (Builder $query) => $query->where('action_type', $this->action))
            // Unsent first, then newest: the queue's job is to surface what
            // still needs doing, not to be a chronological archive.
            ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [NotificationStatus::Ready->value])
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    /**
     * @return array<string, int>
     */
    protected function counts(): array
    {
        /** @var array<string, int> $counts */
        $counts = WhatsappActionNotification::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return $counts;
    }

    public function render(): View
    {
        $counts = $this->counts();

        return view('livewire.notifications.index', [
            'organisation' => $this->organisation(),
            'messages' => $this->messages(),
            'statuses' => NotificationStatus::cases(),
            'actionTypes' => NotificationActionType::cases(),
            'readyCount' => $counts[NotificationStatus::Ready->value] ?? 0,
            'openedCount' => $counts[NotificationStatus::Opened->value] ?? 0,
            'unavailableCount' => $counts[NotificationStatus::Unavailable->value] ?? 0,
        ])->layout('components.layouts.app', ['heading' => 'Messages']);
    }
}
