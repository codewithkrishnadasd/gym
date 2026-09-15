<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Task;
use App\Models\TaskReminder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

/**
 * The reminder list shown on opening the app: every reminder whose day has
 * arrived, on a task that is still open and that the viewer is involved in
 * (admins: every task). It appears once per page load — a refresh or a fresh
 * sign-in brings it back — and each entry leads to its task.
 */
class Reminders extends Component
{
    use ResolvesMembership;

    /**
     * @return Collection<int, TaskReminder>
     */
    protected function due(): Collection
    {
        $membership = $this->currentMembership();
        $today = Carbon::today($this->organisation()->timezone);

        return TaskReminder::query()
            ->with(['task:id,title,task_category_id', 'task.category:id,name'])
            ->whereDate('remind_on', '<=', $today->toDateString())
            ->whereHas('task', fn (Builder $task) => $task
                ->whereNull('completed_at')
                ->when(! $membership->isAdmin(), fn (Builder $query) => $query->involving($membership)))
            ->orderBy('remind_on')
            ->orderBy('id')
            ->limit(50)
            ->get();
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.tasks.reminders', [
            'organisation' => $organisation,
            'today' => Carbon::today($organisation->timezone),
            'reminders' => $this->due(),
        ]);
    }
}
