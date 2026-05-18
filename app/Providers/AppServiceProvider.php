<?php

namespace App\Providers;

use App\Services\CaptchaImageManager;
use App\Services\CompatibleCaptcha;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Mews\Captcha\Captcha as MewsCaptcha;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageManager::class, function (): CaptchaImageManager {
            $driver = config('captcha.driver', 'gd') === 'imagick' ? new ImagickDriver : new GdDriver;

            return new CaptchaImageManager($driver);
        });

        $this->app->bind(MewsCaptcha::class, function (): CompatibleCaptcha {
            return new CompatibleCaptcha(
                $this->app->make(Filesystem::class),
                $this->app->make(Repository::class),
                $this->app->make(ImageManager::class),
                $this->app->make('session.store'),
                $this->app->make(BcryptHasher::class),
                $this->app->make(Str::class),
            );
        });

        $this->app->bind('captcha', fn (): MewsCaptcha => $this->app->make(MewsCaptcha::class));

        // Register optimization services in production
        if ($this->app->isProduction()) {
            $this->app['config']['app.debug'] = false;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL compatibility
        Schema::defaultStringLength(191);

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Optimize database queries - disable strict mode if needed
        // DB::statement("SET SESSION sql_mode=''");

        // Optimize eloquent model loading
        if ($this->app->environment('production')) {
            DB::enableQueryLog();
        }

        // Rate limiting for login
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->string('email')->lower();
            $ip = (string) $request->ip();

            // Create a more specific key by combining the email and IP address.
            $key = $email.'|'.$ip;

            return [
                // Limit attempts based on the email and IP combination.
                // Allows 5 attempts per minute for a specific user from a specific IP.
                Limit::perMinute(5)->by($key),

                // As an additional safeguard, limit attempts by IP address only.
                // This prevents a single IP from spamming the login form with many different emails.
                // Allows 20 attempts per minute from a single IP.
                Limit::perMinute(20)->by($ip),
            ];
        });

        // Rate limiting for API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiting for global requests
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)->by($request->ip());
        });
    }
}
