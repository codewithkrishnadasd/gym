<form method="POST" action="{{ route('platform.organisations.update', $organisation) }}" class="space-y-5">
    @csrf
    @method('PUT')
    <input type="hidden" name="section" value="general">

    <x-ui.card title="General">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.input name="name" id="name" label="Name" value="{{ old('name', $organisation->name) }}" required />
            <x-ui.input name="slug" id="slug" label="Slug" value="{{ old('slug', $organisation->slug) }}" required />

            <x-ui.select name="status" id="status" label="Status">
                @foreach (\App\Enums\OrganisationStatus::cases() as $case)
                    <option value="{{ $case->value }}" @selected(old('status', $organisation->status->value) === $case->value)>
                        {{ $case->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.field label="Timezone" for="timezone" name="timezone">
                <input id="timezone" name="timezone" type="text" list="timezone-options" required
                    value="{{ old('timezone', $organisation->timezone) }}"
                    class="min-h-[40px] w-full rounded-lg border border-hairline-strong bg-surface px-3 py-2 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25 max-lg:min-h-[44px]">
                <datalist id="timezone-options">
                    @foreach ($timezones as $timezone)
                        <option value="{{ $timezone }}"></option>
                    @endforeach
                </datalist>
            </x-ui.field>

            <x-ui.input name="currency_code" id="currency_code" label="Currency code" maxlength="3" required
                value="{{ old('currency_code', $organisation->currency_code) }}" class="uppercase"
                hint="ISO 4217, e.g. INR, USD, GBP." />

            <x-ui.input name="locale" id="locale" label="Locale" required
                value="{{ old('locale', $organisation->locale) }}" hint="Date and currency formatting." />

            <x-ui.input name="contact_email" id="contact_email" label="Contact email" type="email"
                value="{{ old('contact_email', $organisation->contact_email) }}" />

            <x-ui.input name="contact_phone" id="contact_phone" label="Contact phone"
                value="{{ old('contact_phone', $organisation->contact_phone) }}" />
        </div>
    </x-ui.card>

    <x-ui.button type="submit" variant="primary" size="lg">Save changes</x-ui.button>
</form>
