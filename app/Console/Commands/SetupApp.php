<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AppSettings;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SetupApp extends Command
{
    protected $signature = 'app:setup
                            {--force : Force setup even if already configured}
                            {--check : Only check current setup status}';

    protected $description = 'Setup initial app configuration or check current status';

    protected $requiredEnvVars = [
        'SHOPIFY_API_KEY',
        'SHOPIFY_API_SECRET',
        'SHOPIFY_SCOPES',
        'SHOPIFY_REDIRECT_URI',
    ];

    public function handle()
    {
        $status = $this->checkCurrentSetup();

        if ($this->option('check')) {
            $this->showCurrentStatus($status);
            return;
        }

        if ($status['all_set'] && !$this->option('force')) {
            $this->info('All components are already set up. Use --force to re-run the setup.');
            return;
        }

        $this->performRequiredSetup($status);
    }

    protected function checkCurrentSetup(): array
    {
        $status = [
            'env_exists' => File::exists(base_path('.env')),
            'env_configured' => false,
            'db_migrated' => false,
            'app_settings' => false,
            'storage_linked' => File::exists(public_path('storage')),
            'all_set' => false,
            'missing_env' => [],
        ];

        if ($status['env_exists']) {
            foreach ($this->requiredEnvVars as $var) {
                if (empty(env($var))) {
                    $status['missing_env'][] = $var;
                }
            }
            $status['env_configured'] = empty($status['missing_env']);
        }

        try {
            $status['db_migrated'] = Schema::hasTable('app_settings');
        } catch (\Exception $e) {
            $status['db_migrated'] = false;
        }

        $status['app_settings'] = AppSettings::exists();

        $status['all_set'] = $status['env_exists'] &&
                             $status['env_configured'] &&
                             $status['db_migrated'] &&
                             $status['app_settings'] &&
                             $status['storage_linked'];

        return $status;
    }

    protected function showCurrentStatus(array $status)
    {
        $this->info('Current Setup Status:');
        $this->table(
            ['Component', 'Status'],
            [
                ['.env file', $status['env_exists'] ? '✅ Exists' : '❌ Missing'],
                ['Environment Config', $status['env_configured'] ? '✅ Configured' : '❌ Incomplete'],
                ['Database Migrations', $status['db_migrated'] ? '✅ Migrated' : '❌ Pending'],
                ['App Settings', $status['app_settings'] ? '✅ Created' : '❌ Missing'],
                ['Storage Link', $status['storage_linked'] ? '✅ Linked' : '❌ Not Linked'],
            ]
        );

        if (!empty($status['missing_env'])) {
            $this->warn('Missing environment variables:');
            foreach ($status['missing_env'] as $var) {
                $this->line("  - $var");
            }
        }

        $this->showNextSteps($status);
    }

    protected function showNextSteps(array $status)
    {
        $this->info('Next Steps:');

        if (!$status['env_exists']) {
            $this->line(" • Create .env file: cp .env.example .env");
        }

        if (!empty($status['missing_env'])) {
            $this->line(" • Configure missing environment variables in .env file");
        }

        if (!$status['db_migrated']) {
            $this->line(" • Run migrations: php artisan migrate");
        }

        if (!$status['app_settings']) {
            $this->line(" • Initialize app settings");
        }

        if (!$status['storage_linked']) {
            $this->line(" • Create storage link: php artisan storage:link");
        }

        if ($status['all_set']) {
            $this->info('✅ All components are set up properly!');
            $this->line("\nAvailable commands:");
            $this->line(" • Clear all caches: php artisan optimize:clear");
            $this->line(" • Force setup refresh: php artisan app:setup --force");
            $this->line(" • View routes: php artisan route:list");
        } else {
            $this->line("\nRun setup command:");
            $this->line(" • Complete setup: php artisan app:setup --force");
        }
    }

    protected function performRequiredSetup(array $status)
    {
        $this->info('Starting setup for missing components...');

        if (!$status['env_exists']) {
            $this->info('Creating .env file...');
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->call('key:generate');
        }

        if (!$status['env_configured']) {
            $this->warn('Please configure the missing environment variables in your .env file.');
            return;
        }

        if (!$status['db_migrated'] || $this->option('force')) {
            $this->info('Running migrations...');
            $this->call('migrate:fresh', [
                '--force' => true,
                '--seed' => true,
            ]);
        }

        if (!$status['app_settings'] || $this->option('force')) {
            $this->info('Creating app settings...');
            AppSettings::firstOrCreate([]);
        }

        if (!$status['storage_linked'] || $this->option('force')) {
            $this->info('Creating storage link...');
            $this->call('storage:link');
        }

        $this->call('optimize:clear');

        $this->info('Setup completed successfully!');
        $this->info('Run "php artisan app:setup --check" to verify the setup.');
    }
}
