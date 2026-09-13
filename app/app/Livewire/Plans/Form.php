<?php

declare(strict_types=1);

namespace App\Livewire\Plans;

use App\Enums\PlanStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Plan;
use App\Support\Money;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use ResolvesMembership;

    public ?Plan $plan = null;

    public string $name = '';

    public string $description = '';

    /** Entered in major units by the operator; stored as minor units. */
    public string $price = '';

    public string $durationDays = '30';

    public string $sessionLimit = '';

    public string $status = 'active';

    public function mount(?Plan $plan = null): void
    {
        $this->authorize($plan ? 'update' : 'create', Plan::class);

        $this->plan = $plan;

        if ($plan) {
            $this->name = $plan->name;
            $this->description = (string) $plan->description;
            $this->price = (string) Money::ofMinor($plan->price_minor, $plan->currency_code)->major();
            $this->durationDays = (string) $plan->duration_days;
            $this->sessionLimit = (string) ($plan->session_limit ?? '');
            $this->status = $plan->status->value;
        }
    }

    public function save(): void
    {
        $this->authorize($this->plan ? 'update' : 'create', Plan::class);

        $organisation = $this->organisation();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'durationDays' => ['required', 'integer', 'min:1', 'max:3650'],
            'sessionLimit' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'status' => ['required', Rule::enum(PlanStatus::class)],
        ]);

        $amount = Money::parseMajor($validated['price'], $organisation->currency_code);

        if ($amount === null) {
            $this->addError('price', 'Enter a valid amount.');

            return;
        }

        $attributes = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?: null,
            'price_minor' => $amount->minor,
            'currency_code' => $organisation->currency_code,
            'duration_days' => (int) $validated['durationDays'],
            'session_limit' => $validated['sessionLimit'] !== '' ? (int) $validated['sessionLimit'] : null,
            'status' => $validated['status'],
        ];

        $this->plan ? $this->plan->update($attributes) : Plan::create($attributes);

        session()->flash('status', "\"{$validated['name']}\" was saved.");

        $this->redirect(route('tenant.plans.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.plans.form', [
            'organisation' => $this->organisation(),
            'statuses' => PlanStatus::cases(),
        ])->layout('components.layouts.app', [
            'heading' => $this->plan ? "Edit {$this->plan->name}" : 'New plan',
        ]);
    }
}
