<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use App\Models\Hospital;
use App\Models\Department;
use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();

                    // Optional: delete old tokens
                    // $user->tokens()->delete();

                    // Generate token
                    $token = $user->createToken('ui-token')->plainTextToken;

                    // Pass token to view using session flash or query string
                    session(['token' => $token]);

                    return redirect()->intended('/dashboard');

                }
            };
        });
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::registerView(function () {
            return view('auth.register', [
                'departments' => Department::all(),
                'hospitals' => Hospital::all(),
            ]);
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });


    //    Fortify::authenticateUsing(function (Request $request) {
    //         $login = $request->input('login'); // email or id_pegawai
    //         $password = $request->input('password');

    //         $user = User::where('email', $login)
    //                 ->orWhere('id_pegawai', $login)
    //                 ->first();

    //         if ($user && Hash::check($password, $user->password)) {
    //             return $user;
    //         }

    //         return null;
    //     });


        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
