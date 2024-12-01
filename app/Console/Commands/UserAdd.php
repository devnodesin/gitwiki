<?php

namespace App\Console\Commands;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserAdd extends Command
{
    protected $signature = 'gitwiki:useradd';

    protected $description = 'Add new user';

    public function handle()
    {
        $name = $this->ask('Enter name');
        $email = $this->ask('Enter email');
        $password = $this->secret('Enter password');
        $role = $this->choice('Select role', UserRoles::values(), 0);

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'role' => ['required', 'string', Rule::in(UserRoles::values())],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make((string) $password),
            'role' => $role,
        ]);

        $this->info('User created successfully.');

        return 0;
    }
}
