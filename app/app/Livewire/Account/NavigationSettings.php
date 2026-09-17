<?php

declare(strict_types=1);

namespace App\Livewire\Account;

use App\Livewire\Concerns\LazyPage;
use App\Livewire\Concerns\ResolvesMembership;
use App\Support\Navigation;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Defer;
use Livewire\Component;

/**
 * A person's own phone tab bar and dashboard button (account menu →
 * Navigation). Stored on their membership, so it follows them on every
 * device and never touches anyone else's.
 */
#[Defer]
class NavigationSettings extends Component
{
    use LazyPage, ResolvesMembership;

    /** The most tabs a phone bar takes beside Menu. */
    public const MAX_TABS = 4;

    /**
     * The tabs, in order, as [route, icon]. Added and removed one by one.
     *
     * @var array<int, array{route: string, icon: string}>
     */
    public array $mobileTabs = [];

    public string $quickAction = '';

    public function mount(): void
    {
        $this->load();
    }

    /**
     * Starts from the bar as it currently stands — their arrangement, or
     * the built-in one — so editing begins with what they see.
     */
    private function load(): void
    {
        $membership = $this->currentMembership();
        $sections = Navigation::forTenant($this->organisation(), $membership);

        $this->mobileTabs = [];

        foreach (Navigation::mobilePrimary($sections, $membership) as $item) {
            $this->mobileTabs[] = ['route' => $item['route'], 'icon' => $item['icon']];
        }

        $this->quickAction = $membership->quickAction() ?? '';
    }

    /**
     * A new tab, preset to the first destination not already on the bar.
     */
    public function addTab(): void
    {
        if (count($this->mobileTabs) >= self::MAX_TABS) {
            return;
        }

        $used = array_column($this->mobileTabs, 'route');
        $sections = Navigation::forTenant($this->organisation(), $this->currentMembership());

        foreach ($sections as $section) {
            foreach ($section['items'] as $item) {
                if (! in_array($item['route'], $used, true)) {
                    $this->mobileTabs[] = ['route' => $item['route'], 'icon' => $item['icon']];

                    return;
                }
            }
        }
    }

    public function removeTab(int $index): void
    {
        unset($this->mobileTabs[$index]);
        $this->mobileTabs = array_values($this->mobileTabs);
    }

    /**
     * A destination brings its own icon along when picked; the icon can
     * then be changed on its own.
     */
    public function updatedMobileTabs(mixed $value, string $key): void
    {
        if (! str_ends_with($key, '.route')) {
            return;
        }

        $index = (int) explode('.', $key)[0];
        $route = is_string($value) ? $value : '';

        if ($route === '') {
            $this->mobileTabs[$index]['icon'] = '';

            return;
        }

        foreach (Navigation::forTenant($this->organisation(), $this->currentMembership()) as $section) {
            foreach ($section['items'] as $item) {
                if ($item['route'] === $route) {
                    $this->mobileTabs[$index]['icon'] = $item['icon'];
                }
            }
        }
    }

    public function save(): void
    {
        $membership = $this->currentMembership();
        $destinations = array_keys(Navigation::destinations($this->organisation(), $membership));

        $this->validate([
            'mobileTabs' => ['array', 'max:'.self::MAX_TABS],
            'mobileTabs.*.route' => ['required', 'string', Rule::in($destinations)],
            'mobileTabs.*.icon' => ['nullable', 'string', Rule::in(['', ...array_keys(Navigation::ICONS)])],
            'quickAction' => ['nullable', 'string', Rule::in(['', ...array_keys(Navigation::QUICK_ACTIONS)])],
        ]);

        $tabs = [];

        foreach ($this->mobileTabs as $tab) {
            if ($tab['route'] !== '' && ! in_array($tab['route'], array_column($tabs, 'route'), true)) {
                $tabs[] = ['route' => $tab['route'], 'icon' => $tab['icon']];
            }
        }

        $membership->update(['navigation_settings' => [
            'mobile' => $tabs === [] ? null : $tabs,
            'quick_action' => $this->quickAction !== '' ? $this->quickAction : null,
        ]]);

        $this->load();

        session()->flash('status', 'Saved. Your phone bar and dashboard button follow it from the next page.');
    }

    public function reset_(): void
    {
        $this->currentMembership()->update(['navigation_settings' => null]);
        $this->load();

        session()->flash('status', 'Back to the built-in arrangement.');
    }

    public function render(): View
    {
        $organisation = $this->organisation();
        $membership = $this->currentMembership();

        return view('livewire.account.navigation-settings', [
            'organisation' => $organisation,
            'destinations' => Navigation::destinations($organisation, $membership),
            'icons' => Navigation::ICONS,
            'quickActions' => collect(Navigation::QUICK_ACTIONS)
                ->filter(fn (array $action): bool => $organisation->hasFeature($action['feature']) && $membership->user?->can($action['ability'][0], $action['ability'][1]))
                ->map(fn (array $action): string => $action['label'])
                ->all(),
            'isCustom' => $membership->navigation_settings !== null,
        ])->layout('components.layouts.app', ['heading' => 'Navigation']);
    }
}
