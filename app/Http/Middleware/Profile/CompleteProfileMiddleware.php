<?php

namespace App\Http\Middleware\Profile;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompleteProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();

        // It's better to move this logic into a method on the User model,
        // for example: $user->isProfileComplete()
        $profile = $user->profile;

        if (
            ! $profile ||
            ! $profile->tanggal_lahir || $profile->tanggal_lahir === '-' ||
            ! $profile->jenis_kelamin || $profile->jenis_kelamin === '-' ||
            ! $profile->kontak || $profile->kontak === '-' ||
            ! $profile->alamat || $profile->alamat === '-' ||
            ! $profile->foto ||
            $user->profile_status != 1
        ) {
            return redirect()->route('profile.index')->with('warning', 'Lengkapi profil Kamu terlebih dahulu.');
        }

        return $next($request);
    }
}
