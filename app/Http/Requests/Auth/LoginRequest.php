<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            'email' => ['required', 'string', 'email'],
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

        $email = $this->string('email');
        $password = $this->string('password');
        $remember = $this->boolean('remember');

        // First, try to authenticate as a candidate
        $candidate = \App\Models\Candidate::where('email', $email)->first();
        if ($candidate && \Illuminate\Support\Facades\Hash::check($password, $candidate->password)) {
            Auth::guard('candidate')->login($candidate, $remember);
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // If not a candidate, try to authenticate as an employee (User)
        $user = \App\Models\User::where('email', $email)->first();
        
        // Block candidates trying to login as users
        if ($user && ($user->role === 'candidate' || ($user->role === 'user' && !$user->is_admin))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Candidates should use the candidate login. Please contact support if you need access.',
            ]);
        }
        
        if ($user && ($user->is_banned ?? false)) {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'email' => 'Your account has been banned. Please contact the administrator.',
            ]);
        }

        if (! Auth::guard('web')->attempt($this->only('email', 'password'), $remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Double-check after authentication (in case user was banned during session)
        if (Auth::guard('web')->user() && (Auth::guard('web')->user()->is_banned ?? false)) {
            Auth::guard('web')->logout();
            $this->session()->invalidate();
            $this->session()->regenerateToken();
            
            throw ValidationException::withMessages([
                'email' => 'Your account has been banned. Please contact the administrator.',
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
            'email' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
