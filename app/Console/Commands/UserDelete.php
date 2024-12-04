<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserDelete extends Command
{
    protected $signature = 'wiki:userdel {email}';

    protected $description = 'Delete a user by email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('User not found.');

            return 1;
        }

        if (! $this->confirm("Are you sure you want to delete user: {$email}?")) {
            return 0;
        }

        $user->delete();
        $this->info('User deleted successfully.');

        return 0;
    }
}
