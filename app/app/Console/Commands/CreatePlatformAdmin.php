<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\PlatformAdmin;
use App\Support\PhoneNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\password as promptPassword;
use function Laravel\Prompts\text;

/**
 * Creates a platform ("root") operator — the account that can create
 * organisations and map their domains (MEP.md 3.3).
 *
 * A fresh deployment has no way in without one, and the development seeder is
 * not appropriate for production, so this is the supported path. It is also
 * the recovery path after the switch to phone sign-in: accounts that had no
 * usable number were parked on a `pending-<id>` placeholder and cannot sign
 * in, so run this once to create a root login with a real number.
 *
 * Passwords are prompted for rather than passed as arguments by default, to
 * keep them out of the server's shell history.
 */
#[AsCommand(name: 'platform:create-admin', description: 'Create a platform (root) administrator')]
class CreatePlatformAdmin extends Command
{
    protected $signature = 'platform:create-admin
        {--name= : Full name of the operator}
        {--phone= : WhatsApp number used to sign in}
        {--generate-password : Generate a strong password and print it once}';

    public function handle(): int
    {
        $name = $this->option('name') ?: text(label: 'Name', required: true);

        $rawPhone = $this->option('phone') ?: text(
            label: 'WhatsApp number',
            placeholder: '98765 43210',
            required: true,
        );

        $phone = PhoneNumber::normalise((string) $rawPhone);

        if ($phone === null) {
            $this->components->error("[{$rawPhone}] is not a usable WhatsApp number.");

            return self::FAILURE;
        }

        $generated = (bool) $this->option('generate-password');
        $password = $generated ? Str::password(16) : promptPassword(
            label: 'Password',
            validate: fn (string $value): ?string => strlen($value) >= 12
                ? null
                : 'Use at least 12 characters.',
        );

        $validator = Validator::make(
            ['name' => $name, 'phone' => $phone, 'password' => $password],
            [
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', Rule::unique('platform_admins', 'phone')],
                'password' => ['required', 'string', 'min:12'],
            ],
            ['phone.unique' => 'A platform administrator already uses that number.'],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $admin = PlatformAdmin::create([
            'name' => $name,
            'phone' => $phone,
            'password' => Hash::make($password),
        ]);

        $this->components->info('Platform administrator created: '.PhoneNumber::forDisplay($admin->phone));

        if ($generated) {
            $this->newLine();
            $this->components->warn('Generated password (shown once — store it in your password manager now):');
            $this->line("  {$password}");
            $this->newLine();
        }

        $this->components->bulletList([
            'Sign in at https://'.config('platform.hostname').' with that number and password.',
            'Create the first organisation and map its domain from there.',
        ]);

        return self::SUCCESS;
    }
}
