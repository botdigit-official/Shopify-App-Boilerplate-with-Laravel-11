<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    protected $fillable = [
      'is_installed',
        'completed_steps',
        'post_install_redirect',
        'display_mode',
        'admin_redirect_url',
        'enable_frontend',
        'frontend_url'
    ];

    protected $casts = [
       'is_installed' => 'boolean',
        'completed_steps' => 'array',
        'enable_frontend' => 'boolean'
    ];

    public static function isFirstInstall(): bool
    {
        return !self::where('is_installed', true)->exists();
    }

    public static function markStepComplete(string $step): void
    {
        $settings = self::firstOrCreate();
        $completedSteps = $settings->completed_steps ?? [];
        $completedSteps[] = $step;
        $settings->update(['completed_steps' => array_unique($completedSteps)]);
    }

    public static function isStepComplete(string $step): bool
    {
        $settings = self::first();
        return $settings && in_array($step, $settings->completed_steps ?? []);
    }
    public function getPostInstallUrl(): string
    {
        if ($this->post_install_redirect === 'frontend' && $this->enable_frontend) {
            return $this->frontend_url ?? route('shopify.dashboard');
        }

        return $this->admin_redirect_url ?? route('shopify.dashboard');
    }

    public function shouldDisplayEmbedded(): bool
    {
        return $this->display_mode === 'embedded';
    }
}