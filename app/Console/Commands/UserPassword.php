<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserPassword extends Command
{
    protected $signature = 'gitwiki:passwd {email} {password}';

    protected $description = 'Reset user password';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (!is_string($password) || strlen((string) $password) < 8) {
            $this->error('Password must be at least 8 characters long');
            return 1;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('User not found.');

            return 1;
        }

        $user->update([
            'password' => Hash::make((string) $password),
        ]);

        $this->info('Password updated successfully.');

        return 0;
    }
}
