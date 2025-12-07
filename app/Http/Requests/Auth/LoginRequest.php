<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash; // TAMBAHKAN INI
use App\Models\User; // TAMBAHKAN INI

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'no_hp' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // HAPUS BAGIAN DEBUG INI atau komentar jika masih butuh debug
        /*
        $credentials = $this->only('no_hp', 'password');
        $remember = $this->boolean('remember');
        
        dd([
            'credentials' => $credentials,
            'remember' => $remember,
            'auth_attempt' => Auth::attempt($credentials, $remember),
            'user_exists' => User::where('no_hp', $credentials['no_hp'])->exists(),
            'password_match' => function() use ($credentials) {
                $user = User::where('no_hp', $credentials['no_hp'])->first();
                return $user ? Hash::check($credentials['password'], $user->password) : false;
            },
            'actual_password_match' => function() use ($credentials) {
                $user = User::where('no_hp', $credentials['no_hp'])->first();
                if (!$user) return false;
                
                $isMatch = Hash::check($credentials['password'], $user->password);
                
                // Debug tambahan
                dd([
                    'input_password' => $credentials['password'],
                    'stored_hash' => $user->password,
                    'hash_check' => $isMatch,
                    'hash_info' => password_get_info($user->password)
                ]);
                
                return $isMatch;
            }
        ]);
        */

        if (! Auth::attempt($this->only('no_hp', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'no_hp' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'no_hp' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('no_hp')).'|'.$this->ip());
    }
}