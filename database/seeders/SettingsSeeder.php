<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'GitWiki',
                'value_type' => 'string',
                'edit' => true,
            ],
            [
                'key' => 'git_user_name',
                'value' => 'GitWiki',
                'value_type' => 'string',
                'edit' => true,
            ],
            [
                'key' => 'git_user_email',
                'value' => 'app@git.wiki',
                'value_type' => 'string',
                'edit' => true,
            ],
            [
                'key' => 'public_wiki',
                'value' => false,
                'value_type' => 'boolean',
                'edit' => true,
            ],
            [
                'key' => 'footer_copyright',
                'value' => '',
                'value_type' => 'string',
                'edit' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Settings::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
